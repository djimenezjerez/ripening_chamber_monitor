<template>
  <v-footer
    app
    fixed
    permanent
    class="font-weight-light overline py-0"
  >
    <v-spacer></v-spacer>
    <div>Daniel Jimenez <v-icon class="mdi-flip-h">mdi-copyright</v-icon> - {{ $moment().year() }}</div>
  </v-footer>
</template>
<script>
export default {
  name: 'app-footer'
}
</script>