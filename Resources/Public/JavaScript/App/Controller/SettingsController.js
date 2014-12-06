/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('SettingsController', SettingsController);

				function SettingsController($scope, SolrFactory) {

								$scope.settings = {
												'rows': SolrFactory.getByValue('rows'),
												'facetLimit': SolrFactory.getByValue('facet.limit')
								};

								$scope.update = function() {
												//SolrFactory
												SolrFactory.addByValue('rows', $scope.settings.rows);
												SolrFactory.addByValue('facet.limit', $scope.settings.facetLimit);
								}

				}
}());
