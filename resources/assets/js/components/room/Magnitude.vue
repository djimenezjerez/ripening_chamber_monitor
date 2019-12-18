<template>
  <v-dialog
    v-model="show"
    width="500"
  >
    <v-card>
      <v-toolbar flat dense color="tertiary">
        <v-toolbar-title>Magnitudes para el ambiente {{ room.display_name }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click.stop="close()">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-expansion-panels accordion>
        <v-expansion-panel v-for="magnitude in magnitudes" :key="magnitude.id" active-class="grey lighten-4">
          <v-expansion-panel-header>
            <v-layout wrap class="mt-0 pt-0">
              <v-flex xs6 pr-4 class="mt-0 pt-0">
                <v-checkbox
                  v-model="magnitude.checked"
                  :label="magnitude.display_name"
                  @change="resetLimits(magnitude)"
                ></v-checkbox>
              </v-flex>
              <v-flex xs6 pl-4 class="mt-0 pt-0">
                <v-text-field
                  v-model="magnitude.pivot.interval"
                  label="Intervalo"
                  v-validate="intervalRules(magnitude)"
                  :data-vv-name="`Intervalo ${magnitude.id}`"
                  :error-messages="errors.collect(`Intervalo ${magnitude.id}`)"
                  class="mt-0 pt-0"
                  hint="Minutos"
                  persistent-hint=""
                ></v-text-field>
              </v-flex>
            </v-layout>
            <template v-slot:actions>
              <v-btn class="ml-5" icon>
                <v-icon color="primary">$vuetify.icons.expand</v-icon>
              </v-btn>
            </template>
          </v-expansion-panel-header>
          <v-expansion-panel-content eager>
            <v-form v-model="valid" lazy-validation>
              <v-container grid-list-xs class="mt-0 pt-0">
                <v-layout wrap class="mt-0 pt-0">
                  <v-flex xs5 px-4 class="mt-0 pt-0">
                    <v-text-field
                      v-model="magnitude.pivot.min_limit"
                      label="Mínimo"
                      v-validate="limitRules(magnitude)"
                      data-vv-name="Mínimo"
                      :error-messages="errors.collect('Mínimo')"
                      class="mt-0 pt-0"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs1>
                    {{ magnitude.measure }}
                  </v-flex>
                  <v-flex xs5 px-4 class="mt-0 pt-0">
                    <v-text-field
                      v-model="magnitude.pivot.max_limit"
                      label="Máximo"
                      v-validate="limitRules(magnitude)"
                      data-vv-name="Máximo"
                      :error-messages="errors.collect('Máximo')"
                      class="mt-0 pt-0"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs1>
                    {{ magnitude.measure }}
                  </v-flex>
                </v-layout>
              </v-container>
            </v-form>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
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
  async mounted() {
    await this.$validator.validateAll()
    this.getMagnitudes()
    this.bus.$on('magnitude', val => {
      this.show = true
      this.room = val
    })
  },
  methods: {
    resetLimits(magnitude) {
      if (!magnitude.checked) {
        magnitude.min_limit = 0
        magnitude.max_limit = 0
      }
    },
    limitRules(magnitude) {
      if (magnitude.checked) {
        return 'required|decimal:2|min_value:0.00|max_value:100.0'
      } else {
        return ''
      }
    },
    intervalRules(magnitude) {
      if (magnitude.checked) {
        return 'required|integer|min_value:0|max_value:240'
      } else {
        return ''
      }
    },
    close() {
      this.room = {
        magnitudes: []
      }
      this.show = false
    },
    filterMagnitudes() {
      return new Promise((resolve, reject) => {
        let magnitudes = this.magnitudes.filter(o => o.checked)
        if (magnitudes.length > 0) {
          let checkedMagnitudes = {}
          magnitudes.forEach(magnitude => {
            checkedMagnitudes[magnitude.id] = {
              min_limit: magnitude.pivot.min_limit,
              max_limit: magnitude.pivot.max_limit,
              interval: magnitude.pivot.interval
            }
          })
          return resolve(checkedMagnitudes)
        } else {
          return resolve([])
        }
      })
    },
    async save() {
      try {
        if (await this.$validator.validateAll()) {
          let checkedMagnitudes = await this.filterMagnitudes()
          await axios.post(`room/${this.room.id}/magnitude`, {
            magnitudes: checkedMagnitudes
          })
          this.toast('Actualizado correctamente', 'success')
          this.close()
        }
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
            sortBy: 'id',
            direction: 'asc',
            search: null
          }
        })
        this.magnitudes = res.data.data
        this.magnitudes.forEach(magnitude => {
          magnitude.pivot = {
            min_limit: null,
            max_limit: null,
            interval: null
          }
        })
      } catch (e) {
        console.log(e)
      }
    },
    async getRoomMagnitudes() {
      try {
        let res = await axios.get(`room/${this.room.id}/magnitude`)
        this.room.magnitudes = res.data
        this.magnitudes.forEach(magnitude => {
          let data = res.data.find(o => o.id == magnitude.id)
          if (data) {
            magnitude.checked = true
            magnitude.pivot = {
              min_limit: data.pivot.min_limit,
              max_limit: data.pivot.max_limit,
              interval: data.pivot.interval
            }
          } else {
            magnitude.checked = false
            magnitude.pivot = {
              min_limit: null,
              max_limit: null,
              interval: null
            }
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