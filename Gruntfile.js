module.exports = function(grunt) {

	require('load-grunt-tasks')(grunt);
	require('time-grunt')(grunt);

	grunt.initConfig({

		project: {
			dev: '',
			dist: '',
			styles: {
				src: 'assets/css/src/',
				dest: 'assets/css/',
			},
			images: {
				src: 'public/img/',
				dest: 'public/img/',
			},
			scripts: {
				src: 'public/js/src/',
				dest: 'public/js/',
			}
		},

		watch: {
			options: {
				livereload: true,
			},
			styles: {
				files: ['<%= project.dev %><%= project.styles.src %>**/*.{scss,sass}'],
				tasks: ['sass'],
			}
		},

		sass: {
			dist: {
				files: [{
					expand: true,
					cwd: '<%= project.dev %><%= project.styles.src %>',
					src: ['*.scss'],
					dest: '<%= project.dev %><%= project.styles.dest %>',
					ext: '.css'
				}]
			}
		},

		cssmin: {
			options: {
				keepSpecialComments: 0
			},
			minify: {
				files: {
					'<%= project.dist %><%= project.styles.dest %>global.css': [
						'<%= project.dist %><%= project.styles.dest %>global.css'
					],
				}
			}
		},

		uncss: {
			options: {
				ignore: [/(state--)/g],
				stylesheets: [
					'<%= project.styles.dest %>global.css'
				],
			},
			dist: {
				files: {
					'<%= project.dev %><%= project.styles.dest %>global.css': [
						'<%= project.dev %>/**/*.{php,html,twig,svg}',
						'!**/bower_components/**',
						'!**/lib/**',
						'!**/public/**',
						'!**/vendor/**',
					]
				}
			}
		},

		imagemin: {
			dynamic: {
				options: {
					optimizationLevel: 7,
				},
				files: [{
					expand: true,
					cwd: '<%= project.dev %><%= project.images.src %>',
					dest: '<%= project.dist %><%= project.images.dest %>',
					src: [
						'**/*.{png,jpg,gif}',
						'!**/ai-cache/**',
						'!**/bower_components/**',
					],
				}]
			}
		},

		copy: {
			dist: {
				files: [{
					expand: true,
					dot: true,
					cwd: '<%= project.dev %>',
					dest: '<%= project.dist %>',
					src: [
						'**/*.{php,html,csv,json,twig,js,gif,jpeg,jpg,png,svg,webp,htaccess}',
						'!**/bower_components/**',
						'!**/uncss-hacks.twig',
						'!**/ai-cache/**',
					]
				}]
			},
		},

		clean: {
			dist: {
				files: [{
					dot: true,
					src: [
						'.tmp',
						'<%= project.dist %>/*',
						'!<%= project.dist %>/.git*'
					]
				}]
			},
		},

		useminPrepare: {
			options: {
				dest: '<%= project.dist %>'
			},
			html: [
				'<%= project.dev %>/**/*.{html,php,twig}',
				'<%= project.dev %>*.{html,php,twig}',
				'!**/bower_components/**',
				'!**/vendor/**'
			]
		},

		usemin: {
			// options: {
			// 	assetsDirs: ['<%= project.dev %>']
			// },
			html: [
				'<%= project.dist %>/**/*.{html,php,twig}',
				'!**/vendor/**'
			]
		},

		pixrem: {
			options: {
	      rootvalue: '16px',
	      replace: 'true'
	    },
	    dist: {
	      src: '<%= project.dist %><%= project.styles.dest %>global.css',
	      dest: '<%= project.dist %><%= project.styles.dest %>global--no-rems.css'
	    }
	  },

	  replace: {
      mobile: {
        options: {
          patterns: [
            {
              match: /(@media([\s\S]*?}[\s]*}))/g,
              replacement: function() {
              	return '';
              }
            }
          ]
        },
        files: [
          {
          	src: ['<%= project.dev %><%= project.styles.dest %>global.css'],
          	dest: '<%= project.dev %><%= project.styles.dest %>global--no-queries.css'
          }
        ]
      },
      dist: {
        options: {
          patterns: [
            {
              match: /(@media([\s\S]*?}[\s]*}))/g,
              replacement: function() {
              	return '';
              }
            }
          ]
        },
        files: [
          {
          	src: ['<%= project.dist %><%= project.styles.dest %>global.css'],
          	dest: '<%= project.dist %><%= project.styles.dest %>global--no-queries.css'
          },
        ]
      },
      root: {
        options: {
          patterns: [
            {
              match: /\/public/g,
              replacement: function() {
              	return '{{ROOT}}public';
              }
            }
          ]
        },
        files: [
          {
		      	expand: true,
		      	// flatten: true,
          	src: ['<%= project.dist %>templates/**/*.twig'],
          	// dest: '<%= project.dist %>templates/**/*.twig'
          },
        ]
      },
      lastGruntBuild: {
        options: {
          patterns: [
            {
              match: /last-grunt-build">(.*?)<\//,
              replacement: function(){
              	return 'last-grunt-build">' + Date.now() + '</';
              }
            }
          ]
        },
        files: [
          {
          	src: ['<%= project.dev %>templates/inc/footer.twig'],
          	dest: '<%= project.dev %>templates/inc/footer.twig'
          }
        ]
      }
    }
});

grunt.registerTask('default',[
	'watch',
]);

grunt.registerTask('build--fast',[
	'clean:dist',
	'copy:dist',
	'sass',
	'pixrem',
	'replace:dist',
	'replace:lastGruntBuild',
]);

grunt.registerTask('build',[
	'clean:dist',
	'copy:dist',
	'sass',
	'uncss',
	'useminPrepare',
  'concat:generated',
  'cssmin:generated',
  'uglify:generated',
	'usemin',
	'pixrem',
	'imagemin',
	'replace:dist',
	'replace:lastGruntBuild',
	'replace:root',
]);
};