import Vue from 'vue'
import VueJWT from 'vuejs-jwt'

Vue.use(VueJWT, {
  storage: 'localStorage',
  keyName: 'access_token'
})