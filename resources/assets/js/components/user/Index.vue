<template>
  <v-container fluid>
    <v-toolbar dense color="tertiary">
      <v-toolbar-title>Usuarios</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn-toggle
        v-model="enabled"
        active-class="primary white--text"
        mandatory
      >
        <v-btn text :value="true">ACTIVOS</v-btn>
        <v-btn text :value="false">INACTIVOS</v-btn>
      </v-btn-toggle>
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
      <v-btn fab x-small color="success" @click.native="bus.$emit('edit', null)" v-if="$store.getters.permissions.includes('create-user')">
        <v-tooltip top>
          <template v-slot:activator="{ on }">
            <v-icon v-on="on">mdi-plus</v-icon>
          </template>
          <span>Nuevo usuario</span>
        </v-tooltip>
      </v-btn>
    </v-toolbar>
    <v-card>
      <List :bus="bus"/>
    </v-card>
    <Edit :bus="bus"/>
    <Role :bus="bus"/>
  </v-container>
</template>

<script>
import _ from 'lodash'
import Vue from 'vue'
import List from '@/components/user/List'
import Edit from '@/components/user/Edit'
import Role from '@/components/user/Role'

export default {
  name: "userIndex",
  components: {
    List,
    Edit,
    Role
  },
  data: () => ({
    search: '',
    bus: new Vue(),
    enabled: true
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
