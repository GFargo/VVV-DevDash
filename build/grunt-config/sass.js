// directory based instead of using specific file names
// http://gruntjs.com/configuring-tasks#building-the-files-object-dynamically
module.exports = {
	dev: {
	  options: {
	    style: 'expanded',
	    debugInfo: false,
	    sourcemap: 'auto', // options: auto, file, inline, none
	    // compass: true
	  },
	  files: [{
	    expand: true,
	    cwd: '<%= project.paths.sass %>',
	    src: [
	    	'**/*.{scss,sass}',
	    	'!**/vendor/*' // ignores files in lib dir
	    	],
	    dest: '<%= project.paths.css %>',
	    ext: '.css'
	  }],
	},

	dist: {
	  options: {
	    style: 'compressed',
	    sourcemap: 'none',
	  },
	  files: [{
	    expand: true,
	    cwd: '<%= project.paths.sass %>',
	    src: [
	    	'**/*.{scss,sass}',
	    	'!**/vendor/*'
	    	],
	    dest: '<%= project.paths.css %>',
	    ext: '.css'
	  }],
	},
};