module.exports = {
	options: {
    	browsers: ['last 2 versions', 'ie 8', 'ie 9']
    },
    dist: {
    	src: ['<%= project.paths.css %>/*.css']
    },
    check: {
		options: {
			diff: true,
			remove: false, // stops outdated prefixes cleaning
		},
		expand: true,
		flatten: true,
		src: '<%= project.paths.css %>/*.css',
		dest: '' // Outputs diff files to root
    },
};