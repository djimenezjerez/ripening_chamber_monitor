<template>
  <v-dialog persistent v-model="dialog" max-width="31%" @keydown.esc="close">
    <v-card>
      <v-card-title>¿Seguro que desea eliminar el registro?</v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="success" small @click="close"><v-icon small>mdi-check</v-icon> Cancelar</v-btn>
        <v-btn color="error" small @click="remove"><v-icon small>mdi-close</v-icon> Eliminar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "remove-item",
  props: ["url", "bus"],
  data() {
    return {
      path: '',
      dialog: false,
    }
  },
  methods: {
    resetVariables() {
      this.path = ''
    },
    close() {
      this.resetVariables()
      this.dialog = false
      this.bus.$emit('closeDialog')
    },
    async remove() {
      try {
        let res = await axios.delete(this.path)
        this.toast('Eliminado correctamente', 'error')
        this.bus.$emit('deleted', Number(this.path.split('/').pop()))
        this.close()
      } catch (e) {
        console.log(e)
      }
    }
  },
  mounted() {
    this.bus.$on('delete', url => {
      this.path = url
      this.dialog = true
    })
  }
}
</script>