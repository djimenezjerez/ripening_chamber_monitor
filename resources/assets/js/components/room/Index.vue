<template>
  <v-container fluid>
    <v-toolbar flat dense color="tertiary">
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
          append-icon="mdi-magnify"
          label="Buscar"
          class="mr-5"
          single-line
          hide-details
          clearable
        ></v-text-field>
      </v-flex>
      <v-btn fab x-small color="success" @click.native="bus.$emit('edit', null)" v-if="$store.getters.permissions.includes('create-room') && devices.length > 0">
        <v-tooltip top>
          <template v-slot:activator="{ on }">
            <v-icon v-on="on">mdi-plus</v-icon>
          </template>
          <span>Nuevo ambiente</span>
        </v-tooltip>
      </v-btn>
    </v-toolbar>
    <v-card>
      <List :bus="bus" :devices="devices"/>
    </v-card>
    <Edit :bus="bus" :devices="devices"/>
    <Magnitude :bus="bus"/>
    <RemoveItem :bus="bus"/>
  </v-container>
</template>

<script>
import _ from 'lodash'
import Vue from 'vue'
import List from '@/components/room/List'
import Edit from '@/components/room/Edit'
import Magnitude from '@/components/room/Magnitude'
import RemoveItem from '@/components/shared/RemoveItem'

export default {
  name: "roomIndex",
  components: {
    RemoveItem,
    Magnitude,
    List,
    Edit
  },
  data: () => ({
    bus: new Vue(),
    search: '',
    devices: []
  }),
  watch: {
    search: _.debounce(function () {
      this.bus.$emit('search', this.search)
    }, 1000)
  },
  beforeMount() {
    this.getDevices()
  },
  methods: {
    async getDevices() {
      try {
        let res = await axios.get(`device`, {
          params: {
            page: 1,
            per_page: 1000,
            sortBy: 'display_name',
            direction: 'asc',
            search: null
          }
        })
        this.devices = res.data.data
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>