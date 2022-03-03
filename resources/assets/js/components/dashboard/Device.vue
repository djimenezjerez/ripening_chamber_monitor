<template>
  <v-container fluid>
    <v-card>
      <v-toolbar flat dense color="tertiary">
        <v-toolbar-title>Sensores</v-toolbar-title>
      </v-toolbar>
      <v-card-title></v-card-title>
      <v-card-text>
        <v-layout wrap>
          <v-flex xs12 md4 lg4 v-for="device in devices" :key="device.id">
            <v-chip
              class="pa-2"
              :color="device.online ? 'success' : 'error'"
              text-color="white"
              :close="false"
            >
              <v-avatar left>
                <v-icon v-if="device.online">mdi-check-circle</v-icon>
                <v-icon v-else>mdi-close-circle</v-icon>
              </v-avatar>
              {{ device.display_name }}
            </v-chip>
            <p class="mt-1 ml-1 overline">
              Actualizado el: {{ $moment(device.updated_at).format("LLL") }}
            </p>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: "device-list",
  props: ["devices"]
};
</script>
