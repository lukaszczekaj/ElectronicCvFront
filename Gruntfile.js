module.exports = function(grunt) {
    grunt.initConfig({
        browserSync: {
            dev: {
                bsFiles: {
                    src : [
                        'public/dist/css/*.css',
                        'dev/view/*.html',
                        'public/js/*.js'
                    ]
                },
                options: {
                    watchTask: true,
                    server: './'
                }
            },
            lh: {
                bsFiles: {
                    src : [
                        'public/dist/css/*.css',
                        'dev/view/*.html',
                        'public/js/*.js'
                    ]
                },
                options: {
                    server: './'
                }
            }
        },
        postcss: {
            options: {
                map: true,
                processors: [
                    require('autoprefixer')({
                        browsers: ['last 20 versions', 'ie 7-11']
                    })
                ]
            },
            dist: {
                src: 'public/dist/css/*.css'
            }
        },
        sass: {
            options: {
                sourceMap: true,
                outputStyle: 'compressed'
            },
            dist: {
                files: {
                    'public/dist/css/main.min.css': 'dev/scss/main.scss'
                }
            }
        },
        watch: {
            files: 'dev/scss/**/*.scss',
            tasks: ['sass', 'postcss:dist']
        }
    });
    
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-sass');
 
    grunt.registerTask('default', ['browserSync','watch']);
    grunt.registerTask('rebuild-css', ['sass','postcss']);
    
}; 

