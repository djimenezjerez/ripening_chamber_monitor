import moment from 'moment';

export default {
  state: {
    id: localStorage.getItem('id') || null,
    user: localStorage.getItem('user') || null,
    roles: localStorage.getItem('roles') || null,
    permissions: localStorage.getItem('permissions') || null,
    dateNow: moment().format('Y-MM-DD'),
    token: {
      type: localStorage.getItem('token_type') || null,
      value: localStorage.getItem('token') || null
    }
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
    token(state) {
      return state.token
    },
    tokenExpired(state) {
      let token = localStorage.getItem('token')
      if (token) {
        if (!state.roles.includes('monitor')) {
          let base64 = token.split('.')[1]
          return moment().isAfter(moment.unix(JSON.parse(decodeURIComponent(atob(base64).split('').map(c => {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
          }).join(''))).exp))
        } else {
          return false
        }
      }
    }
  },
  mutations: {
    'logout': function (state) {
      localStorage.removeItem('user')
      localStorage.removeItem('token')
      localStorage.removeItem('token_type')
      localStorage.removeItem('roles')
      localStorage.removeItem('id')
      localStorage.removeItem('permissions')
      state.id = null
      state.user = null
      state.roles = null
      state.permissions = null
    },
    'login': function (state, data) {
      localStorage.setItem("token", data.token);
      localStorage.setItem("token_type", data.token_type);
      localStorage.setItem("id", JSON.stringify(data.id));
      localStorage.setItem("user", JSON.stringify(data.user));
      localStorage.setItem("roles", JSON.stringify(data.roles));
      localStorage.setItem("permissions", JSON.stringify(data.permissions));
      state.user = localStorage.getItem('id');
      state.user = localStorage.getItem('user');
      state.roles = localStorage.getItem('roles');
      state.permissions = localStorage.getItem('permissions');
      state.token = {
        type: localStorage.getItem('token_type'),
        value: localStorage.getItem('token')
      }
      axios.defaults.headers.common['Authorization'] = `${state.token.type} ${state.token.value}`
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