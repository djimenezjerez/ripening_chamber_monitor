<template>
  <v-tooltip top>
    <template v-slot:activator="{ on }">
      <v-btn
        fab
        small
        class="ml-5"
        color="success"
        :disabled="!enabled"
        v-on="on"
        @click.stop="download()"
      >
        <v-icon>mdi-file-excel</v-icon>
      </v-btn>
    </template>
    <span>Descargar reporte</span>
  </v-tooltip>
</template>

<script>
export default {
  name: 'report-export',
  props: ['bus'],
  data: () => ({
    enabled: false,
    data: {
      room: null,
      from: null,
      to: null
    }
  }),
  mounted() {
    this.bus.$on('enableExport', val => {
      this.enabled = val
    })
    this.bus.$on('search', val => {
      this.data = val
    })
  },
  methods: {
    getFilename(text) {
      return new Promise((resolve, reject) => {
        try {
          return resolve(text.split('filename=')[1].split(';')[0])
        } catch (e) {
          return resolve('reporte.xlsx')
        }
      })
    },
    async download() {
      try {
        this.enabled = false
        let res = await axios({
            url: `room/${this.data.room}/export/${this.data.from}/${this.data.to}`,
            method: 'GET',
            responseType: 'blob'
        })
        let filename = await this.getFilename(res.headers['content-disposition'])
        const url = window.URL.createObjectURL(new Blob([res.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', filename)
        document.body.appendChild(link)
        link.click()
      } catch (e) {
        console.log(e)
        this.enabled = true
      } finally {
        this.enabled = true
      }
    }
  }
}
</script>