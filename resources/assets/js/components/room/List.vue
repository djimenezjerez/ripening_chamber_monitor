<template>
  <v-data-table
    :headers="headers"
    :items="rooms"
    :loading="loading"
    :options.sync="options"
    :server-items-length="totalItems"
    :footer-props="{ itemsPerPageOptions: [10, 20, 30] }"
  >
    <template v-slot:item="props">
      <tr>
        <td v-if="$store.getters.roles.includes('admin')" class="text-center">{{ props.item.name }}</td>
        <td class="text-center">{{ props.item.display_name }}</td>
        <td class="text-center" v-if="devices.length > 0">{{ devices.find(o => o.id == props.item.device_id).display_name }}</td>
        <td v-else></td>
        <td class="text-center">
          <v-btn icon text @click="bus.$emit('edit', props.item)" v-if="$store.getters.permissions.includes('update-room')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="info" v-on="on">mdi-pencil</v-icon>
              </template>
              <span>Editar</span>
            </v-tooltip>
          </v-btn>
          <v-btn icon text @click="bus.$emit('delete', `room/${props.item.id}`)" v-if="$store.getters.permissions.includes('delete-room')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="error" v-on="on">mdi-delete</v-icon>
              </template>
              <span>Eliminar</span>
            </v-tooltip>
          </v-btn>
          <v-btn icon text @click.native="bus.$emit('magnitude', props.item)" v-if="$store.getters.permissions.includes('update-room')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="danger" v-on="on">mdi-opacity</v-icon>
              </template>
              <span>Magnitudes</span>
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
  props: ['bus', 'devices'],
  data: () => ({
    loading: true,
    search: '',
    options: {
      page: 1,
      rowsPerPage: 10,
      sortBy: ['display_name'],
      sortDesc: [false]
    },
    rooms: [],
    totalItems: 0,
    headers: [
      { text: 'Nombre', value: 'display_name', align: 'center', sortable: true, class: 'grey lighten-2' },
      { text: 'Dispositivo', value: 'device_id', align: 'center', sortable: true, class: 'grey lighten-2' },
      { text: 'Acciones', value: 'id', align: 'center', sortable: false, class: 'grey lighten-2' }
    ]
  }),
  watch: {
    options: function(newVal, oldVal) {
      if (newVal.page != oldVal.page || newVal.rowsPerPage != oldVal.rowsPerPage || newVal.sortBy != oldVal.sortBy || newVal.sortDesc != oldVal.sortDesc) {
        this.getRooms()
      }
    },
    search: function(newVal, oldVal) {
      if (newVal != oldVal) {
        this.getRooms()
      }
    }
  },
  mounted() {
    if (this.$store.getters.roles.includes('admin')) {
      this.headers.unshift({ text: 'Código', value: 'name', align: 'center', sortable: true, class: 'grey lighten-2' })
    }
    this.bus.$on('refresh', () => {
      this.getRooms()
    })
    this.bus.$on('search', val => {
      this.search = val
    })
    this.bus.$on('deleted', val => {
      this.rooms = this.rooms.filter(o => o.id != val)
    })
    this.getRooms()
  },
  methods: {
    async getRooms(params) {
      try {
        let res = await axios.get(`room`, {
          params: {
            page: this.options.page,
            per_page: this.options.rowsPerPage,
            sortBy: this.options.sortBy,
            sortDesc: this.options.sortDesc,
            search: this.search
          }
        })
        this.rooms = res.data.data
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

