/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('SolrSettingsController', SolrSettingsController);

				function SolrSettingsController($scope, SolrSettingsFactory) {
								$scope.settings = SolrSettingsFactory.getSettings();
				}
}());
