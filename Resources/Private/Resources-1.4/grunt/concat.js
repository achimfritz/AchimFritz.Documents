/*jslint node: true */
/*global module*/
'use strict';
module.exports = {
    options: {
        separator: '\r\n'
    },
    main: {
        src: [
            "JavaScript/Components/**/Module.js",
            "JavaScript/Components/**/*.js",
            "JavaScript/App.js"
        ],
        dest: '../../Public/Build-1.4/Main.js'
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
            'bower_components/angular-sanitize/angular-sanitize.js',
            'bower_components/angular-new-router/dist/router.es5.js',
            'bower_components/angular-animate/angular-animate.js',
            'bower_components/AngularJS-Toaster/toaster.js',
            'bower_components/file-saver/FileSaver.js',
            'bower_components/ajax-solr/core/Core.js',
            'bower_components/ajax-solr/core/AbstractManager.js',
            'bower_components/ajax-solr/core/Parameter.js',
            'bower_components/ajax-solr/core/ParameterStore.js',
            'bower_components/angular-soundmanager2/src/modules/soundmanager2.js',
            'bower_components/angular-soundmanager2/src/*.js',
            'bower_components/angular-xeditable/dist/js/xeditable.js'
        ],
        dest: '../../Public/Build-1.4/Libs.js'
    },
    csslibs: {
        src: [
            'bower_components/jquery-ui/themes/base/jquery-ui.css',
            'bower_components/bootstrap/dist/css/bootstrap.css',
            'bower_components/angular-xeditable/dist/css/xeditable.css'
        ],
        dest: '../../Public/Build-1.4/Libs.css'
    },
    css: {
        src: [
            'Css/**/*.css'
        ],
        dest: '../../Public/Build-1.4/Main.css'
    }
};
