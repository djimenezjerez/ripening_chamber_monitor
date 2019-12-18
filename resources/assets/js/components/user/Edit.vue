<template>
  <v-dialog
    v-model="show"
    width="600"
  >
    <v-card>
      <v-toolbar flat dense color="tertiary">
        <v-toolbar-title>{{ title }} usuario</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click.stop="close()">
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
                  v-model="user.name"
                  label="Nombre"
                  v-validate="'required|min:4'"
                  data-vv-name="Nombre"
                  :error-messages="errors.collect('Nombre')"
                ></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  v-model="user.charge"
                  label="Cargo"
                  v-validate="'alpha_spaces|min:1'"
                  data-vv-name="Cargo"
                  :error-messages="errors.collect('Cargo')"
                ></v-text-field>
              </v-flex>
              <v-flex xs6 pr-4>
                <v-text-field
                  v-model="user.username"
                  label="Usuario"
                  v-validate="'required|alpha_num|min:4'"
                  data-vv-name="Usuario"
                  :error-messages="errors.collect('Usuario')"
                ></v-text-field>
              </v-flex>
              <v-flex xs6 pl-4>
                <v-text-field
                  v-model="user.phone"
                  label="Teléfono"
                  v-validate="'numeric|min:7|max:8'"
                  data-vv-name="Teléfono"
                  :error-messages="errors.collect('Teléfono')"
                ></v-text-field>
              </v-flex>
              <v-flex xs12 v-if="user.id">
                <v-switch
                  v-model="user.enabled"
                  :label="user.enabled ? `Activo` : `Inactivo`"
                ></v-switch>
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
    user: {},
    title: ''
  }),
  mounted() {
    this.bus.$on('edit', val => {
      this.show = true
      if (val == null) {
        this.resetUser()
        this.title = 'Añadir'
      } else {
        this.user = val
        this.title = 'Editar'
      }
    })
  },
  methods: {
    close() {
      this.$validator.reset()
      this.show = false
      this.resetUser()
      this.bus.$emit('refresh')
    },
    async save() {
      try {
        if (await this.$validator.validateAll()) {
          let res
          if (this.user.id != null) {
            res = await axios.patch(`user/${this.user.id}`, this.user)
          } else {
            this.user.password = this.user.username
            res = await axios.post(`user`, this.user)
          }
          this.toast('Usuario registrado', 'success')
          this.close()
          if (this.title != 'Editar') this.bus.$emit('role', res.data)
        }
      } catch (e) {
        console.log(e)
      }
    },
    resetUser() {
      this.user = {
        id: null,
        name: null,
        username: null,
        charge: null,
        phone: null,
        enabled: true
      }
    }
  }
}
</script>