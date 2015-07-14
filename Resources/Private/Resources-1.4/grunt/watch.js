/*jslint node: true */
/*global module*/
'use strict';
module.exports = {
    scripts: {
        files: [
            'JavaScript/**/*.js',
            'bower_components/angular-soundmanager2/src/*.js',
            'Css/**/*.css'
        ],
        tasks: ['concat' ],
        options: {
            nospawn: true
        }
    }
};
