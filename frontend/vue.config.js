const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true,
  devServer: {
    port: 8080, // Altere para a porta desejada
    host: '0.0.0.0', // Permite acesso externo, se necessário
  },

})
