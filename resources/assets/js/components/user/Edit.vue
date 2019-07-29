<template>
  <v-dialog
    v-model="show"
    width="500"
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
          <v-text-field
            v-model="user.name"
            label="Nombre"
            v-validate="'required|min:4'"
            data-vv-name="Nombre"
            :error-messages="errors.collect('Nombre')"
          ></v-text-field>
          <v-text-field
            v-model="user.charge"
            label="Cargo"
            v-validate="'alpha_spaces|min:1'"
            data-vv-name="Cargo"
            :error-messages="errors.collect('Cargo')"
          ></v-text-field>
          <v-text-field
            v-model="user.phone"
            label="Teléfono"
            v-validate="'numeric|min:7|max:8'"
            data-vv-name="Teléfono"
            :error-messages="errors.collect('Teléfono')"
          ></v-text-field>
          <v-text-field
            v-model="user.username"
            label="Usuario"
            v-validate="'required|alpha_num|min:4'"
            data-vv-name="Usuario"
            :error-messages="errors.collect('Usuario')"
          ></v-text-field>
          <v-text-field
            v-model="user.password"
            type="password"
            ref="password"
            label="Contraseña"
            v-validate="passwordRules"
            data-vv-name="Contraseña"
            :error-messages="errors.collect('Contraseña')"
          ></v-text-field>
          <v-text-field
            v-model="user.passwordConfirm"
            type="password"
            label="Confirmar Contraseña"
            v-validate="passwordRules"
            data-vv-name="Confirmar Contraseña"
            :error-messages="errors.collect('Confirmar Contraseña')"
          ></v-text-field>
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
    newUser: {
      id: null,
      name: null,
      username: null,
      charge: null,
      phone: null,
      password: null,
      passwordConfirm: null
    },
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
        this.user = this.newUser
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
      this.show = false
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
            console.log(this.user)
            this.close()
          }
        }
      } catch(e) {
        console.log(e)
      }
    }
  }
}
</script>

