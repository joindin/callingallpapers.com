module.exports = function (grunt) {
   'use strict';

   require('load-grunt-tasks')(grunt);

   var config = {
      src: 'public',
      dist: 'dist',
      tmp: '.tmp',
   };

   grunt.initConfig({
      config: config,

      clean: ['<%= config.dist %>/*', '<%= config.tmp %>/*'],

      copy: {
         html: {
            expand: true,
            cwd: '<%= config.src %>',
            src: ['*.html'],
            dest: '<%= config.dist %>/'
         },
         fonts: {
            expand: true,
            cwd: '<%= config.src %>/inc/lib/fontawesome',
            src: ['fonts/*.*'],
            dest: '<%= config.dist %>'
         }
      },

      jshint: {
         files: [
            'Gruntfile.js',
            '<%= config.src %>/inc/js/*.js'
         ],
         options: {
            reporter: require('jshint-stylish')
         }
      },

      filerev: {
         dist: {
            src: [
               '<%= config.dist %>/css/*.css',
               '<%= config.dist %>/js/*.js'
            ]
         }
      },

      uncss: {
         dist: {
            options: {
               htmlroot: '.',
               ignore: [/_720kb-/],
               stylesheets: ['/<%= config.tmp %>/concat/css/main.css'],
            },
            files: {
               '<%= config.tmp %>/concat/css/main.css': ['<%= config.dist %>/index.html']
            }
         }
      },

      useminPrepare: {
         options: {
            dest: '<%= config.dist %>'
         },
         html: '<%= config.src %>/index.html'
      },

      usemin: {
         options: {
            assetsDirs: [
               '<%= config.dist %>',
               '<%= config.dist %>/css',
               '<%= config.dist %>/js'
            ]
         },
         html: ['<%= config.dist %>/index.html'],
      }
   });

   grunt.registerTask('default', [
      'clean',
      'jshint',
      'useminPrepare',
      'copy',
      'concat',
      'uncss',
      'cssmin',
      'uglify',
      'filerev',
      'usemin'
   ]);
};