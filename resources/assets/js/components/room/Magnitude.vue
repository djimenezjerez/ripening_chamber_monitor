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
        <v-expansion-panel>
          <v-expansion-panel-content
            v-for="magnitude in magnitudes"
            :key="magnitude.id"
          >
            <template v-slot:header>
              <v-checkbox v-model="magnitude.checked" :label="magnitude.display_name"></v-checkbox>
            </template>
            <v-container grid-list-xs>
              <v-layout wrap>
                <v-flex xs6 pr-4>
                  <v-text-field
                    v-model="magnitude.min_limit"
                    label="Mínimo"
                    v-validate="magnitude.checked ? 'required|decimal:2|min_value:0.00|max_value:100.0' : ''"
                    data-vv-name="Mínimo"
                    :error-messages="errors.collect('Mínimo')"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6 pl-4>
                  <v-text-field
                    v-model="magnitude.max_limit"
                    label="Máximo"
                    v-validate="magnitude.checked ? 'required|decimal:2|min_value:0.00|max_value:100.0' : ''"
                    data-vv-name="Máximo"
                    :error-messages="errors.collect('Máximo')"
                  ></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-expansion-panel-content>
        </v-expansion-panel>
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
    async setRoomLimits(id) {
      try {
        let magnitude = this.magnitudes.find(o => o.id == id)
        let res = await axios.post(`room/${this.room.id}/magnitude/${id}`, {
          params: {
            min_limit: magnitude.min_limit,
            max_limit: magnitude.max_limit
          }
        })
        console.log(res.data)
      } catch (e) {
        console.log(e)
      }
    },
    async getRoomLimits(id) {
      try {
        let res = await axios.get(`room/${this.room.id}/magnitude/${id}`)
        console.log(res.data)
      } catch (e) {
        console.log(e)
      }
    },
    async getRoomMagnitudes() {
      try {
        let res = await axios.get(`room/${this.room.id}/magnitude`)
        this.room.magnitudes = res.data
        this.magnitudes.forEach(magnitude => {
          if (res.data.find(o => o.id == magnitude.id)) {
            magnitude.checked = true
            magnitude.limits = this.getRoomLimits(magnitude.id)
          } else {
            magnitude.checked = false
            magnitude.min_limit = null
            magnitude.max_limit = null
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