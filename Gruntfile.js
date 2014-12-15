module.exports = function(grunt) {
	grunt.initConfig({
		jshint: { // stops compiling when you write bad js.
			all: ['scripts/src/**/*.js']
		},
		concat: { //concatenates .js files into one.
			debug: {
				src: 'scripts/src/*.js',
				dest: 'scripts/site/global.js'
			}
		},
		sass: {
			dubug: {
				options: {
					style: 'expanded',
					require: 'susy',
					noCache: true
				},
				files: {
					'css/src/editor-styles.css': 'scss/editor-styles.scss',
					'css/src/style.css': ['scss/style.scss']
				}
			}
		},
		autoprefixer: {
			editor: {
				expand:true,
				flatten: true,
				src: 'css/src/editor-styles.css',
				dest: 'css/'
			},
			base: {
				options: {
					map:true
				},
				expand:true,
				flatten: true,
				src: 'css/src/style.css',
				dest: '.'
			}
		},
		cmq: { //combines media queries
			debug: {
				'css/src/style.css': ['css/src/style.css']
			}
		},
		clean: {
			css_src: {
				src: ["css/src"]
			}			
		},			
		watch: { //checks for specified changes, refreshes browser if plugin is installed
			options: { livereload: true},
			scripts: {
				files: 'scripts/src/*.js',
				tasks: ['js']
			},
			css: {
				files: 'scss/*.scss',
				tasks: ['css']
			},
			img: {
				files: 'images/src/**/*.{jpg,gif,png,svg}',
				tasks: ['img']
			},
			php: {
				files: '*.php',
				tasks: []
			}
		}
	});
	
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-sass')
	grunt.loadNpmTasks('grunt-combine-media-queries');
	grunt.loadNpmTasks('grunt-newer');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.registerTask('js', ['jshint', 'concat']);
	grunt.registerTask('css', ['sass', 'cmq', 'autoprefixer', 'clean']);
	grunt.registerTask('default', ['js', 'css']);	
}