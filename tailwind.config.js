/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./public/**/*.html",
    "./marketplace/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        // New universal color palette
        'soft-linen': '#f2ede4',
        'ash-grey': '#abaf9f',
        'golden-bronze': '#be9e47',
        'olive': '#97863d',
        'fern': '#347e44',
        'fern-light': '#4a9a56',
        'fern-dark': '#2a5e35',
        'golden-bronze-light': '#d4b368',
        'soft-linen-dark': '#e5e0d5',
        'dark-fern': '#1a3d22',
        // Legacy aliases for backward compatibility
        earth: '#347e44',
        leaf: '#347e44',
        'leaf-light': '#4a9a56',
        crop: '#be9e47',
        'crop-light': '#d4b368',
        cream: '#f2ede4',
        'cream-dark': '#e5e0d5',
        mist: '#f2ede4',
        charcoal: '#1a3d22',
        'text-muted': '#abaf9f',
      },
      fontFamily: {
        head: ['"DM Serif Display"', 'Georgia', 'serif'],
        body: ['"DM Sans"', 'sans-serif'],
      },
      borderRadius: {
        'xl': '10px',
      },
      transitionTimingFunction: {
        'custom': 'cubic-bezier(0.4, 0, 0.2, 1)',
      },
      transitionDuration: {
        '280': '280ms',
      },
    },
  },
  plugins: [],
}
