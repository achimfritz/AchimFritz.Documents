/* global angular */

(function () {
    'use strict';
				angular.module('documentApp', ['ngRoute', 'angularUtils.directives.dirPagination'])
				.config(paginationConfiguration)
				.config(routeConfiguration);

				/* @ngInject */
				function paginationConfiguration(paginationTemplateProvider) {
								paginationTemplateProvider.setPath('/_Resources/Static/Packages/AchimFritz.Resources/Vendor/angular-utils-pagination/dirPagination.tpl.html');
				}

				/* @ngInject */
    function routeConfiguration($routeProvider) {
        var templatePath = '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Templates/';
        $routeProvider.
            when('/', {
                templateUrl: templatePath + 'List.html',
                controller: 'ListController'
            }).
            when('/list', {
                templateUrl: templatePath + 'List.html',
                controller: 'ListController'
            }).
            when('/settings', {
                templateUrl: templatePath + 'Settings.html',
                controller: 'SettingsController'
            }).
            when('/solrsettings', {
                templateUrl: templatePath + 'SolrSettings.html',
                controller: 'SolrSettingsController'
            }).
            when('/clipboard', {
                templateUrl: templatePath + 'Clipboard.html',
                controller: 'ClipboardController'
            }).
            when('/navigation', {
                templateUrl: templatePath + 'Navigation.html',
                controller: 'NavigationController'
            }).
												otherwise({
                redirectTo: '/'
            });
				}
}());
