/*jslint node: true */
/*global module*/
'use strict';
module.exports = {
    options: {
        separator: '\r\n'
    },
    app: {
        src: [
												// TODO
            'JavaScript/SolrApp/App.js',
            // and after that the rest
            'JavaScript/SolrApp/**/*.js',
        ],
        dest: 'JavaScript/Main.js'
    },
    libs: {
        src: [
            'bower_components/jquery/dist/jquery.js',
            'bower_components/jquery-ui/ui/core.js',
            'bower_components/jquery-ui/ui/widget.js',
            'bower_components/jquery-ui/ui/autocomplete.js',
            'bower_components/bootstrap/js/tooltip.js',
            'bower_components/bootstrap/js/modal.js',
            'bower_components/bootstrap/js/tab.js',
            'bower_components/angular/angular.js',
            'bower_components/angular-route/angular-route.js',
            'bower_components/angular-animate/angular-animate.js',
            'bower_components/ngDialog/js/ngDialog.js',
            'bower_components/ngDraggable/js/ngDraggable.js',
            'bower_components/file-saver/FileSaver.js',
												'bower_components/angular-utils-pagination/dirPagination.js',
												'bower_components/ajax-solr/core/Core.js',
												'bower_components/ajax-solr/core/AbstractManager.js',
												'bower_components/ajax-solr/core/Parameter.js',
												'bower_components/ajax-solr/core/ParameterStore.js',
					//							'bower_components/isotope/js/isotope.js',
            'bower_components/AngularJS-Toaster/toaster.js'
        ],
        dest: 'JavaScript/Libs.js'
    },
    css: {
        src: [
            'bower_components/bootstrap/dist/css/bootstrap.css'
        ],
        dest: 'Css/Main.css'
    }
};
