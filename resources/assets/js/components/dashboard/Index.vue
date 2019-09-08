<template>
  <div>
    <template v-for="magnitude in magnitudes">
      <Magnitude :magnitude="magnitude" :key="magnitude.id"/>
    </template>
    <Device :devices="devices"/>
  </div>
</template>

<script>
import Magnitude from '@/components/dashboard/Magnitude'
import Device from '@/components/dashboard/Device'

export default {
  name: "dashboard-index",
  components: {
    Magnitude,
    Device
  },
  data: () => ({
    magnitudes: [],
    devices: []
  }),
  mounted() {
    this.getDevices()
    this.getMagnitudes()
  },
  methods: {
    async getDevices() {
      try {
        let res = await axios.get(`device`)
        this.devices = res.data.data
        if (this.devices.length == 0) {
          this.$router.push({
            name: 'deviceIndex'
          })
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
      } catch (e) {
        console.log(e)
      }
    }
  }
};
</script>
