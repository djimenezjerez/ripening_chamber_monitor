#!/usr/bin/env node

require('dotenv').config({ path: './.env' })
const moment = require('moment')
let models  = require('./models')
const mqtt = require('mqtt')
const client  = mqtt.connect(`ws://${process.env.MQTT_HOST}:${process.env.MQTT_PORT}`)

client.on('connect', function () {
  console.log('Connected to mqtt server')
})

client.on('message', (topic, message) => {
  let data = topic.split('/')
  insert(data[0], data[1], Number(message.toString()))
})

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
  } catch (e) {
    console.error(e)
  }
}

async function main() {
  try {
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
  } catch (e) {
    client.end
    console.error(e)
  }
}

main()