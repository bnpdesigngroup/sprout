module.exports = (grunt) ->

  grunt.initConfig
    pkg: grunt.file.readJSON("package.json")

    watch:
      config:
        files: ['package.json', 'Gruntfile.coffee']
      styles:
        files: ['assets/scss/**/*.scss', 'config.rb']
        tasks: ['styles', 'cssmin', 'bless']
        options:
          livereload: true
      scripts:
        files: ['assets/coffee/**/*.coffee', '!Gruntfile.coffee', '!groundwork.all.coffee']
        tasks: ['scripts']
        options:
          livereload: true

    clean:
      build:
        src: [
          "assets/css/**/*.css"
        ]

    compass:
      build:
        options:
          config: 'config.rb'
          trace: true

    autoprefixer:
      build:
        options:
          browsers: ['last 2 versions']
        files: [
          expand: true
          cwd: 'assets/css'
          src: ['**/*.css']
          dest: 'assets/css'
          ext: '.css'
        ]

    coffee:
      individual:
        expand: true
        cwd: 'assets/coffee'
        src: ['**/*.coffee', '!groundwork/groundwork.all.coffee']
        dest: 'assets/js'
        ext: '.js'
      concatenated:
        options:
          join: true
        files:
          "assets/js/groundwork.js": ["assets/coffee/groundwork/components/*.coffee", "assets/coffee/groundwork/plugins/*.coffee"]

    uglify:
      build:
        expand: true
        cwd: 'assets/js'
        src: ['**/*.js', '!**/*.min.js']
        dest: 'assets/js'
        ext: '.min.js'

    cssmin:
      options:
        inline_import: false
      build:
        expand: true
        cwd: 'assets/css'
        src: ['**/*.css', '!**/*.min.css']
        dest: 'assets/css'
        ext: '.min.css'

    bless:
      build:
        files: [
          expand: true
          cwd: 'assets/css'
          src: ['**/*.min.css', '!**/*blessed*.css']
          dest: 'assets/css'
          ext: '.min.css',
        ]


  grunt.loadNpmTasks 'grunt-contrib-watch'
  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-clean'
  grunt.loadNpmTasks 'grunt-contrib-compass'
  grunt.loadNpmTasks 'grunt-autoprefixer'
  grunt.loadNpmTasks 'grunt-contrib-cssmin'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-bless'

  grunt.registerTask 'default',           ['build']
  grunt.registerTask 'build',             ['styles', 'scripts']
  grunt.registerTask 'styles',            ['clean', 'compass', 'autoprefixer']
  grunt.registerTask 'scripts',           ['coffee']
  grunt.registerTask 'compress',          ['cssmin', 'uglify']
  grunt.registerTask 'package',           ['styles', 'scripts', 'compress', 'bless']
