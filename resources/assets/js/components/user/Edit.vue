<template>
  <v-dialog
    v-model="show"
    width="600"
  >
    <v-card>
      <v-toolbar dense flat dark class="info">
        <v-toolbar-title>{{ title }}</v-toolbar-title>
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
              <v-flex xs6 pr-4>
                <v-text-field
                  v-model="user.password"
                  type="password"
                  ref="password"
                  label="Contraseña"
                  v-validate="passwordRules"
                  data-vv-name="Contraseña"
                  :error-messages="errors.collect('Contraseña')"
                ></v-text-field>
              </v-flex>
              <v-flex xs6 pl-4>
                <v-text-field
                  v-model="user.passwordConfirm"
                  type="password"
                  label="Confirmar Contraseña"
                  v-validate="passwordRules"
                  data-vv-name="Confirmar Contraseña"
                  :error-messages="errors.collect('Confirmar Contraseña')"
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
    user: {},
    title: ''
  }),
  computed: {
    passwordRules() {
      if ((this.user.password != null && this.user.password != '') || this.user.id == null) {
        return 'required|min:4'
      } else {
        return ''
      }
    }
  },
  mounted() {
    this.bus.$on('edit', val => {
      this.show = true
      if (val == null) {
        this.resetUser()
        this.title = 'Añadir usuario'
      } else {
        this.user = val
        this.user.passwordConfirm = null
        this.title = 'Editar usuario'
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
        let valid = await this.$validator.validateAll()
        if (valid) {
          if ((this.user.password != this.user.passwordConfirm) && this.user.id == null) {
            this.toastr.error('Las contraseñas no coinciden')
            this.user.password = ''
            this.user.passwordConfirm = ''
            this.$refs['password'].focus()
          } else {
            if (this.user.id != null) {
              await axios.patch(`user/${this.user.id}`, this.user)
            } else {
              await axios.post(`user`, this.user)
            }
            this.toastr.success('Usuario registrado')
            this.close()
          }
        }
      } catch(e) {
        console.log(e)
        this.toastr.error('Error al guardar los datos de usuario')
      }
    },
    resetUser() {
      this.user = {
        id: null,
        name: null,
        username: null,
        charge: null,
        phone: null,
        password: null,
        passwordConfirm: null,
        enabled: true
      }
    }
  }
}
</script>

