<template>
  <v-dialog
    v-model="show"
    width="600"
  >
    <v-card>
      <v-toolbar flat dense color="tertiary">
        <v-toolbar-title>{{ title }} ambiente</v-toolbar-title>
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
                  v-if="$store.getters.roles.includes('admin')"
                  v-model="room.name"
                  label="Código"
                  v-validate="'required|min:4|max:4'"
                  data-vv-name="Código"
                  :error-messages="errors.collect('Código')"
                ></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  v-model="room.display_name"
                  label="Nombre"
                  v-validate="'required|min:4'"
                  data-vv-name="Nombre"
                  :error-messages="errors.collect('Nombre')"
                ></v-text-field>
              </v-flex>
              <v-select
                :items="devices"
                label="Dispositivo"
                prepend-icon="mdi-cellphone-wireless"
                item-text="display_name"
                item-value="id"
                v-model="room.device_id"
                :hint="room.device_id ? devices.find(o => o.id == room.device_id).ip : ''"
                :persistent-hint="true"
                v-validate="'required'"
                data-vv-name="Dispositivo"
                :error-messages="errors.collect('Dispositivo')"
              ></v-select>
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
  props: ['bus', 'devices'],
  data: () => ({
    show: false,
    valid: false,
    room: {},
    title: ''
  }),
  mounted() {
    this.bus.$on('edit', val => {
      this.show = true
      if (val == null) {
        this.resetRoom()
        this.title = 'Añadir'
      } else {
        this.room = val
        this.title = 'Editar'
      }
    })
  },
  methods: {
    close() {
      this.$validator.reset()
      this.show = false
      this.resetRoom()
      this.bus.$emit('refresh')
    },
    async save() {
      try {
        if (await this.$validator.validateAll()) {
          if (this.room.id != null) {
            await axios.patch(`room/${this.room.id}`, this.room)
          } else {
            await axios.post(`room`, this.room)
          }
          this.toast('Ambiente registrado', 'success')
          this.close()
        }
      } catch (e) {
        console.log(e)
      }
    },
    resetRoom() {
      this.room = {
        id: null,
        name: null,
        display_name: null,
        device_id: null
      }
    }
  }
}
</script>