<template>
  <v-dialog
    v-model="show"
    width="400"
  >
    <v-card>
      <v-toolbar flat dense color="tertiary">
        <v-toolbar-title>Roles para el usuario {{ user.name }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click.stop="close()">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-title></v-card-title>
      <v-card-text>
        <v-radio-group v-model="userRole">
          <v-radio
            v-for="role in roles"
            :key="role.id"
            :label="role.display_name"
            :value="role.id"
            @change="user.roles = [role.id]"
          ></v-radio>
        </v-radio-group>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click="save()">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue';

export default {
  name: 'Edit',
  props: ['bus'],
  data: () => ({
    show: false,
    valid: false,
    roles: [],
    user: {
      roles: []
    },
    userRole: null
  }),
  watch: {
    user: function(val) {
      if (val.hasOwnProperty('id')) this.getUserRoles()
    }
  },
  beforeMount() {
    this.getRoles()
  },
  mounted() {
    this.bus.$on('role', val => {
      this.show = true
      this.user = val
      this.getUserRoles()
    })
  },
  methods: {
    close() {
      this.user = {
        roles: []
      }
      this.userRole = null
      this.show = false
    },
    async save() {
      try {
        await axios.post(`user/${this.user.id}/role`, {
          roles: this.user.roles
        })
        this.toast('Actualizado correctamente', 'success')
        this.close()
      } catch (e) {
        console.log(e)
      }
    },
    async getRoles() {
      try {
        let res = await axios.get(`role`)
        this.roles = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async getUserRoles() {
      try {
        let res = await axios.get(`user/${this.user.id}/role`)
        this.user.roles = res.data
        if (res.data.length > 0) this.userRole = res.data[0].id
        this.$forceUpdate()
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>