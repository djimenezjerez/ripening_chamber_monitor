<template>
  <v-data-table
    :headers="headers"
    :items="devices"
    :loading="loading"
    :options.sync="options"
    :server-items-length="totalItems"
    :footer-props="{ itemsPerPageOptions: [10, 20, 30] }"
  >
    <template v-slot:item="props">
      <tr>
        <td class="text-center">{{ props.item.name }}</td>
        <td class="text-center">{{ props.item.display_name }}</td>
        <td class="text-center">{{ props.item.mac }}</td>
        <td class="text-center">{{ props.item.ip }}</td>
        <td class="text-center">
          <v-btn icon text @click="bus.$emit('edit', props.item)" v-if="$store.getters.permissions.includes('update-device')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="info" v-on="on">mdi-pencil</v-icon>
              </template>
              <span>Editar</span>
            </v-tooltip>
          </v-btn>
          <v-btn icon text @click="bus.$emit('delete', `device/${props.item.id}`)" v-if="$store.getters.permissions.includes('delete-device')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="error" v-on="on">mdi-delete</v-icon>
              </template>
              <span>Eliminar</span>
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
    options: {
      page: 1,
      rowsPerPage: 10,
      sortBy: ['name'],
      sortDesc: [false]
    },
    devices: [],
    totalItems: 0,
    headers: [
      { text: 'Código', value: 'name', align: 'center', sortable: true, width: '20%', class: 'grey lighten-2' },
      { text: 'Nombre', value: 'display_name', align: 'center', sortable: true, width: '25%', class: 'grey lighten-2' },
      { text: 'MAC', value: 'mac', align: 'center', sortable: true, width: '20%', class: 'grey lighten-2' },
      { text: 'IP', value: 'ip', align: 'center', sortable: true, width: '20%', class: 'grey lighten-2' },
      { text: 'Acciones', value: 'id', align: 'center', sortable: false, width: '15%', class: 'grey lighten-2' }
    ]
  }),
  watch: {
    options: function(newVal, oldVal) {
      if (newVal.page != oldVal.page || newVal.rowsPerPage != oldVal.rowsPerPage || newVal.sortBy != oldVal.sortBy || newVal.sortDesc != oldVal.sortDesc) {
        this.getDevices()
      }
    },
    search: function(newVal, oldVal) {
      if (newVal != oldVal) {
        this.getDevices()
      }
    }
  },
  mounted() {
    this.bus.$on('refresh', () => {
      this.getDevices()
    })
    this.bus.$on('search', val => {
      this.search = val
    })
    this.bus.$on('deleted', val => {
      this.devices = this.devices.filter(o => o.id != val)
    })
    this.getDevices()
  },
  methods: {
    async getDevices(params) {
      try {
        let res = await axios.get(`device`, {
          params: {
            page: this.options.page,
            per_page: this.options.rowsPerPage,
            sortBy: this.options.sortBy,
            sortDesc: this.options.sortDesc,
            search: this.search
          }
        })
        this.devices = res.data.data
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
    }
  }
}
</script>

