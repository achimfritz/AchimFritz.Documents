/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('SettingsController', SettingsController);

				function SettingsController($scope, SettingsFactory, SolrFactory) {
								$scope.settings = SettingsFactory.getSettings();
								$scope.change = function() {
												SolrFactory.buildSolrValues();
								};
				}
}());
