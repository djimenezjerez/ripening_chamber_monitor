<template>
  <v-dialog
    v-model="show"
    width="400"
  >
    <v-card>
      <v-toolbar dense flat dark class="info">
        <v-toolbar-title>Roles para el usuario {{ user.name }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click.native="close()">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <template v-for="role in roles">
          <v-checkbox :key="role.id" v-model="role.checked" :label="role.display_name"></v-checkbox>
        </template>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="success"
          class="mr-6"
          @click.native="close()"
        >Cerrar</v-btn>
        <v-btn
          color="error"
          class="mr-6"
          @click.native="save()"
        >Guardar</v-btn>
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
    }
  }),
  watch: {
    user: function(val) {
      if (val.hasOwnProperty('id')) this.getUserRoles()
    }
  },
  mounted() {
    this.getRoles()
    this.bus.$on('role', val => {
      this.show = true
      this.user = val
    })
  },
  methods: {
    close() {
      this.user = {
        roles: []
      }
      this.show = false
    },
    async save() {
      try {
        await axios.post(`user/${this.user.id}/role`, {
          roles: this.roles.filter(o => o.checked).map(o => o.id)
        })
        this.toastr.success('Actualizado correctamente')
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
        this.roles.forEach(role => {
          if (res.data.find(o => o.id == role.id)) {
            role.checked = true
          } else {
            role.checked = false
          }
        })
        this.$forceUpdate();
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>