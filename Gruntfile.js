module.exports = function(grunt) {

  'use strict';

  require('time-grunt')(grunt);

  /*****************************************************
    Grunt Init Config
  *****************************************************/
  grunt.initConfig({
    /*
      Configs
    */
    pkg: grunt.file.readJSON('package.json'),
    project: require('./config/Grunt/project'),
    watch: require('./config/Grunt/watch.js'),
    /*
      CSS
    */
    sass: require('./config/Grunt/sass'),
    csscss: require('./config/Grunt/csscss'),
    autoprefixer: require('./config/Grunt/autoprefixer'),
    uncss: require('./config/Grunt/uncss'),
    /*
      JS
    */
    concat: require('./config/Grunt/concat'),
    uglify: require('./config/Grunt/uglify'),
    jshint: require('./config/Grunt/jshint'),
    qunit: require('./config/Grunt/qunit'),
  });

  /*****************************************************
    Core Tasks
  *****************************************************/

  grunt.loadNpmTasks('grunt-contrib-sass');

  /*****************************************************
    Dev Tasks
  *****************************************************/

  // Default Task
  grunt.registerTask('default', [], function(){
    grunt.task.run('sass:dev','js-dist');
  });

  // Watch CSS Files
  grunt.registerTask('watch-css', [], function() {
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.task.run('watch:styles');
  });

  // Global Watch
  grunt.registerTask('watch', [], function() {
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.task.run('watch:all');
  });

  /*****************************************************
    Dist Tasks
  *****************************************************/

  // Javascript
  grunt.registerTask('js-dist', [], function(){
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.task.run('jshint:all', 'concat');
  });

  // Check Files
  grunt.registerTask('check', 'Checks for Redundancies / Autprefixes', function() {
    grunt.loadNpmTasks('grunt-csscss');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.task.run('sass:dev','csscss:check','autoprefixer:check');
  });

  // Final Build
  grunt.registerTask('dist', 'Compiles all files for live environment', function() {
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.task.run('sass:dist','autoprefixer:dist', 'js-dist');
  });


};

