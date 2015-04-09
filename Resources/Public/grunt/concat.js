/*jslint node: true */
/*global module*/
'use strict';
module.exports = {
    options: {
        separator: '\r\n'
    },
    documentApp: {
        src: [
            'App/JavaScript/Solr/Module.js',
            'App/JavaScript/Solr/**/*.js',
            'App/JavaScript/Document/App.js',
            'App/JavaScript/Document/**/*.js',
        ],
        dest: 'Build/Document.js'
    },
    imageApp: {
        src: [
            'App/JavaScript/Solr/Module.js',
            'App/JavaScript/Solr/**/*.js',
            'App/JavaScript/Image/App.js',
            'App/JavaScript/Image/**/*.js',
        ],
        dest: 'Build/Image.js'
    },
    mp3App: {
        src: [
            'App/JavaScript/Solr/Module.js',
            'App/JavaScript/Solr/**/*.js',
            'App/JavaScript/Mp3/App.js',
            'App/JavaScript/Mp3/**/*.js',
        ],
        dest: 'Build/Mp3.js'
    },
    libs: {
        src: [
            'bower_components/jquery/dist/jquery.js',
            'bower_components/jquery-ui/jquery-ui.js',
            'bower_components/bootstrap/js/tooltip.js',
            'bower_components/bootstrap/js/modal.js',
            'bower_components/bootstrap/js/tab.js',
            'bower_components/angular/angular.js',
            'bower_components/angular-route/angular-route.js',
            'bower_components/angular-animate/angular-animate.js',
            'bower_components/ngDialog/js/ngDialog.js',
            'bower_components/ngDraggable/ngDraggable.js',
            'bower_components/file-saver/FileSaver.js',
												'bower_components/angular-utils-pagination/dirPagination.js',
												'bower_components/ajax-solr/core/Core.js',
												'bower_components/ajax-solr/core/AbstractManager.js',
												'bower_components/ajax-solr/core/Parameter.js',
												'bower_components/ajax-solr/core/ParameterStore.js',
            'bower_components/AngularJS-Toaster/toaster.js',
												'bower_components/isotope/dist/isotope.pkgd.js'
        ],
        dest: 'Build/Libs.js'
    },
    csslibs: {
        src: [
            'bower_components/jquery-ui/themes/base/jquery-ui.css',
            'bower_components/bootstrap/dist/css/bootstrap.css',
												'bower_components/ngDialog/css/ngDialog.css',
            'bower_components/AngularJS-Toaster/toaster.css',
												'bower_components/ngDialog/css/ngDialog-theme-default.css'
        ],
        dest: 'Build/Libs.css'
    },
    css: {
        src: [
            'App/Css/**/*.css'
        ],
        dest: 'Build/Main.css'
    }
};
