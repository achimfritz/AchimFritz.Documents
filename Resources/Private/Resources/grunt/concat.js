/*jslint node: true */
/*global module*/
'use strict';
module.exports = {
    options: {
        separator: '\r\n'
    },
    documentApp: {
        src: [
            'JavaScript/Solr/Module.js',
            'JavaScript/Solr/**/*.js',
            'JavaScript/Document/App.js',
            'JavaScript/Document/**/*.js',
        ],
        dest: '../../Public/Build/Document.js'
    },
    imageApp: {
        src: [
            'JavaScript/Solr/Module.js',
            'JavaScript/Solr/**/*.js',
            'JavaScript/Image/App.js',
            'JavaScript/Image/**/*.js',
        ],
        dest: '../../Public/Build/Image.js'
    },
    mp3App: {
        src: [
            'JavaScript/Solr/Module.js',
            'JavaScript/Solr/**/*.js',
            'JavaScript/Mp3/App.js',
            'JavaScript/Mp3/**/*.js',
        ],
        dest: '../../Public/Build/Mp3.js'
    },
    libs: {
        src: [
            'bower_components/jquery/dist/jquery.js',
            'bower_components/jquery-ui/jquery-ui.js',
            'bower_components/bootstrap/js/tooltip.js',
            'bower_components/bootstrap/js/modal.js',
            'bower_components/bootstrap/js/tab.js',
            'bower_components/bootstrap/js/dropdown.js',
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
            'bower_components/angular-soundmanager2/src/modules/soundmanager2.js',
            'bower_components/angular-soundmanager2/src/*.js',
												'bower_components/isotope/dist/isotope.pkgd.js'
        ],
        dest: '../../Public/Build/Libs.js'
    },
    csslibs: {
        src: [
            'bower_components/jquery-ui/themes/base/jquery-ui.css',
            'bower_components/bootstrap/dist/css/bootstrap.css',
												'bower_components/ngDialog/css/ngDialog.css',
            'bower_components/AngularJS-Toaster/toaster.css',
												'bower_components/ngDialog/css/ngDialog-theme-default.css'
        ],
        dest: '../../Public/Build/Libs.css'
    },
    css: {
        src: [
            'Css/**/*.css'
        ],
        dest: '../../Public/Build/Main.css'
    }
};
