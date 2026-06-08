/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.js',
    './assets/**/*.css'
  ],
  theme: {
    extend: {
      colors: {
        aperture: {
          blue: '#006edc',
          dark: '#05070a',
          ink: '#15191f',
          muted: '#69717d',
          line: '#dce2ea',
          panel: '#f5f8fb'
        }
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        display: ['Barlow Condensed', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif']
      },
      boxShadow: {
        card: '0 18px 60px rgba(5, 7, 10, 0.08)'
      },
      backgroundImage: {
        blueprint: 'linear-gradient(rgba(105,113,125,.12) 1px, transparent 1px), linear-gradient(90deg, rgba(105,113,125,.12) 1px, transparent 1px)'
      }
    }
  },
  plugins: []
}
