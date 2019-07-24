<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Usuarios</v-toolbar-title>
      <v-spacer></v-spacer>
      <template v-if="viewType == 'Usuarios'">
        <v-btn  @click="enabled = true" :class="this.enabled ? 'primary' : 'normal'" class="mr-0">
          <div class="font-weight-regular subheading pa-2 white--text">ACTIVOS</div>
        </v-btn>
        <v-btn  @click="enabled = false" :class="!this.enabled ? 'primary' : 'normal'" class="mr-3">
          <div class="font-weight-regular subheading pa-2 white--text">INACTIVOS</div>
        </v-btn>
      </template>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
      <v-flex xs2>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          hide-details
          full-width
          clearable
        ></v-text-field>
      </v-flex>
    </v-toolbar>
    <v-card>
      <v-card-text>
        <DatabaseUsers :bus="bus"/>
      </v-card-text>
    </v-card>
  </v-container>
</template>
<script>
import _ from 'lodash'
import Vue from 'vue'
import DatabaseUsers from './DatabaseUsers'

export default {
  name: "userIndex",
  components: {
    DatabaseUsers
  },
  data: () => ({
    viewType: 'Usuarios',
    search: '',
    bus: new Vue(),
    enabled: 'active'
  }),
  watch: {
    search: _.debounce(function () {
      this.bus.$emit('search', this.search)
    }, 1000),
    enabled: function() {
      this.bus.$emit('enabled', this.enabled)
    }
  }
}
</script>
