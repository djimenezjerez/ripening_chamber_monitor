<template>
  <div>
    <p class="ma-0 pa-0 font-weight-thin display-2 text-center">{{ room.display_name }}</p>
    <canvas :id="chartId" class="ma-0 pa-0"></canvas>
    <p class="ma-0 pa-0 font-weight-thin display-2 text-center">{{ value }} {{ magnitude.measure }}</p>
    <p v-if="date != ''" class="ma-0 pa-0 font-weight-thin caption text-center">Último date recibido el: {{ date.format('LLLL') }}</p>
  </div>
</template>

<script>
import Chart from 'chart.js';

export default {
  name: 'TemperatureChart',
  props: ['room', 'magnitude'],
  data: () => ({
    type: 'doughnut',
    value: 0,
    date: '',
    options: {
      rotation: 1 * Math.PI,
      circumference: 1 * Math.PI,
      tooltips: {
        enabled: false
      },
      hover: {
        mode: null
      },
      responsive: true
    },
    data: {
      datasets: [{
        data: [0, 100],
        backgroundColor: ['red', 'lightgray']
      }]
    },
    chart: null
  }),
  computed: {
    topic() {
      return `${this.room.name}/${this.magnitude.name}`
    },
    chartId() {
      return `${this.room.name}.${this.magnitude.name}`
    }
  },
  mounted() {
    this.createChart()
    this.data.labels = [
      `Límite mínimo: ${this.room.pivot.min_limit}`,
      `Límite máximo: ${this.room.pivot.max_limit}`
    ]
    this.$mqtt.subscribe(this.topic)
  },
  mqtt: {
    '+/tem' (data, topic) {
      if (topic.split('/')[0] == this.room.name) {
        let value = Number(String.fromCharCode.apply(null, data))
        if (this.value != value) {
          this.value = value
          this.date = this.$moment()
        }
      }
    }
  },
  watch: {
    value(val) {
      this.data.datasets[0].data[0] = val
      this.data.datasets[0].data[1] = 100 - val
      if (val < this.room.pivot.min_limit) {
        this.data.datasets[0].backgroundColor[0] = 'blue'
      } else if (val < this.room.pivot.max_limit) {
        this.data.datasets[0].backgroundColor[0] = 'green'
      } else {
        this.data.datasets[0].backgroundColor[0] = 'red'
      }
      this.chart.update()
    }
  },
  methods: {
    createChart(chartId) {
      const context = document.getElementById(this.chartId)
      this.chart = new Chart(context, {
        type: this.type,
        data: this.data,
        options: this.options
      })
    }
  }
}
</script>

