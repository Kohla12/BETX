/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./app/**/*.{js,ts,jsx,tsx}",
    "./components/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        neon: {
          cyan: "#22d3ee",
          magenta: "#e879f9",
          lime: "#a3e635",
        },
      },
      backgroundImage: {
        "grid-neon": "radial-gradient(circle at 1px 1px, #1e293b 1px, #020617 0)",
      },
    },
  },
  plugins: [],
};

