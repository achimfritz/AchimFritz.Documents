/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('SettingsController', SettingsController);

				function SettingsController($scope, SettingsFactory) {
								$scope.settings = SettingsFactory.getSettings();
				}
}());
