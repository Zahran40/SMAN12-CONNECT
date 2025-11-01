/** @type {import('tailwindcss').Config} */
export default {
  // Tailwind v4 primarily uses @source in CSS; keep content as a fallback for tooling.
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
