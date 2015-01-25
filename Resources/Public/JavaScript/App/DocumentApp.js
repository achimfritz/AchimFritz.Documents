/* global angular */

(function () {
    'use strict';
				angular.module('documentApp', ['ngRoute', 'angularUtils.directives.dirPagination', 'ngDialog', 'ngAnimate', 'toaster', 'ngDraggable'])
				.config(paginationConfiguration)
				.config(toasterConfiguration)
				.config(routeConfiguration);

				/* @ngInject */
				function toasterConfiguration(toasterConfig) {
								var customConfig = {
												'position-class': 'toast-bottom-right',
												'time-out': 5000,
												'close-button': true
								};
								angular.extend(toasterConfig, customConfig);
				}

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
            when('/clipboard', {
                templateUrl: templatePath + 'Clipboard.html',
                controller: 'ClipboardController'
            }).
            when('/facet', {
                templateUrl: templatePath + 'Facet.html',
                controller: 'FacetController'
            }).
            when('/integrity', {
                templateUrl: templatePath + 'Integrity.html',
                controller: 'IntegrityController'
            }).
            when('/documentlist', {
                templateUrl: templatePath + 'DocumentList.html',
                controller: 'DocumentListController'
            }).
												otherwise({
                redirectTo: '/'
            });
				}
}());
