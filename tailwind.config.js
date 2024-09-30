/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js}"],
  theme: {
    extend: {
      colors: {
        primary: '#ed923a',
        darkprimary: '#e07814',
        secondary: '#3ad1ed',
        secondaryContent: '#032524',
        secondaryDark: '#14c0e0'
      },
      fontFamily: {
        abeezee: ['ABeeZee', 'sans-serif'],
        bangers: ['Bangers', 'cursive'],
        fredoka: ['Fredoka', 'sans-serif'],
        AptosReg: ['Aptos'],
        // aptosDisplay: ['Aptos Display', 'sans-serif', defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [],
}

