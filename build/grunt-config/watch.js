module.exports = {
  all: {
  	options: {
  		livereload: true
  	},
  	files: [
      '<%= project.paths.js %>/src/*.js',
  		// '/app/views/**/*.php',
  		'<%= project.paths.sass %>/{,*/}*.{scss,sass}'
  	],
  	tasks: ['sass:dev', 'js-dist']
  },
  styles: {
    files: '<%= project.paths.sass %>/{,*/}*.{scss,sass}',
    tasks: ['sass:dev'],
  }
};