/*jslint node: true */
/*global module*/
'use strict';
module.exports = {
    scripts: {
        files: [
            'App/JavaScript/**/*.js',
            'JavaScript/SolrApp/**/*.js',
            'JavaScript/ImageApp/**/*.js',
            'App/Css/**/*.css'
        ],
        tasks: ['concat', 'ngAnnotate' ],
        options: {
            nospawn: true
        }
    }
};
