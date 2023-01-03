/** @type {import('postcss').Config} */
module.exports = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
    'postcss-prefix-selector':{
      prefix: '#your-plugin-name'
    }
  },
}
