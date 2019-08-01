<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Ambientes</v-toolbar-title>
      <v-spacer></v-spacer>
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
      <v-btn icon small color="success" @click.native="bus.$emit('edit', null)" v-if="$store.getters.permissions.includes('create-room')">
        <v-tooltip top>
          <v-icon slot="activator">add</v-icon>
          <span>Nuevo ambiente</span>
        </v-tooltip>
      </v-btn>
    </v-toolbar>
    <v-card>
      <v-card-text>
        <List :bus="bus"/>
      </v-card-text>
    </v-card>
    <Edit :bus="bus"/>
    <RemoveItem :bus="bus"/>
  </v-container>
</template>

<script>
import _ from 'lodash'
import Vue from 'vue'
import List from './List'
import Edit from './Edit'
import RemoveItem from '../RemoveItem'

export default {
  name: "roomIndex",
  components: {
    RemoveItem,
    List,
    Edit
  },
  data: () => ({
    bus: new Vue(),
    search: ''
  }),
  watch: {
    search: _.debounce(function () {
      this.bus.$emit('search', this.search)
    }, 1000)
  }
}
</script>