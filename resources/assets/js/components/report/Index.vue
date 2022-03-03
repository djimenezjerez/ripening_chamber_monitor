<template>
  <v-container fluid>
    <v-toolbar dense color="tertiary">
      <v-toolbar-title>Reportes</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-toolbar class="pt-3">
      <v-layout wrap>
        <v-spacer></v-spacer>
        <v-flex xs4>
          <v-select
            :items="rooms"
            label="Ambiente"
            prepend-icon="mdi-door-open"
            class="px-4"
            item-text="display_name"
            item-value="id"
            v-model="data.room"
          ></v-select>
        </v-flex>
        <v-flex xs3>
          <v-select
            :items="magnitudes"
            label="Magnitud"
            prepend-icon="mdi-opacity"
            class="px-4"
            item-text="display_name"
            item-value="id"
            v-model="data.magnitude"
          ></v-select>
        </v-flex>
        <v-flex xs2>
          <v-menu
            v-model="showDate.from"
            :close-on-content-click="true"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="formatted.from"
                label="Desde"
                prepend-icon="mdi-calendar"
                class="px-4"
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              :max="data.to"
              v-model="data.from"
              @input="showDate.from = false"
            ></v-date-picker>
          </v-menu>
        </v-flex>
        <v-flex xs2>
          <v-menu
            v-model="showDate.to"
            :close-on-content-click="true"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="formatted.to"
                label="Hasta"
                prepend-icon="mdi-calendar"
                class="px-4"
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              :min="data.from"
              v-model="data.to"
              @input="showDate.to = false"
            ></v-date-picker>
          </v-menu>
        </v-flex>
        <v-flex xs1>
          <v-spacer></v-spacer>
          <Export :bus="bus" />
        </v-flex>
      </v-layout>
    </v-toolbar>
    <v-card>
      <List :bus="bus" />
    </v-card>
  </v-container>
</template>

<script>
import Vue from "vue";
import List from "@/components/report/List";
import Export from "@/components/report/Export";

export default {
  name: "reportIndex",
  components: {
    List,
    Export
  },
  data() {
    return {
      bus: new Vue(),
      data: {
        room: null,
        magnitude: null,
        from: this.$moment().format("YYYY-MM-DD"),
        to: this.$moment().format("YYYY-MM-DD")
      },
      formatted: {
        from: this.$moment().format("DD/MM/YYYY"),
        to: this.$moment().format("DD/MM/YYYY")
      },
      showDate: {
        from: false,
        to: false
      },
      totalMeasurements: 0,
      rooms: [],
      magnitudes: []
    };
  },
  watch: {
    "data.room": function(newVal, oldVal) {
      if (newVal != oldVal) {
        this.magnitudes = [];
        this.getRoomMagnitudes();
      }
      if (this.data.magnitude) {
        this.emitSearch();
      }
    },
    "data.from": function(newVal, oldVal) {
      if (newVal != oldVal) {
        this.formatted.from = this.$moment(newVal).format("DD/MM/YYYY");
        this.emitSearch();
      }
    },
    "data.to": function(newVal, oldVal) {
      if (newVal != oldVal) {
        this.formatted.to = this.$moment(newVal).format("DD/MM/YYYY");
        this.emitSearch();
      }
    },
    "data.magnitude": function() {
      if (this.data.room) {
        this.emitSearch();
      }
    }
  },
  mounted() {
    this.getRooms();
    this.bus.$on("totalMeasurements", val => {
      if (val != this.totalMeasurements) {
        this.totalMeasurements = val;
      }
    });
  },
  methods: {
    emitSearch() {
      if (
        this.data.room != null &&
        this.data.magnitude != null &&
        this.data.from != null &&
        this.data.to != null
      ) {
        this.bus.$emit("search", this.data);
      }
    },
    async getRooms(params) {
      try {
        let res = await axios.get(`room`, {
          params: {
            page: 1,
            per_page: 1000,
            sortBy: "display_name",
            direction: "asc"
          }
        });
        this.rooms = res.data.data;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async getRoomMagnitudes() {
      try {
        let res = await axios.get(`room/${this.data.room}/magnitude`);
        this.magnitudes = res.data;
      } catch (e) {
        console.log(e);
      }
    }
  }
};
</script>
