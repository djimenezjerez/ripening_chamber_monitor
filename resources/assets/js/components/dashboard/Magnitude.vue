<template>
  <v-container fluid>
    <v-card>
      <v-toolbar dense>
        <v-toolbar-title>{{ magnitude.display_name }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-layout wrap>
          <v-flex xs12 md4 v-for="room in rooms" :key="room.id">
            <TemperatureChart v-if="magnitude.name == 'tem'" :room="room" :magnitude="magnitude"/>
            <HumidityChart v-if="magnitude.name == 'hum'" :room="room" :magnitude="magnitude"/>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
import TemperatureChart from './TemperatureChart'
import HumidityChart from './HumidityChart'

export default {
  name: 'Magnitude',
  props: ['magnitude'],
  components: {
    TemperatureChart,
    HumidityChart
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

