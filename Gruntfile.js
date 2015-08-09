/* globals module */

module.exports = function (grunt) {


	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		//
		// Pre-processes Sass files with compression
		//

		sass: {
			options: {
				// TODO: Turn this on before launch
				// outputStyle: 'compressed',
				sourceMap  : true
			},
			dist: {
				files: {
					'assets/css/main.css' : 'assets/css/sass/main.sass',
					'assets/css/admin.css': 'assets/css/sass/admin.sass',
					'assets/css/login.css': 'assets/css/sass/login.sass',
					'assets/css/editor-styles.css': 'assets/css/sass/editor-styles.sass',
					'emails/email.css': 'emails/email.sass'
				}
			}
		},


		//
		// Combines and minifies JS files

		browserify: {
			dist: {
				files: {
					'assets/js/main.js': 'assets/js/src/main.js',
					'assets/js/admin.js': 'assets/js/src/admin.js'
				},
				options: {
					// TODO: Turn this on before launch
					//transform: ['uglifyify'],
					alias: {
						'pikaday': './assets/js/src/libs/pikaday.js',
						'remodal': './assets/js/src/libs/remodal.js'
					},
					ignore: ['moment']
				}
			}
		},

		//
		// Automates inlining CSS for HTML emails
		//

		inlinecss: {
			main: {
				options: {},
				files  : {
					'emails/admin-contact-notice.html': 'emails/admin-contact-notice-BUILD.html'
				}
			}
		},


		//
		// Watch certain file types for changes, take action
		//

		watch: {
			styles: {
				files: [
					'assets/css/sass/**/*.sass',
					'assets/css/sass/**/*.scss',
					'emails/**/*.sass'
				],
				tasks: ['sass']
			},
			scripts: {
				files: [
					'assets/js/src/**/*.js'
				],
				tasks: ['browserify']
			},
			emails: {
				files: [
					'emails/**/*-BUILD.html'
				],
				tasks: ['inlinecss']
			},
			grunt: {
				files: [
					'Gruntfile.js'
				],
				tasks: ['sass', 'browserify', 'inlinecss']
			}
		}
	});

	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-browserify');
	grunt.loadNpmTasks('grunt-inline-css');

	grunt.registerTask('default');

};