/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('NavigationController', NavigationController);

				function NavigationController($scope, SolrFactory, $timeout) {
								SolrFactory.getData().then(function(data) {
												$scope.results = data.data;
								});

								$scope.addFacet = function(facetName, facetValue) {
												//SolrFactory.addByValue('fq', facetName + ':' + facetValue);
												SolrFactory.addByValue('rows', 1);
												SolrFactory.resetApiData();
												SolrFactory.getData().then(function(data) {
																$scope.results = data.data;
																});

								}

								$timeout(function() {
								}, 1000);
				}
}());
