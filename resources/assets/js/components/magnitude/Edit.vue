<template>
  <v-dialog
    v-model="show"
    width="600"
  >
    <v-card>
      <v-toolbar flat dense color="tertiary">
        <v-toolbar-title>{{ title }} dispositivo</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click.native="close()">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-title></v-card-title>
      <v-card-text>
        <v-form v-model="valid">
          <v-container grid-list-xs>
            <v-layout wrap>
              <v-flex xs12>
                <v-text-field
                  v-model="magnitude.name"
                  label="Código"
                  v-validate="'required|min:3'"
                  data-vv-name="Código"
                  :error-messages="errors.collect('Código')"
                ></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  v-model="magnitude.display_name"
                  label="Nombre"
                  v-validate="'required|min:4'"
                  data-vv-name="Nombre"
                  :error-messages="errors.collect('Nombre')"
                ></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  v-model="magnitude.measure"
                  label="Unidad"
                  v-validate="'required|min:1'"
                  data-vv-name="Unidad"
                  :error-messages="errors.collect('Unidad')"
                ></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click="save()">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'Edit',
  props: ['bus'],
  data: () => ({
    show: false,
    valid: false,
    magnitude: {},
    title: ''
  }),
  mounted() {
    this.bus.$on('edit', val => {
      this.show = true
      if (val == null) {
        this.resetMagnitude()
        this.title = 'Añadir'
      } else {
        this.magnitude = val
        this.title = 'Editar'
      }
    })
  },
  methods: {
    close() {
      this.$validator.reset()
      this.show = false
      this.resetMagnitude()
      this.bus.$emit('refresh')
    },
    async save() {
      try {
        if (await this.$validator.validateAll()) {
          if (this.magnitude.id != null) {
            await axios.patch(`magnitude/${this.magnitude.id}`, this.magnitude)
          } else {
            await axios.post(`magnitude`, this.magnitude)
          }
          this.toast('Magnitud registrada', 'success')
          this.close()
        }
      } catch (e) {
        console.log(e)
      }
    },
    resetMagnitude() {
      this.magnitude = {
        id: null,
        name: null,
        display_name: null,
        measure: null
      }
    }
  }
}
</script>