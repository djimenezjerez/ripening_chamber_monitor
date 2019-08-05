<template>
  <v-data-table
    :headers="headers"
    :items="measurements"
    :loading="loading"
    :rows-per-page-items="[10,20,30]"
    disable-initial-sort
  >
    <template v-slot:items="props">
      <td class="text-xs-center">{{ $moment(props.item.created_at).format('LL') }}</td>
      <td class="text-xs-center">{{ $moment(props.item.created_at).format('HH:mm') }}</td>
      <td class="text-xs-center">{{ props.item.value }}</td>
    </template>
  </v-data-table>
</template>

<script>
export default {
  name: 'List',
  props: ['bus'],
  data: () => ({
    loading: true,
    data: {
      room: null,
      magnitude: null,
      from: null,
      to: null
    },
    measurements: [],
    pagination: {
      page: 1,
      rowsPerPage: 10,
      sortBy: null,
      descending: false
    },
    totalMeasurements: 0,
    headers: [
      { text: 'Fecha', value: 'created_at', align: 'center', sortable: true },
      { text: 'Hora', value: 'created_at', align: 'center', sortable: false },
      { text: 'Medición', value: 'value', align: 'center', sortable: true }
    ]
  }),
  watch: {
    pagination: function(newVal, oldVal) {
      if (newVal.page != oldVal.page || newVal.rowsPerPage != oldVal.rowsPerPage || newVal.sortBy != oldVal.sortBy || newVal.descending != oldVal.descending) {
        this.getMeasurements()
      }
    }
  },
  mounted() {
    this.getMeasurements()
    this.bus.$on('search', val => {
      this.data = val
      this.getMeasurements()
    })
  },
  methods: {
    async getMeasurements() {
      try {
        if (this.data.room != null && this.data.magnitude != null && this.data.from != null && this.data.to != null) {
          this.loading = true
          let res = await axios.get(`measurement`, {
            params: {
              room: this.data.room,
              magnitude: this.data.magnitude,
              from: this.data.from,
              to: this.data.to,
              page: this.pagination.page,
              per_page: this.pagination.rowsPerPage,
              sortBy: this.pagination.sortBy,
              direction: this.pagination.descending ? 'desc' : 'asc'
            }
          })
          this.measurements = res.data.data
          this.totalMeasurements = res.data.total
          delete res.data['data']
          this.pagination.page = res.data.current_page
          this.pagination.rowsPerPage = parseInt(res.data.per_page)
          this.pagination.totalItems = res.data.total
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
