<template>
  <v-dialog
    v-model="show"
    width="400"
  >
    <v-card>
      <v-toolbar dense flat dark class="info">
        <v-toolbar-title>Magnitudes para el ambiente {{ room.name }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click.native="close()">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <template v-for="magnitude in magnitudes">
          <v-checkbox :key="magnitude.id" v-model="magnitude.checked" :label="magnitude.display_name"></v-checkbox>
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
    magnitudes: [],
    room: {
      magnitudes: []
    }
  }),
  watch: {
    room: function(val) {
      if (val.hasOwnProperty('id')) this.getRoomMagnitudes()
    }
  },
  mounted() {
    this.getMagnitudes()
    this.bus.$on('magnitude', val => {
      this.show = true
      this.room = val
    })
  },
  methods: {
    close() {
      this.room = {
        magnitudes: []
      }
      this.show = false
    },
    async save() {
      try {
        await axios.post(`room/${this.room.id}/magnitude`, {
          magnitudes: this.magnitudes.filter(o => o.checked).map(o => o.id)
        })
        this.toastr.success('Actualizado correctamente')
        this.close()
      } catch (e) {
        console.log(e)
      }
    },
    async getMagnitudes() {
      try {
        let res = await axios.get(`magnitude`, {
          params: {
            page: 1,
            per_page: 1000,
            sortBy: 'display_name',
            direction: 'asc',
            search: null
          }
        })
        this.magnitudes = res.data.data
      } catch (e) {
        console.log(e)
      }
    },
    async getRoomMagnitudes() {
      try {
        let res = await axios.get(`room/${this.room.id}/magnitude`)
        this.room.magnitudes = res.data
        this.magnitudes.forEach(role => {
          if (res.data.find(o => o.id == role.id)) {
            role.checked = true
          } else {
            role.checked = false
          }
        })
        this.$forceUpdate()
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>