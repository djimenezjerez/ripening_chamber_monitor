<template>
  <v-container fluid>
    <v-card>
      <v-toolbar flat dense color="tertiary">
        <v-toolbar-title>{{ magnitude.display_name }}</v-toolbar-title>
      </v-toolbar>
      <v-card-title></v-card-title>
      <v-card-text>
        <v-layout wrap>
          <v-flex xs12 md4 lg4 v-for="room in rooms" :key="room.id">
            <Chart :room="room" :magnitude="magnitude"/>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
import Chart from '@/components/dashboard/Chart'

export default {
  name: 'Magnitude',
  props: ['magnitude'],
  components: {
    Chart
  },
  data: () => ({
    rooms: []
  }),
  mounted() {
    this.getRooms()
  },
  methods: {
    async getRooms() {
      try {
        let res = await axios.get(`magnitude/${this.magnitude.id}/room`)
        this.rooms = res.data
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>

