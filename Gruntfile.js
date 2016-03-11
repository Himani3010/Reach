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
                tasks: ['sass:watch']
            },
            concat: {
                files: ['js/reach/*.js', 'js/reach/**/*.js'], 
                tasks: ['concat']
            },            
            uglify: {
                files: 'js/reach-lib.js', 
                tasks: ['uglify']
            }, 
            editor: {
                files: ['sass/editor-style.scss'],
                tasks: ['sass:editor']
            },
        },

        // Sass
        sass: {            
            dist: {
                files: {
                    'css/main.css' : 'sass/main.scss',
                    'css/editor-style.css' : 'sass/editor-style.scss', 
                    'css/base.css' : 'sass/base.scss',
                    'css/palettes/_classic.css' : 'sass/palette-classic.scss'
                },
                trace: true
            },
            watch: {
                files: {
                    'css/main.css' : 'sass/main.scss', 
                    'css/base.css' : 'sass/base.scss'
                }, 
                trace: true
            },
            editor: {
                files: {
                    'css/editor-style.css' : 'sass/editor-style.scss'
                },
                trace: true
            },
            palettes: {
                files: {
                    'css/palettes/_classic.css' : 'sass/palette-classic.scss', 
                    'css/palettes/_light.css' : 'sass/palette-light.scss',
                    'css/palettes/_dark.css' : 'sass/palette-dark.scss', 
                    'css/palettes/_custom.css' : 'sass/palette-custom.scss',
                    'css/palettes/_franklin.css' : 'sass/palette-franklin.scss'
                },
                trace: true
            },
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
                    'js/reach-lib.js' : [
                        'js/reach/*.js', 
                        'js/reach/**/*.js'
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
                'js/reach.js', 
                'js/reach/*.js', 
                'js/admin/theme-customizer.js',
            ]
        },

        // uglify to concat, minify, and make source maps
        uglify: {
            dist: {
                files: {
                    'js/reach-lib.min.js': [ 
                        'js/reach-lib.js'
                    ]
                }
            }
        },           

        checktextdomain: {
            options:{
                text_domain: 'reach',
                create_report_file: true,
                keywords: [
                    '__:1,2d',
                    '_e:1,2d',
                    '_x:1,2c,3d',
                    'esc_html__:1,2d',
                    'esc_html_e:1,2d',
                    'esc_html_x:1,2c,3d',
                    'esc_attr__:1,2d',
                    'esc_attr_e:1,2d',
                    'esc_attr_x:1,2c,3d',
                    '_ex:1,2c,3d',
                    '_n:1,2,3,4d',
                    '_nx:1,2,4c,5d',
                    '_n_noop:1,2,3d',
                    '_nx_noop:1,2,3c,4d',
                    ' __ngettext:1,2,3d',
                    '__ngettext_noop:1,2,3d',
                    '_c:1,2d',
                    '_nc:1,2,4c,5d'
                ]
            },
            files: {
                src: [
                    '**/*.php', // Include all files
                    '!node_modules/**', // Exclude node_modules/
                    '!build/.*'// Exclude build/
                ],
                expand: true
            }
        },

        makepot: {
            target: {
                options: {
                    domainPath: '/languages/',    // Where to save the POT file.
                    exclude: ['build/.*'],
                    mainFile: 'style.css',    // Main project file.
                    potFilename: 'reach.pot',    // Name of the POT file.
                    potHeaders: {
                        poedit: true,                 // Includes common Poedit headers.
                        'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
                                },
                    type: 'wp-theme',    // Type of project (wp-plugin or wp-theme).
                    updateTimestamp: true,    // Whether the POT-Creation-Date should be updated without other changes.
                    processPot: function( pot, options ) {
                        pot.headers['report-msgid-bugs-to'] = 'https://www.wpcharitable.com/';
                        pot.headers['last-translator'] = 'WP-Translations (http://wp-translations.org/)';
                        pot.headers['language-team'] = 'WP-Translations <wpt@wp-translations.org>';
                        pot.headers['language'] = 'en_US';
                        var translation, // Exclude meta data from pot.
                            excluded_meta = [
                                'Plugin Name of the plugin/theme',
                                'Plugin URI of the plugin/theme',
                                'Author of the plugin/theme',
                                'Author URI of the plugin/theme'
                            ];
                            
                            for ( translation in pot.translations[''] ) {
                                if ( 'undefined' !== typeof pot.translations[''][ translation ].comments.extracted ) {
                                    if ( excluded_meta.indexOf( pot.translations[''][ translation ].comments.extracted ) >= 0 ) {
                                        console.log( 'Excluded meta: ' + pot.translations[''][ translation ].comments.extracted );
                                            delete pot.translations[''][ translation ];
                                    }
                                }
                            }

                        return pot;
                    }
                }
            }
        },

        // build Palette stylesheet
        copy : {
            main: {
                src:  [
                    '**',
                    '!bin/**',
                    '!composer.json',
                    '!composer.lock', 
                    '!phpunit.xml',
                    '!node_modules/**',
                    '!build/**',
                    '!.git/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!.gitignore',
                    '!tests/**',
                    '!**/Gruntfile.js',
                    '!**/package.json',
                    '!**/README.md',
                    '!**/*~', 
                    '!css/**/*.map',
                    '!css/**/_*.css',
                    '!css/*.map',
                    '!sass/**'                
                ],
                dest: 'build/<%= pkg.name %>/'
            },
            classic : {
                src: 'css/palettes/_classic.css', 
                dest: 'css/palettes/classic.css', 
                options: {
                    process: function(content, path) {
                        var split1 = content.split( '0.1 Palette\n--------------------------------------------------------------*/\n' );
                        var split2 = split1[1].split( '\n\n/*--------------------------------------------------------------\n1.0 Reset' );
                        return split2[0];
                    }
                }
            }, 
            light : {
                src: 'css/palettes/_light.css', 
                dest: 'css/palettes/light.css',
                options: {
                    process: function(content, path) {
                        var split1 = content.split( '0.1 Palette\n--------------------------------------------------------------*/\n' );
                        var split2 = split1[1].split( '\n\n/*--------------------------------------------------------------\n1.0 Reset' );
                        return split2[0];
                    }
                }
            },
            dark : {
                src: 'css/palettes/_dark.css', 
                dest: 'css/palettes/dark.css',
                options: {
                    process: function(content, path) {
                        var split1 = content.split( '0.1 Palette\n--------------------------------------------------------------*/\n' );
                        var split2 = split1[1].split( '\n\n/*--------------------------------------------------------------\n1.0 Reset' );
                        return split2[0];
                    }
                }
            },
            franklin : {
                src: 'css/palettes/_franklin.css', 
                dest: 'css/palettes/franklin.css',
                options: {
                    process: function(content, path) {
                        var split1 = content.split( '0.1 Palette\n--------------------------------------------------------------*/\n' );
                        var split2 = split1[1].split( '\n\n/*--------------------------------------------------------------\n1.0 Reset' );
                        return split2[0];
                    }
                }
            },
            custom : {
                src: 'css/palettes/_custom.css', 
                dest: 'css/palettes/custom.css',
                options: {
                    process: function(content, path) {
                        var split1 = content.split( '0.1 Palette\n--------------------------------------------------------------*/\n' );
                        var split2 = split1[1].split( '\n\n/*--------------------------------------------------------------\n1.0 Reset' );
                        return split2[0];
                    }
                }
            }
        },

        // Clean up build directory
        clean: {
            main: ['build/<%= pkg.name %>']
        },

        //Compress build directory into <name>.zip and <name>-<version>.zip
        compress: {
            main: {
                options: {
                    mode: 'zip',
                    archive: './build/<%= pkg.name %>-<%= pkg.version %>.zip'
                },
                expand: true,
                cwd: 'build/<%= pkg.name %>/',
                src: ['**/*'],
                dest: '<%= pkg.name %>/'
            }
        },
    });
 
    // register task
    grunt.registerTask('default', ['watch:sass', 'watch:concat', 'watch:uglify']);
    
    grunt.registerTask('watchEditor', ['watch:editor']);

    grunt.registerTask('buildPalettes', ['sass:palettes', 'copy:classic', 'copy:light', 'copy:dark', 'copy:franklin', 'copy:custom']);
    
    grunt.registerTask('build', ['sass:dist', 'jshint', 'makepot', 'clean', 'copy:main', 'compress' ]);
};