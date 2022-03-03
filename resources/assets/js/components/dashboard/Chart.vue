<template>
  <div>
    <v-dialog v-model="dialog" persistent max-width="360">
      <v-card>
        <v-card-title class="text-h5"> Agregar dato </v-card-title>
        <v-card-text>
          <v-text-field
            class="pl-5 pr-5"
            v-validate="'required|min_value:0|max_value:100|decimal:[2,`,`]'"
            v-model="newValue"
            label="Medida"
            name="medida"
            :error-messages="errors.collect('medida')"
            autocomplete="off"
            autofocus
            required
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" @click="dialog = false"> Cancelar </v-btn>
          <v-btn color="success" @click="setValue()" @keyup.enter="setValue()">
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <canvas :id="chartId" class="ma-0 pa-0"></canvas>
    <p align="center">
      <v-btn
        text
        class="ma-0 pa-0 font-weight-thin headline text-center"
        @click="openDialog()"
      >
        {{ value }} {{ magnitude.measure }}
      </v-btn>
    </p>
    <p
      v-if="date != ''"
      align="center"
      class="ma-0 pa-0 font-weight-thin caption text-center"
    >
      Último date recibido el: {{ date.format("LLLL") }}:{{ date.format("ss") }}
    </p>
  </div>
</template>

<script>
import Chart from "chart.js";

export default {
  name: "Chart",
  props: ["room", "magnitude"],
  data() {
    return {
      dialog: false,
      newValue: 0,
      type: this.magnitude.name == "tem" ? "doughnut" : "line",
      value: 0,
      limitValues: 10,
      date: "",
      options:
        this.magnitude.name == "tem"
          ? {
              title: {
                display: true,
                text: [
                  this.room.display_name,
                  `Límite mínimo: ${
                    this.room.pivot.min_limit
                  } - Límite máximo: ${this.room.pivot.max_limit}`
                ]
              },
              rotation: 1 * Math.PI,
              circumference: 1 * Math.PI,
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
            }
          : {
              title: {
                display: true,
                text: [
                  this.room.display_name,
                  `Límite mínimo: ${
                    this.room.pivot.min_limit
                  } - Límite máximo: ${this.room.pivot.max_limit}`
                ]
              },
              legend: {
                display: false
              },
              tooltips: {
                enabled: true
              },
              responsive: true
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
      data:
        this.magnitude.name == "tem"
          ? {
              datasets: [
                {
                  data: [0, 100],
                  backgroundColor: ["red", "lightgray"]
                }
              ]
            }
          : {
              labels: [],
              datasets: [
                {
                  data: [],
                  borderColor: "blue",
                  fill: false
                }
              ]
            },
      chart: null
    };
  },
  computed: {
    chartId() {
      return `${this.magnitude.name}.${this.room.name}`;
    }
  },
  mounted() {
    this.createChart();
    if (this.magnitude.name == "tem") {
      this.data.labels = [
        `Límite mínimo: ${this.room.pivot.min_limit}`,
        `Límite máximo: ${this.room.pivot.max_limit}`
      ];
    }
  },
  watch: {
    value(val) {
      if (this.magnitude.name == "tem") {
        this.data.datasets[0].data[0] = val;
        this.data.datasets[0].data[1] = 100 - val;
        if (val < this.room.pivot.min_limit) {
          this.data.datasets[0].backgroundColor[0] = "blue";
        } else if (val < this.room.pivot.max_limit) {
          this.data.datasets[0].backgroundColor[0] = "green";
        } else {
          this.data.datasets[0].backgroundColor[0] = "red";
        }
        this.chart.update();
      } else if (["hum", "hic"].includes(this.magnitude.name)) {
        if (this.data.datasets[0].data.length == this.limitValues) {
          this.data.labels.shift();
          this.data.datasets[0].data.shift();
        }
        this.data.labels.push(this.$moment(this.date).format("HH:mm:ss"));
        this.data.datasets[0].data.push(val);
        if (val < this.room.pivot.min_limit) {
          this.data.datasets[0].borderColor = "blue";
        } else if (val < this.room.pivot.max_limit) {
          this.data.datasets[0].borderColor = "green";
        } else {
          this.data.datasets[0].borderColor = "red";
        }
        this.chart.update();
      } else {
        console.log(`Undefined magnitude ${this.magnitude.name}`);
      }
    }
  },
  methods: {
    createChart() {
      const context = document.getElementById(this.chartId);
      this.chart = new Chart(context, {
        type: this.type,
        data: this.data,
        options: this.options
      });
    },
    openDialog() {
      this.dialog = true;
      this.newValue = 0;
    },
    async setValue() {
      try {
        await axios.post(`measurement`, {
          room_id: this.room.id,
          magnitude_id: this.magnitude.id,
          value: parseFloat(this.newValue).toFixed(2)
        });
        this.value = parseFloat(this.newValue).toFixed(2);
        this.date = this.$moment();
        this.dialog = false;
      } catch (error) {
        console.log("Error al guardar el dato");
      }
    }
  }
};
</script>
