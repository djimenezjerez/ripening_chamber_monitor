<template>
  <v-data-table
    :headers="headers"
    :items="magnitudes"
    :loading="loading"
    :options.sync="options"
    :server-items-length="totalItems"
    :footer-props="{ itemsPerPageOptions: [10, 20, 30] }"
  >
    <template v-slot:item="props">
      <tr>
        <td class="text-center">{{ props.item.name }}</td>
        <td class="text-center">{{ props.item.display_name }}</td>
        <td class="text-center">{{ props.item.measure }}</td>
        <td class="text-center">
          <v-btn icon text @click="bus.$emit('edit', props.item)" v-if="$store.getters.permissions.includes('update-magnitude')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="info" v-on="on">mdi-pencil</v-icon>
              </template>
              <span>Editar</span>
            </v-tooltip>
          </v-btn>
          <v-btn icon text @click="bus.$emit('delete', `magnitude/${props.item.id}`)" v-if="$store.getters.permissions.includes('delete-magnitude')">
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
    magnitudes: [],
    totalItems: 0,
    headers: [
      { text: 'Código', value: 'name', align: 'center', sortable: true, class: 'grey lighten-2' },
      { text: 'Nombre', value: 'display_name', align: 'center', sortable: true, class: 'grey lighten-2' },
      { text: 'Unidad', value: 'measure', align: 'center', sortable: true, class: 'grey lighten-2' },
      { text: 'Acciones', value: 'id', align: 'center', sortable: false, class: 'grey lighten-2' }
    ]
  }),
  watch: {
    options: function(newVal, oldVal) {
      if (newVal.page != oldVal.page || newVal.rowsPerPage != oldVal.rowsPerPage || newVal.sortBy != oldVal.sortBy || newVal.sortDesc != oldVal.sortDesc) {
        this.getMagnitudes()
      }
    },
    search: function(newVal, oldVal) {
      if (newVal != oldVal) {
        this.getMagnitudes()
      }
    }
  },
  mounted() {
    if (!this.$store.getters.permissions.some(o => ['update-magnitude', 'delete-magnitude'].includes(o))) {
      this.headers.pop()
    }
    this.bus.$on('refresh', () => {
      this.getMagnitudes()
    })
    this.bus.$on('search', val => {
      this.search = val
    })
    this.bus.$on('deleted', val => {
      this.magnitudes = this.magnitudes.filter(o => o.id != val)
    })
    this.getMagnitudes()
  },
  methods: {
    async getMagnitudes(params) {
      try {
        let res = await axios.get(`magnitude`, {
          params: {
            page: this.options.page,
            per_page: this.options.rowsPerPage,
            sortBy: this.options.sortBy,
            sortDesc: this.options.sortDesc,
            search: this.search
          }
        })
        this.magnitudes = res.data.data
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