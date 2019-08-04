<template>
  <div>
    <canvas :id="chartId" class="ma-0 pa-0"></canvas>
    <p class="ma-0 pa-0 font-weight-thin headline text-center">{{ value }} {{ magnitude.measure }}</p>
    <p v-if="date != ''" class="ma-0 pa-0 font-weight-thin caption text-center">Último date recibido el: {{ date.format('LLLL') }}:{{ date.format('ss')}}</p>
  </div>
</template>

<script>
import Chart from 'chart.js';

export default {
  name: 'HumidityChart',
  props: ['room', 'magnitude'],
  data() {
    return {
      type: 'line',
      value: 0,
      limitValues: 10,
      date: '',
      options: {
        title: {
          display: true,
          text: this.room.display_name
        },
        legend: {
          display: false
        },
        tooltips: {
          enabled: false
        },
        hover: {
          mode: null
        },
        responsive: true
      },
      data: {
        labels: [],
        datasets: [
          {
            data: [],
            borderColor: 'blue',
            fill: false
          }
        ]
      },
      chart: null
    }
  },
  computed: {
    topic() {
      return `${this.magnitude.name}/${this.room.name}`
    },
    chartId() {
      return `${this.magnitude.name}.${this.room.name}`
    }
  },
  mounted() {
    this.createChart()
    this.$mqtt.subscribe(this.topic)
    console.log(`Subscribed to: ${this.topic}`)
  },
  beforeDestroy() {
    this.$mqtt.unsubscribe(this.topic)
  },
  mqtt: {
    'hum/+' (data, topic) {
      if (topic.split('/')[1] == this.room.name) {
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
      if (this.data.datasets[0].data.length == this.limitValues) {
        this.data.labels.shift()
        this.data.datasets[0].data.shift()
      }
      this.data.labels.push(this.$moment(this.date).format('HH:mm:ss'))
      this.data.datasets[0].data.push(val)
      if (val < this.room.pivot.min_limit) {
        this.data.datasets[0].borderColor = 'blue'
      } else if (val < this.room.pivot.max_limit) {
        this.data.datasets[0].borderColor = 'green'
      } else {
        this.data.datasets[0].borderColor = 'red'
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

