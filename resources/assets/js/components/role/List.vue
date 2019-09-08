<template>
  <v-data-table
    :headers="headers"
    :items="roles"
    :loading="loading"
    :footer-props="{ itemsPerPageOptions: [10, 20, 30] }"
  >
    <template v-slot:item="props">
      <tr>
        <td class="text-center">{{ props.item.display_name }}</td>
        <td class="text-center">
          <v-btn icon text @click="bus.$emit('edit', props.item)" v-if="$store.getters.permissions.includes('update-role')">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon color="info" v-on="on">mdi-pencil</v-icon>
              </template>
              <span>Editar</span>
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
    roles: [],
    headers: [
      { text: 'Rol', value: 'display_name', align: 'center', sortable: true, width: '85%', class: 'grey lighten-2' },
      { text: 'Acciones', value: 'id', align: 'center', sortable: false, width: '15%', class: 'grey lighten-2' }
    ]
  }),
  mounted() {
    this.getRoles()
  },
  methods: {
    async getRoles() {
      try {
        this.loading = true
        let res = await axios.get(`role`)
        this.roles = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
