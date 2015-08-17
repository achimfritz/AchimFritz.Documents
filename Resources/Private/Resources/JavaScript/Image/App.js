/* global angular */

(function () {
    'use strict';
    angular.module('imageApp', ['solr', 'app', 'ngRoute', 'angularUtils.directives.dirPagination', 'ngDialog', 'ngAnimate', 'toaster', 'ngDraggable'])
        .config(paginationConfiguration)
        .config(routeConfiguration)
        .config(toasterConfiguration)
        .config(appConfiguration)
        .config(solrConfiguration);

    /* @ngInject */
    function toasterConfiguration(toasterConfig) {
        var customConfig = {
            'position-class': 'toast-bottom-right',
            'time-out': 5000,
            'close-button': true
        };
        angular.extend(toasterConfig, customConfig);
    };

    /* @ngInject */
    function appConfiguration(AppConfigurationProvider) {
        AppConfigurationProvider.setSetting('documentListResource', 'imagedocumentlist');
        AppConfigurationProvider.setSetting('documentListMergeResource', 'imagedocumentlistmerge');
        AppConfigurationProvider.setSetting('documentListRemoveResource', 'imagedocumentlistremove');
    };

    /* @ngInject */
    function paginationConfiguration(paginationTemplateProvider) {
        paginationTemplateProvider.setPath('/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/dirPagination.tpl.html');
    };

    function solrConfiguration(SolrProvider) {
        SolrProvider.setFacets(['hCategories', 'hPaths', 'hLocations', 'year', 'tags', 'parties', 'mainDirectoryName', 'collections']);
        SolrProvider.setHFacets({
            'hPaths': '0',
            'hCategories': '1/categories',
            'hLocations': '1/locations'
        });
        SolrProvider.setSolrSetting('servlet', 'image');
        SolrProvider.setSetting('rows', 10);
        SolrProvider.setSetting('facet_limit', 10);
        var solrSettingsDiv = jQuery('#solrSettings');
        if (solrSettingsDiv.length) {
            var solrSettings = solrSettingsDiv.data('solrsettings');
            SolrProvider.setSolrSetting('servlet', solrSettings.servlet);
            SolrProvider.setSolrSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
        }
    };

    /* @ngInject */
    function routeConfiguration($routeProvider) {
        var templatePath = '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/Image/Templates/';
        $routeProvider.
            when('/', {
                templateUrl: templatePath + 'Index.html',
                controller: 'IndexController'
            }).
            when('/index', {
                templateUrl: templatePath + 'Index.html',
                controller: 'IndexController'
            }).
            when('/filter', {
                templateUrl: templatePath + 'Filter.html',
                controller: 'FilterController'
            }).
            when('/clipboard', {
                templateUrl: templatePath + 'Clipboard.html',
                controller: 'ClipboardController'
            }).
            when('/solrIntegrity', {
                templateUrl: templatePath + 'SolrIntegrity.html',
                controller: 'SolrIntegrityController'
            }).
            when('/integrity', {
                templateUrl: templatePath + 'Integrity.html',
                controller: 'IntegrityController'
            }).
            when('/documentList', {
                templateUrl: templatePath + 'DocumentList.html',
                controller: 'DocumentListController'
            }).
            otherwise({
                redirectTo: '/'
            });
    }

}());
