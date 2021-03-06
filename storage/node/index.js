#!/usr/bin/env node

require('dotenv').config({ path: './.env' })
const moment = require('moment-timezone')
const _ = require('lodash')
let models  = require('./models')
const mqtt = require('mqtt')
const client  = mqtt.connect(`ws://${process.env.MQTT_HOST}:${process.env.MQTT_PORT}`)
let devices = []
const redisDriver = require("redis")

const redis = redisDriver.createClient({
  port: process.env.REDIS_PORT,
  host: process.env.REDIS_HOST
})

moment.tz.setDefault(process.env.APP_TIMEZONE)

client.on('connect', function () {
  console.log('Connected to mqtt server')
})

client.on('message', (topic, message) => {
  let data = topic.split('/')
  let msg = Number(message.toString())
  if (data[0] != 'dev') {
    insert(data[0], data[1], msg)
  } else {
    setOnline(data[1], Boolean(msg))
  }
})

async function setOnline(device, value) {
  try {
    let d = await models.Device.findOne({
      where: {
        name: device
      }
    })
    if (d) {
      if (value != d.online) {
        await d.update({
          online: value
        })
        redis.set(d.name, Number(value).toString())
        let rooms = await models.Room.findAll({
          where: {
            device_id: d.id
          }
        })
        rooms.forEach(room => {
          setTimeout(() => {
            publish('led', room.name, 'a')
          }, Number(process.env.MQTT_CONTROL_INTERVAL) * 1000)
        })
      } else {
        redis.set(`${d.name}.lastMeasurement`, moment().format())
      }
    }
  } catch (e) {
    console.error(e)
  }
}

function publish(pre, key, value) {
  redis.get(key, (err, r) => {
    if (r !== value) {
      redis.set(key, value)
      client.publish(`${pre}/${key}`, value)
    }
  })
}

async function insert(magnitude, room, value) {
  try {
    let m = await models.Magnitude.findOne({
      where: {
        name: magnitude
      }
    })
    let r = await models.Room.findOne({
      where: {
        name: room
      }
    })
    let params = await models.MagnitudeRoom.findOne({
      where: {
        magnitude_id: m.id,
        room_id: r.id
      }
    })
    if (params) {
      if (magnitude == process.env.MQTT_CONTROL_PARAM) {
        if (value < params.min_limit) {
          publish('led', r.name, 'b')
        } else if (value < params.max_limit) {
          publish('led', r.name, 'g')
        } else {
          publish('led', r.name, 'r')
        }
      }
      let lastMeasurement = await models.Measurement.findOne({
        where: {
          magnitude_id: m.id,
          room_id: r.id
        },
        order: [['created_at', 'DESC']]
      })
      if (params.interval > 0) {
        if (!lastMeasurement) {
          lastMeasurement = await models.Measurement.create({
            magnitude_id: m.id,
            room_id: r.id,
            value: value
          })
        } else {
          let end = moment()
          let start = moment.tz(lastMeasurement.createdAt, process.env.APP_TIMEZONE).add(4, 'hours')
          if (end.diff(start, 'minutes') >= params.interval) {
            lastMeasurement = await models.Measurement.create({
              magnitude_id: m.id,
              room_id: r.id,
              value: value
            })
          }
        }
      }
    }
  } catch (e) {
    console.error(e)
  }
}

async function verifyConnection() {
  try {
    let cacheDevices = devices.map(o => o.id)
    let newDevices = await models.Device.findAll({
      attributes: [
        'id'
      ]
    }).map(o => o.id)
    if (_.xor(cacheDevices, newDevices).length > 0) {
      devices = await models.Device.findAll()
      console.log(`Updating cached devices from ${cacheDevices.toString()} to ${newDevices.toString()}`)
    }
    if (devices.length > 0) {
      devices.forEach(d => {
        let end = moment()
        let key = `${d.name}.lastMeasurement`
        redis.get(key, (err, value) => {
          if (value) {
            let start = moment(value)
            if (end.diff(start, 'seconds') >= 2 * Number(process.env.MQTT_CONTROL_INTERVAL)) {
              publish('dev', d.name, '0')
            }
          } else {
            redis.set(key, moment().format())
            if (!d.online) {
              publish('dev', d.name, '0')
            }
          }
        })
      })
    }
  } catch (e) {
    console.error(e)
  }
}

async function main() {
  try {
    setInterval(() => {
      verifyConnection()
    }, Number(process.env.MQTT_CONTROL_INTERVAL) * 1000)
    devices = await models.Device.findAll()
    let magnitudes = await models.Magnitude.findAll()
    magnitudes.forEach(magnitude => {
      let topic = `${magnitude.name}/+`
      client.subscribe(topic, e => {
        if (e) {
          console.error(e)
        } else {
          console.log(`Subscribed to ${topic}`)
        }
      })
    })
    topic = `dev/+`
    client.subscribe(topic, e => {
      if (e) {
        console.error(e)
      } else {
        console.log(`Subscribed to ${topic}`)
      }
    })
  } catch (e) {
    client.end
    console.error(e)
  }
}

main()