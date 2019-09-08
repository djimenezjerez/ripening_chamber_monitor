<template>
  <v-container fluid>
    <v-toolbar dense color="tertiary">
      <v-toolbar-title>Dispositivos</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
      <v-flex xs2>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Buscar"
          class="mr-5"
          single-line
          hide-details
          clearable
        ></v-text-field>
      </v-flex>
      <v-btn fab x-small color="success" @click.native="bus.$emit('edit', null)" v-if="$store.getters.permissions.includes('create-device')">
        <v-tooltip top>
          <template v-slot:activator="{ on }">
            <v-icon v-on="on">mdi-plus</v-icon>
          </template>
          <span>Nuevo dispositivo</span>
        </v-tooltip>
      </v-btn>
    </v-toolbar>
    <v-card>
      <List :bus="bus"/>
    </v-card>
    <Edit :bus="bus"/>
    <RemoveItem :bus="bus"/>
  </v-container>
</template>

<script>
import _ from 'lodash'
import Vue from 'vue'
import List from '@/components/device/List'
import Edit from '@/components/device/Edit'
import RemoveItem from '@/components/shared/RemoveItem'

export default {
  name: "deviceIndex",
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