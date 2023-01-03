/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./shortcodes/*/*.html"],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}
