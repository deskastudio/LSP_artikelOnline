module.exports = {
  content: [
    './*.php',           // Semua file PHP di root
    './**/*.php',        // Semua file PHP di subfolder
    './*.html',          // Jika ada file HTML di root
    './**/*.html',       // Jika ada file HTML di subfolder
    './src/**/*.js',     // Jika ada file JS di src folder
    './src/**/*.vue',    // Jika menggunakan Vue
    './src/**/*.jsx',    // Jika menggunakan React JSX
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
