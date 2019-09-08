import Vue from 'vue'
import moment from 'moment'
import VueMqtt from 'vue-mqtt'

Vue.use(VueMqtt, `ws://${process.env.MIX_MQTT_URL}`, {
  clientId: `web.${moment().unix()}`
})