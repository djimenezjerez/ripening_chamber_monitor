<template>
  <v-data-table
    :headers="headers"
    :items="rooms"
    :loading="loading"
    :pagination.sync="pagination"
    :total-items="totalRooms"
    :rows-per-page-items="[10,20,30]"
  >
    <template v-slot:items="props">
      <td class="text-xs-center">{{ props.item.name }}</td>
      <td class="text-xs-center">{{ props.item.display_name }}</td>
      <td class="text-xs-center">{{ devices.find(o => o.id == props.item.device_id).display_name }}</td>
      <td class="text-xs-center">
        <v-btn icon text @click="bus.$emit('edit', props.item)" v-if="$store.getters.permissions.includes('update-room')">
          <v-tooltip top>
            <v-icon color="info" slot="activator">edit</v-icon>
            <span>Editar</span>
          </v-tooltip>
        </v-btn>
        <v-btn icon text @click="bus.$emit('delete', `room/${props.item.id}`)" v-if="$store.getters.permissions.includes('delete-room')">
          <v-tooltip top>
            <v-icon color="error" slot="activator">delete</v-icon>
            <span>Eliminar</span>
          </v-tooltip>
        </v-btn>
        <v-btn icon text @click.native="bus.$emit('magnitude', props.item)" v-if="$store.getters.permissions.includes('update-room')">
          <v-tooltip top>
            <v-icon color="danger" slot="activator">opacity</v-icon>
            <span>Magnitudes</span>
          </v-tooltip>
        </v-btn>
      </td>
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
    pagination: {
      page: 1,
      rowsPerPage: 10,
      sortBy: null,
      descending: false
    },
    rooms: [],
    totalRooms: 0,
    headers: [
      { text: 'Código', value: 'name', align: 'center', sortable: true },
      { text: 'Nombre', value: 'display_name', align: 'center', sortable: true },
      { text: 'Dispositivo', value: 'device_id', align: 'center', sortable: true },
      { text: 'Acciones', value: 'id', align: 'center', sortable: false }
    ]
  }),
  watch: {
    pagination: function(newVal, oldVal) {
      if (newVal.page != oldVal.page || newVal.rowsPerPage != oldVal.rowsPerPage || newVal.sortBy != oldVal.sortBy || newVal.descending != oldVal.descending) {
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
            page: this.pagination.page,
            per_page: this.pagination.rowsPerPage,
            sortBy: this.pagination.sortBy,
            direction: this.pagination.descending ? 'desc' : 'asc',
            search: this.search
          }
        })
        this.rooms = res.data.data
        this.totalRooms = res.data.total
        delete res.data['data']
        this.pagination.page = res.data.current_page
        this.pagination.rowsPerPage = parseInt(res.data.per_page)
        this.pagination.totalItems = res.data.total
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

