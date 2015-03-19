/*global module:false*/
/*global require:false*/
/*jshint -W097*/
"use strict";

module.exports = function(grunt) {
 
    // load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
 
    grunt.initConfig({
    
        pkg: grunt.file.readJSON('package.json'), 

        // watch for changes and trigger compass, jshint, uglify and livereload
        watch: {            
            sass: {
                files: ['sass/*.scss', 'sass/**/*.scss'],
                tasks: ['sass']
            },
            concat: {
                files: ['js/benny/*.js', 'js/benny/**/*.js'], 
                tasks: ['concat']
            },            
            uglify: {
                files: 'js/benny-lib.js', 
                tasks: ['uglify']
            },
            sync: {
                files: [
                    '**',
                    '!.git*', 
                    '!node_modules', 
                    '!node_modules/**', 
                    '!.sass-cache', 
                    '!Gruntfile.js', 
                    '!package.json', 
                    '!.DS_Store',
                    '!**/.DS_Store',
                    '!README.md', 
                    '!.jshintrc',  
                    '!sass', 
                    '!sass/**'
                ],
                tasks: ['sync']
            }        
        },    

        // Sass
        sass: {
            dist: {
                files: {
                    'style.css' : 'sass/style.scss'
                }
            }
        },

        // Sync
        sync: {            
            dist: {
                files: [
                    // includes files within path
                    {
                        src: [  
                            '**',
                            '!.git*', 
                            '!node_modules', 
                            '!node_modules/**', 
                            '!.sass-cache', 
                            '!Gruntfile.js', 
                            '!package.json', 
                            '!.DS_Store',
                            '!**/.DS_Store',
                            '!README.md', 
                            '!.jshintrc',  
                            '!sass', 
                            '!sass/**'
                        ], 
                        dest: '../../themes/benny'
                    }
                ], 
                verbose: true
            }
        },

        // concat js
        concat: {            
            options: {
                separator: "\n\n;",
                stripBanners: false,
                banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' + '<%= grunt.template.today("yyyy-mm-dd") %> */\n\n',
            },
            dist: {
                files: {
                    'js/benny-lib.js' : [
                        'js/benny/*.js', 
                        'js/benny/**/*.js'
                    ]
                }
            },
        },
 
        // javascript linting with jshint
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                force: true,
                reporter: require('jshint-stylish')
            },
            all: [
                'Gruntfile.js', 
                'js/benny.js', 
                'js/benny-lib.js', 
                'js/admin/theme-customizer.js',
            ]
        },

        // uglify to concat, minify, and make source maps
        uglify: {
            dist: {
                files: {
                    'js/benny-lib.min.js': [ 
                        'js/benny-lib.js'
                    ]
                }
            }
        },           

        // make POT file
        makepot: {
            target: {
                options: {
                    cwd: '',                    // Directory of files to internationalize.
                    domainPath: '/languages',   // Where to save the POT file.                    
                    mainFile: 'style.css',      // Main project file.
                    potFilename: 'benny.pot', // Name of the POT file.
                    type: 'wp-theme',           // Type of project (wp-plugin or wp-theme).
                    updateTimestamp: true       // Whether the POT-Creation-Date should be updated without other changes.
                }
            }
        },
    });
 
    // register task
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('build', ['concat', 'uglify', 'sync', 'jshint', 'makepot']);
};