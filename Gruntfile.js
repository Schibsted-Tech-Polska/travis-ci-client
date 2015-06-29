'use strict';

module.exports = function (grunt) {
    grunt.initConfig({
        phpcs: {
            options: {
                bin: 'vendor/bin/phpcs',
                standard: 'PSR2'
            },
            application: {
                src: ['src/**/*.php']
            }
        },
        phpcpd: {
            application: {
                dir: 'src'
            },
            options: {
                bin: 'vendor/bin/phpcpd',
                quiet: true
            }
        },
        phpmd: {
            application: {
                dir: 'src'
            },
            options: {
                bin: 'vendor/bin/phpmd',
                reportFormat: 'text'
            }
        },
        phploc: {
            default: {
                dir: 'src'
            },
            options: {
                bin: 'vendor/bin/phploc',
                quiet: false
            }
        }
    });

    grunt.loadNpmTasks('grunt-phpcpd');
    grunt.loadNpmTasks('grunt-phpcs');
    grunt.loadNpmTasks('grunt-phpmd');
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-phploc');

    grunt.registerTask('default', ['quality']);
    grunt.registerTask('quality', ['phpcs', 'phpcpd', 'phpmd', 'phploc']);
};
