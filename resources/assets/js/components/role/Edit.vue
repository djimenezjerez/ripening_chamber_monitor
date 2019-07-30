<template>
  <v-dialog
    v-model="show"
    width="600"
  >
    <v-card>
      <v-toolbar dense flat dark class="info">
        <v-toolbar-title>Permisos para el rol {{ role.display_name }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click.native="close()">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-select
          :items="modules"
          label="Módulo"
          prepend-icon="extension"
          item-text="name"
          item-value="id"
          v-model="selectedModule"
        ></v-select>
        <template v-for="permission in permissions">
          <v-checkbox @click.native="save(permission)" :key="permission.id" v-model="permission.checked" :label="permission.display_name"></v-checkbox>
        </template>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="success"
          class="mr-6"
          @click.native="close()"
        >Cerrar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'Edit',
  props: ['bus'],
  data: () => ({
    loading: true,
    show: false,
    modules: [],
    permissions: [],
    role: {
      display_name: '',
      permissions: []
    },
    selectedModule: null
  }),
  watch: {
    selectedModule(val) {
      if (val != null) {
        this.getModulePermissions(val)
      }
    }
  },
  mounted() {
    this.getModules()
    this.bus.$on('edit', val => {
      this.role = val
      this.role.permissions = []
      this.getRolePermissions(val)
      this.show = true
    })
  },
  methods: {
    async save(permission) {
      try {
        this.loading = true
        if (permission.checked) {
          await axios.post(`role/${this.role.id}/permission/${permission.id}`)
          this.toastr.success('Permiso agregado')
        } else {
          await axios.delete(`role/${this.role.id}/permission/${permission.id}`)
          this.toastr.warning('Permiso removido')
        }
      } catch(e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    close() {
      this.show = false
      this.permissions.forEach(permission => {
        delete permission.checked
      })
      this.$forceUpdate()
      this.role = {
        display_name: '',
        permissions: []
      }
    },
    async getModules() {
      try {
        this.loading = true
        let res = await axios.get(`module`)
        this.modules = res.data
        if (!this.selectedModule) {
          this.selectedModule = res.data[0].id
        }
      } catch(e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getRolePermissions(role) {
      try {
        this.loading = true
        let res = await axios.get(`role/${role.id}/permission`)
        this.role.permissions = res.data
        this.filterPermissions(res.data)
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getModulePermissions(id) {
      try {
        this.loading = true
        let res = await axios.get(`module/${id}/permission`)
        this.permissions = res.data
        this.filterPermissions(this.role.permissions)
      } catch(e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    filterPermissions(permissions) {
      this.permissions.forEach(permission => {
        if (permissions.find(o => o.id == permission.id)) {
          permission.checked = true
        } else {
          permission.checked = false
        }
      })
      this.$forceUpdate()
    }
  }
}
</script>