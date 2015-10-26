module.exports = {
  dist: {
    src: ['app/about.html', 'app/index.html'],
    dest: 'dist/css/tidy.css',
    options: {
      report: 'min' // optional: include to report savings
    }
  }
}
