#!/usr/bin/env node

require('dotenv').config({ path: './.env' })
const moment = require('moment')
const _ = require('lodash')
let models  = require('./models')
const mqtt = require('mqtt')
const client  = mqtt.connect(`ws://${process.env.MQTT_HOST}:${process.env.MQTT_PORT}`)
var devices = []

client.on('connect', function () {
  console.log('Connected to mqtt server')
})

client.on('message', (topic, message) => {
  let data = topic.split('/')
  let value = Number(message.toString())
  if (data[0] == 'dev') {
    setOnline(data[1], Boolean(value))
  } else {
    insert(data[0], data[1], value)
  }
})

async function setOnline(device, status) {
  try {
    let index = await _.findIndex(devices, function(o) {return o.name == device})
    if (index != -1) {
      let dev = devices[index]
      if (status) {
        devices[index].updatedStatusAt = new Date()
      }
      if (dev.online != status) {
        devices[index] = await dev.update({
          online: status
        })
      }
    } else {
      console.log(`Unknown device ${device}`)
    }
  } catch (e) {
    console.log(e)
  }
}

async function verifyState() {
  try {
    _.forEach(devices, async function(device) {
      if (device.hasOwnProperty('updatedStatusAt')) {
        if ((moment().diff(moment(device.updatedStatusAt), 'seconds') > process.env.MQTT_INTERVAL_CONTROL) && device.online) {
          client.publish(`dev/${device.name}`, '0')
        }
      } else {
        device.updatedStatusAt = new Date()
      }
    })
  } catch (e) {
    console.log(e)
  }
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
    if (m && r) {
      let params = await models.MagnitudeRoom.findOne({
        where: {
          magnitude_id: m.id,
          room_id: r.id
        }
      })
      if (params) {
        if (magnitude == process.env.MQTT_CONTROL_PARAM) {
          if (value < params.min_limit) {
            client.publish(`led/${r.name}`, 'b')
          } else if (value < params.max_limit) {
            client.publish(`led/${r.name}`, 'g')
          } else {
            client.publish(`led/${r.name}`, 'r')
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
            let start = moment(lastMeasurement.createdAt)
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
    }
  } catch (e) {
    console.error(e)
  }
}

let subscribe = (topic) => {
  client.subscribe(topic, e => {
    if (e) {
      console.error(e)
    } else {
      console.log(`Subscribed to ${topic}`)
    }
  })
}

async function main() {
  try {
    devices = await models.Device.findAll()
    devices.forEach(async function(device) {
      await device.update({
        online: false
      })
    })
    let magnitudes = await models.Magnitude.findAll()
    magnitudes.forEach(magnitude => {
      let topic = `${magnitude.name}/+`
      subscribe(topic)
    })
    let topic = `dev/+`
    subscribe(topic)
    setInterval(() => {
      verifyState()
    }, 2 * process.env.MQTT_INTERVAL_CONTROL * 1000)
  } catch (e) {
    client.end
    console.error(e)
  }
}

main()