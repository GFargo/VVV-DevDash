module.exports = {
    options: {
      banner: '<%= banner %>',
    },
    dist: {
      files: {
        '<%= project.paths.js %>/<%= pkg.name %>.min.js': ['<%= concat.dist.dest %>']
      }
    },
    dev: {
      options: {
        mangle: false, // Stops uglify from mangling variables https://github.com/gruntjs/grunt-contrib-uglify#mangle
        compress: false,
        beautify: true,
      },
      files: {
        '<%= project.paths.js %>/<%= pkg.name %>.min.js': ['<%= concat.dist.dest %>']
      }
    },
};