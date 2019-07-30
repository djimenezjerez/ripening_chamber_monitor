<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Roles</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-card>
      <v-card-text>
        <List :bus="bus"/>
      </v-card-text>
    </v-card>
    <Edit :bus="bus"/>
  </v-container>
</template>

<script>
import Vue from 'vue'
import List from './List'
import Edit from './Edit'

export default {
  name: "roleIndex",
  components: {
    List,
    Edit
  },
  data: () => ({
    bus: new Vue()
  })
}
</script>