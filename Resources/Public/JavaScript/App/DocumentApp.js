/* global angular */

(function () {
    'use strict';
				angular.module('documentApp', ['ngRoute'])
				.config(routeConfiguration);

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
            when('/navigation', {
                templateUrl: templatePath + 'Navigation.html',
                controller: 'NavigationController'
            }).
												otherwise({
                redirectTo: '/'
            });
				}
}());
