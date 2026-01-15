/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        // Pink Theme Colors
        humble: "#4A202A",
        inlove: "#83394A",
        bubble: "#E4879E",
        secrets: "#EEAAC3",
        milk: "#F5C0D3",
        almost: "#FCD6E3",
        pinkbg: "#FEF0F5",
        ink: "#2b0f16",
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
      },
      borderRadius: {
        'xl': '16px',
        '2xl': '20px',
        '3xl': '24px',
      },
      boxShadow: {
        'soft': '0 8px 24px rgba(74,32,42,.10)',
        'hover': '0 16px 40px rgba(74,32,42,.16)',
      },
    },
  },
  plugins: [],
}
