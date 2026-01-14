/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        cream: "#FAF7F2",
        terracotta: "#F3A6B6",
        pinksoft: "#FBE4EC",
      },
    },
  },
  plugins: [],
}
