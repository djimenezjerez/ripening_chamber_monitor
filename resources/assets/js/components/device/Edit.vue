<template>
  <v-dialog
    v-model="show"
    width="600"
  >
    <v-card>
      <v-toolbar dense flat dark class="info">
        <v-toolbar-title>{{ title }} dispositivo</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click.native="close()">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-form v-model="valid">
          <v-container grid-list-xs>
            <v-layout wrap>
              <v-flex xs12>
                <v-text-field
                  v-model="device.name"
                  label="Código"
                  v-validate="'required|min:3'"
                  data-vv-name="Código"
                  :error-messages="errors.collect('Código')"
                ></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  v-model="device.display_name"
                  label="Nombre"
                  v-validate="'required|min:4'"
                  data-vv-name="Nombre"
                  :error-messages="errors.collect('Nombre')"
                ></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  v-model="device.mac"
                  label="MAC"
                  v-validate="'required|min:17|max:17'"
                  data-vv-name="MAC"
                  :error-messages="errors.collect('MAC')"
                ></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  v-model="device.ip"
                  label="IP"
                  v-validate="'required|ip'"
                  data-vv-name="IP"
                  :error-messages="errors.collect('IP')"
                ></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="success"
          class="mr-6"
          @click.native="close()"
        >Cerrar</v-btn>
        <v-btn
          :disabled="!valid"
          color="error"
          class="mr-6"
          @click.native="save()"
        >Guardar</v-btn>
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
    device: {},
    title: ''
  }),
  mounted() {
    this.bus.$on('edit', val => {
      this.show = true
      if (val == null) {
        this.resetDevice()
        this.title = 'Añadir'
      } else {
        this.device = val
        this.title = 'Editar'
      }
    })
  },
  methods: {
    close() {
      this.$validator.reset()
      this.show = false
      this.resetDevice()
      this.bus.$emit('refresh')
    },
    async save() {
      try {
        if (await this.$validator.validateAll()) {
          if (this.device.id != null) {
            await axios.patch(`device/${this.device.id}`, this.device)
          } else {
            await axios.post(`device`, this.device)
          }
          this.toastr.success('Dispositivo registrado')
          this.close()
        }
      } catch (e) {
        console.log(e)
      }
    },
    resetDevice() {
      this.device = {
        id: null,
        name: null,
        display_name: null,
        mac: null,
        ip: null
      }
    }
  }
}
</script>