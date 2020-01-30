import moment from 'moment'
import Vue from 'vue'

export default {
  state: {
    id: localStorage.getItem('id') || null,
    user: localStorage.getItem('user') || null,
    roles: localStorage.getItem('roles') || [],
    permissions: localStorage.getItem('permissions') || [],
    dateNow: moment().format('Y-MM-DD'),
    tokenType: localStorage.getItem('token_type') || null,
    accessToken: localStorage.getItem('token') || null
  },
  getters: {
    id(state) {
      return JSON.parse(state.id)
    },
    user(state) {
      return JSON.parse(state.user)
    },
    roles(state) {
      return JSON.parse(state.roles)
    },
    permissions(state) {
      return JSON.parse(state.permissions)
    },
    dateNow(state) {
      return state.dateNow
    },
    tokenType(state) {
      return state.tokenType
    },
    accessToken(state) {
      return state.accessToken
    },
    tokenExpired(state) {
      let token = Vue.$jwt.decode()
      if (token) {
        if (!state.roles.includes('monitor')) {
          return moment().isAfter(moment.unix(token.exp))
        } else {
          return false
        }
      }
      return false
    }
  },
  mutations: {
    'logout': function (state) {
      localStorage.removeItem('user')
      localStorage.removeItem('access_token')
      localStorage.removeItem('token_type')
      localStorage.removeItem('roles')
      localStorage.removeItem('id')
      localStorage.removeItem('permissions')
      state.id = null
      state.user = null
      state.roles = []
      state.permissions = []
    },
    'login': function (state, data) {
      let token = Vue.$jwt.decode()
      localStorage.setItem("access_token", data.access_token)
      localStorage.setItem("token_type", data.token_type)
      localStorage.setItem("id", JSON.stringify(token.id))
      localStorage.setItem("user", JSON.stringify(token.name))
      localStorage.setItem("roles", JSON.stringify(token.roles))
      localStorage.setItem("permissions", JSON.stringify(token.permissions))
      state.user = localStorage.getItem('id')
      state.user = localStorage.getItem('user')
      state.roles = localStorage.getItem('roles')
      state.permissions = localStorage.getItem('permissions')
      state.tokenType = localStorage.getItem('token_type')
      state.accessToken = localStorage.getItem('access_token')
      axios.defaults.headers.common['Authorization'] = `${state.tokenType} ${state.accessToken}`
    },
    'setDate': function(state, newValue) {
      state.dateNow = newValue;
    }
  },
  actions: {
    logout(context) {
      context.commit('logout')
    },
  }
}