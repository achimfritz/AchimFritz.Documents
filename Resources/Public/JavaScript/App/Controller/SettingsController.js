/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('SettingsController', SettingsController);

				function SettingsController($scope, SolrFactory) {

								//$scope.settings = {rows: 10};

								$scope.update = function() {
												//SolrFactory
												SolrFactory.addByValue('rows', $scope.settings.rows);
												//console.log($scope.settings.rows);
												SolrFactory.resetApiData();
								}


								//$scope.settings = {};

				}
}());
