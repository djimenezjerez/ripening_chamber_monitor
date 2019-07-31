<template>
  <v-data-table
    :headers="headers"
    :items="roles"
    :loading="loading"
    :rows-per-page-items="[10,20,30]"
    disable-initial-sort
  >
    <template v-slot:items="props">
      <td class="text-xs-center">{{ props.item.display_name }}</td>
      <td class="text-xs-center">
        <v-btn icon text @click="bus.$emit('edit', props.item)" v-if="$store.getters.permissions.includes('update-role')">
          <v-tooltip top>
            <v-icon color="info" slot="activator">edit</v-icon>
            <span>Editar</span>
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
    roles: [],
    headers: [
      { text: 'Rol', value: 'display_name', align: 'center', sortable: true },
      { text: 'Acciones', value: 'id', align: 'center', sortable: false }
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
