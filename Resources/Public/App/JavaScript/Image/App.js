/* global angular */

(function () {
    'use strict';
				angular.module('imageApp', ['solr', 'ngRoute', 'angularUtils.directives.dirPagination', 'ngDialog', 'ngAnimate', 'toaster', 'ngDraggable'])
				.config(paginationConfiguration)
				.config(routeConfiguration)
				.config(toasterConfiguration)
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
				function paginationConfiguration(paginationTemplateProvider) {
								paginationTemplateProvider.setPath('/_Resources/Static/Packages/AchimFritz.Documents/bower_components/angular-utils-pagination/dirPagination.tpl.html');
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
				};

   /* @ngInject */
    function routeConfiguration($routeProvider) {
        var templatePath = '/_Resources/Static/Packages/AchimFritz.Documents/App/JavaScript/Image/Templates/';
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
