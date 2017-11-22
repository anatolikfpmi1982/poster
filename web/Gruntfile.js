module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({
		//Clean up dir
		clean: {
			docs: ["docs/"]
		},

		bower: {
			install: {
				options: {
					targetDir: './vendor',
					layout: 'byType',
					install: true,
					verbose: false,
					cleanTargetDir: false,
					bowerOptions: {}
				}
			}
		},

		//Styles
    	less: {
			dev: {
				options: {
					paths: ["../css/"]
				},
				files: {
					"../css/index.css": "../css/index.less"
				}
			}
    	},

    	meta : {      
			basePath: './',
			srcPath: './',
			docsPath: './docs/'
		},

		//Project documentation
		pkg: grunt.file.readJSON('yuidoc.json'),

		yuidoc: {
			compile: {
				name: '<%= pkg.name %>',
				description: '<%= pkg.description %>',
				version: '<%= pkg.version %>',
				url: '<%= pkg.homepage %>',
				options: {
					syntaxtype: "js",
					extension: ".js",
					paths: '<%= meta.srcPath %>',
					outdir: '<%= meta.docsPath %>',
				}
			}
		}
	});

	// Load the plugin that provides the "grunt-bower-task" task.
	grunt.loadNpmTasks('grunt-bower-task');

	// Load the plugin that provides the "grunt-contrib-less" task.
	grunt.loadNpmTasks('grunt-contrib-less');

	// Load the plugin that provides the "grunt-contrib-yuidoc" task.
	grunt.loadNpmTasks('grunt-contrib-yuidoc');

	// Default task(s).
	grunt.registerTask('dev', ['less', 'bower']); //'yuidoc',
};