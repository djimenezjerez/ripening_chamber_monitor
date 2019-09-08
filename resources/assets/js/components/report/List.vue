<template>
  <v-data-table
    :headers="headers"
    :items="measurements"
    :loading="loading"
    :options.sync="options"
    :server-items-length="totalItems"
    :footer-props="{ itemsPerPageOptions: [10, 20, 30] }"
  >
    <template v-slot:item="props">
      <tr>
        <td class="text-center">{{ $moment(props.item.created_at).format('LL') }}</td>
        <td class="text-center">{{ $moment(props.item.created_at).format('HH:mm') }}</td>
        <td class="text-center">{{ props.item.value }}</td>
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
    data: {
      room: null,
      magnitude: null,
      from: null,
      to: null
    },
    measurements: [],
    options: {
      page: 1,
      rowsPerPage: 10,
      sortBy: ['created_at'],
      sortDesc: [true]
    },
    totalItems: 0,
    headers: [
      { text: 'Fecha', value: 'created_at', align: 'center', sortable: true, width: '50%', class: 'grey lighten-2' },
      { text: 'Hora', value: 'created_at', align: 'center', sortable: false, width: '25%', class: 'grey lighten-2' },
      { text: 'Valor', value: 'value', align: 'center', sortable: true, width: '25%', class: 'grey lighten-2' }
    ]
  }),
  watch: {
    options: function(newVal, oldVal) {
      if (newVal.page != oldVal.page || newVal.rowsPerPage != oldVal.rowsPerPage || newVal.sortBy != oldVal.sortBy || newVal.sortDesc != oldVal.sortDesc) {
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
              page: this.options.page,
              per_page: this.options.rowsPerPage,
              sortBy: this.options.sortBy,
              sortDesc: this.options.sortDesc
            }
          })
          this.measurements = res.data.data
          this.totalItems = res.data.total
          if (res.data.total > 0) {
            this.bus.$emit('enableExport', true)
          } else {
            this.bus.$emit('enableExport', false)
          }
          delete res.data['data']
          this.options.page = res.data.current_page
          this.options.rowsPerPage = parseInt(res.data.per_page)
          this.options.totalItems = res.data.total
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
