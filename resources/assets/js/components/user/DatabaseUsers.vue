<template>
  <v-data-table
    :headers="headers"
    :items="users"
    :loading="loading"
    :pagination.sync="pagination"
    :total-items="totalUsers"
    :rows-per-page-items="[10,20,30]"
  >
    <template v-slot:items="props">
      <td class="text-xs-center">{{ props.item.name }}</td>
      <td class="text-xs-center">{{ props.item.username }}</td>
      <td class="text-xs-center">{{ props.item.charge }}</td>
      <td class="text-xs-center">{{ props.item.phone }}</td>
    </template>
  </v-data-table>
</template>

<script>
export default {
  name: 'databaseUsers',
  props: ['bus'],
  data: () => ({
    loading: true,
    search: '',
    enabled: true,
    pagination: {
      page: 1,
      rowsPerPage: 10,
      sortBy: null,
      descending: false
    },
    users: [],
    totalUsers: 0,
    headers: [
      { text: 'Nombre', value: 'name', align: 'center', sortable: true },
      { text: 'Usuario', value: 'username', align: 'center', sortable: true },
      { text: 'Cargo', value: 'charge', align: 'center', sortable: true },
      { text: 'Teléfono', value: 'phone', align: 'center', sortable: true }
    ]
  }),
  watch: {
    pagination: function(newVal, oldVal) {
      if (newVal.page != oldVal.page || newVal.rowsPerPage != oldVal.rowsPerPage || newVal.sortBy != oldVal.sortBy || newVal.descending != oldVal.descending) {
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
    this.getUsers()
  },
  methods: {
    async getUsers(params) {
      try {
        let res = await axios.get(`user`, {
          params: {
            page: this.pagination.page,
            per_page: this.pagination.rowsPerPage,
            sortBy: this.pagination.sortBy,
            direction: this.pagination.descending ? 'desc' : 'asc',
            enabled: this.enabled,
            search: this.search
          }
        })
        this.users = res.data.data
        this.totalUsers = res.data.total
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

