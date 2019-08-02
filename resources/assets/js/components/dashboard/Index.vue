<template>
  <div>
    <template v-for="magnitude in magnitudes">
      <Magnitude :magnitude="magnitude" :key="magnitude.id"/>
    </template>
  </div>
</template>
<script>
import Magnitude from './Magnitude'

export default {
  name: "dashboardIndex",
  components: {
    Magnitude
  },
  data: () => ({
    magnitudes: []
  }),
  mounted() {
    this.getMagnitudes()
  },
  methods: {
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
