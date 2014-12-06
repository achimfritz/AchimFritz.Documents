/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('NavigationController', NavigationController);

				function NavigationController($scope, SolrFactory) {
								SolrFactory.buildSolrValues();

								SolrFactory.getData().then(function(data) {
												$scope.results = data.data;
								});

								$scope.addFacet = function(facetName, facetValue) {
												SolrFactory.addByValue('fq', facetName + ':' + facetValue);
												//SolrFactory.addByValue('rows', 1);
												SolrFactory.getData().then(function(data) {
																$scope.results = data.data;
												});

								}
				}
}());
