/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.jsx",
    "./resources/**/*.ts",
    "./resources/**/*.tsx",
  ],
  theme: {
    extend: {
      colors: {
        healthcare: {
          bg: '#F8FAFC',
          surface: '#F5F5F0',
          indigo: '#5B6B9C',
          teal: '#4A90E2',
          sage: '#81B29A',
          text: '#2D3748',
          subtle: '#718096',
        }
      }
    },
  },
  plugins: [],
}
