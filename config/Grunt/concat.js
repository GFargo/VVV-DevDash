module.exports = {
	options: {
        banner: '<%= project.banner %>',
        stripBanners: false,
        separator: '\n'
	},
	dist: {
	  src: ['<%= project.paths.js %>/src/*.js'],
	  dest: '<%= project.paths.js %>/<%= pkg.name %>.js'
	},
};