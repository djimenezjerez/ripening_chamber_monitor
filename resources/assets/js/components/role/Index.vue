<template>
  <v-container fluid>
    <v-toolbar flat dense color="tertiary">
      <v-toolbar-title>Roles</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-card>
      <List :bus="bus"/>
    </v-card>
    <Edit :bus="bus"/>
  </v-container>
</template>

<script>
import Vue from 'vue'
import List from '@/components/role/List'
import Edit from '@/components/role/Edit'

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