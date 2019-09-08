<template>
  <v-data-table
    :headers="headers"
    :items="users"
    :loading="loading"
    :options.sync="options"
    :server-items-length="totalItems"
    :footer-props="{ itemsPerPageOptions: [10, 20, 30] }"
  >
    <template v-slot:item="props">
      <tr>

        <td class="text-center">{{ props.item.name }}</td>
        <td class="text-center">{{ props.item.username }}</td>
        <td class="text-center">{{ props.item.charge }}</td>
        <td class="text-center">{{ props.item.phone }}</td>
        <td class="text-center">
          <v-btn icon text @click.native="resetPassword(props.item)" v-if="enabled && $store.getters.permissions.includes('update-user')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="error" v-on="on">mdi-key</v-icon>
              </template>
              <span>Reiniciar contraseña</span>
            </v-tooltip>
          </v-btn>
          <v-btn icon text @click="bus.$emit('edit', props.item)" v-if="$store.getters.permissions.includes('update-user')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="info" v-on="on">mdi-pencil</v-icon>
              </template>
              <span>Editar</span>
            </v-tooltip>
          </v-btn>
          <v-btn icon text @click.native="bus.$emit('role', props.item)" v-if="enabled && $store.getters.permissions.includes('update-user')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="danger" v-on="on">mdi-security</v-icon>
              </template>
              <span>Roles</span>
            </v-tooltip>
          </v-btn>
        </td>
      </tr>
    </template>
  </v-data-table>
</template>

<script>
export default {
  name: 'List',
  props: ['bus'],
  data: () => ({
    loading: true,
    search: '',
    enabled: true,
    options: {
      page: 1,
      rowsPerPage: 10,
      sortBy: ['name'],
      sortDesc: [false]
    },
    users: [],
    totalItems: 0,
    headers: [
      { text: 'Nombre', value: 'name', align: 'center', sortable: true, width: '35%', class: 'grey lighten-2' },
      { text: 'Usuario', value: 'username', align: 'center', sortable: true, width: '10%', class: 'grey lighten-2' },
      { text: 'Cargo', value: 'charge', align: 'center', sortable: true, width: '25%', class: 'grey lighten-2' },
      { text: 'Teléfono', value: 'phone', align: 'center', sortable: true, width: '15%', class: 'grey lighten-2' },
      { text: 'Acciones', value: 'id', align: 'center', sortable: false, width: '15%', class: 'grey lighten-2' }
    ]
  }),
  watch: {
    options: function(newVal, oldVal) {
      if (newVal.page != oldVal.page || newVal.rowsPerPage != oldVal.rowsPerPage || newVal.sortBy != oldVal.sortBy || newVal.sortDesc != oldVal.sortDesc) {
        this.getUsers()
      }
    },
    search: function(newVal, oldVal) {
      if (newVal != oldVal) {
        this.getUsers()
      }
    },
    enabled: function(newVal, oldVal) {
      if (newVal != oldVal) {
        this.getUsers()
      }
    }
  },
  mounted() {
    this.bus.$on('search', val => {
      this.search = val
    })
    this.bus.$on('enabled', val => {
      this.enabled = val
    })
    this.bus.$on('refresh', () => {
      this.getUsers()
    })
    this.getUsers()
  },
  methods: {
    async getUsers(params) {
      try {
        let res = await axios.get(`user`, {
          params: {
            page: this.options.page,
            per_page: this.options.itemsPerPage,
            sortBy: this.options.sortBy,
            sortDesc: this.options.sortDesc,
            enabled: this.enabled,
            search: this.search
          }
        })
        this.users = res.data.data
        this.totalItems = res.data.total
        delete res.data['data']
        this.options.page = res.data.current_page
        this.options.rowsPerPage = parseInt(res.data.per_page)
        this.options.totalItems = res.data.total
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async resetPassword(item) {
      try {
        await axios.patch(`user/${item.id}`, {
          newPassword: item.username
        })
        this.toast('Contraseña reiniciada', 'success')
      } catch(e) {
        console.log(e)
      }
    }
  }
}
</script>

