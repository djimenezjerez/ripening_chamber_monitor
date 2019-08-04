<template>
  <div>
    <canvas :id="chartId" class="ma-0 pa-0"></canvas>
    <p align="center" class="ma-0 pa-0 font-weight-thin headline text-center">{{ value }} {{ magnitude.measure }}</p>
    <p v-if="date != ''"  align="center" class="ma-0 pa-0 font-weight-thin caption text-center">Último date recibido el: {{ date.format('LLLL') }}:{{ date.format('ss')}}</p>
  </div>
</template>

<script>
import Chart from 'chart.js';

export default {
  name: 'Chart',
  props: ['room', 'magnitude'],
  data() {
    return {
      type: (this.magnitude.name == 'tem') ? 'doughnut' : 'line',
      value: 0,
      limitValues: 10,
      date: '',
      options: (this.magnitude.name == 'tem') ? {
        title: {
          display: true,
          text: [this.room.display_name, `Límite mínimo: ${this.room.pivot.min_limit} - Límite máximo: ${this.room.pivot.max_limit}`]
        },
        rotation: 1 * Math.PI,
        circumference: 1 * Math.PI,
        legend: {
          display: false,
        },
        tooltips: {
          enabled: false
        },
        hover: {
          mode: null
        },
        responsive: true
      } : {
        title: {
          display: true,
          text: [this.room.display_name, `Límite mínimo: ${this.room.pivot.min_limit} - Límite máximo: ${this.room.pivot.max_limit}`]
        },
        legend: {
          display: false,
        },
        tooltips: {
          enabled: true
        },
        responsive: true,
        // scales: {
        //   yAxes: [{
        //     display: true,
        //     ticks: {
        //       suggestedMin: 0,
        //       suggestedMax: 100
        //     }
        //   }]
        // }
      },
      data: (this.magnitude.name == 'tem') ? {
        datasets: [{
          data: [0, 100],
          backgroundColor: ['red', 'lightgray']
        }]
      } : {
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
    if ((this.magnitude.name == 'tem')) {
      this.data.labels = [
        `Límite mínimo: ${this.room.pivot.min_limit}`,
        `Límite máximo: ${this.room.pivot.max_limit}`
      ]
    }
    this.$mqtt.subscribe(this.topic)
    console.log(`Subscribed to: ${this.topic}`)
  },
  beforeDestroy() {
    this.$mqtt.unsubscribe(this.topic)
  },
  mqtt: {
    'hum/+' (data, topic) {
      this.verifyTopic(data, topic)
    },
    'tem/+' (data, topic) {
      this.verifyTopic(data, topic)
    },
    'hic/+' (data, topic) {
      this.verifyTopic(data, topic)
    }
  },
  watch: {
    value(val) {
      if ((this.magnitude.name == 'tem')) {
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
      } else if (['hum', 'hic'].includes(this.magnitude.name)) {
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
      } else {
        console.log(`Undefined magnitude ${this.magnitude.name}`)
      }
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
    },
    verifyTopic(data, topic) {
      let t = topic.split('/')
      if (t[0] == this.magnitude.name && t[1] == this.room.name) {
        let value = Number(String.fromCharCode.apply(null, data))
        if (this.value != value) {
          this.value = value
          this.date = this.$moment()
        }
      }
    }
  }
}
</script>

