module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sass: {
			dist: {
        options: {
          loadPath: 'node_modules/foundation-sites/scss'
        },
				files: {
					'css/main.css' : 'scss/main.scss'
				}
			}
		},
		watch: {
			css: {
				files: 'scss/*.scss',
				tasks: ['sass']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default',['watch']);
};
