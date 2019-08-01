<template>
  <v-data-table
    :headers="headers"
    :items="magnitudes"
    :loading="loading"
    :pagination.sync="pagination"
    :total-items="totalMagnitudes"
    :rows-per-page-items="[10,20,30]"
  >
    <template v-slot:items="props">
      <td class="text-xs-center">{{ props.item.name }}</td>
      <td class="text-xs-center">{{ props.item.display_name }}</td>
      <td class="text-xs-center">{{ props.item.measure }}</td>
      <td class="text-xs-center">
        <v-btn icon text @click="bus.$emit('edit', props.item)" v-if="$store.getters.permissions.includes('update-magnitude')">
          <v-tooltip top>
            <v-icon color="info" slot="activator">edit</v-icon>
            <span>Editar</span>
          </v-tooltip>
        </v-btn>
        <v-btn icon text @click="bus.$emit('delete', `magnitude/${props.item.id}`)" v-if="$store.getters.permissions.includes('delete-magnitude')">
          <v-tooltip top>
            <v-icon color="error" slot="activator">delete</v-icon>
            <span>Eliminar</span>
          </v-tooltip>
        </v-btn>
      </td>
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
    pagination: {
      page: 1,
      rowsPerPage: 10,
      sortBy: null,
      descending: false
    },
    magnitudes: [],
    totalMagnitudes: 0,
    headers: [
      { text: 'Código', value: 'name', align: 'center', sortable: true },
      { text: 'Nombre', value: 'display_name', align: 'center', sortable: true },
      { text: 'Unidad', value: 'measure', align: 'center', sortable: true }
    ]
  }),
  watch: {
    pagination: function(newVal, oldVal) {
      if (newVal.page != oldVal.page || newVal.rowsPerPage != oldVal.rowsPerPage || newVal.sortBy != oldVal.sortBy || newVal.descending != oldVal.descending) {
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
            page: this.pagination.page,
            per_page: this.pagination.rowsPerPage,
            sortBy: this.pagination.sortBy,
            direction: this.pagination.descending ? 'desc' : 'asc',
            search: this.search
          }
        })
        this.magnitudes = res.data.data
        this.totalMagnitudes = res.data.total
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