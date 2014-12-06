/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('NavigationController', NavigationController);

				function NavigationController($scope, SolrFactory, FacetFactory) {
								SolrFactory.buildSolrValues();

								$scope.facets = FacetFactory.getFacets();
								$scope.filterQueries = FacetFactory.getFilterQueries();

								SolrFactory.getData().then(function(data) {
												$scope.results = data.data;
								});

								$scope.rmFq = function (value) {
												FacetFactory.rmFq(value);
												SolrFactory.buildSolrValues();
												//console.log(value);
												SolrFactory.getData().then(function(data) {
																$scope.results = data.data;
																$scope.filterQueries = FacetFactory.getFilterQueries();
												});
								};

								$scope.addFacet = function(facetName, facetValue) {
												FacetFactory.addFilterQuery(facetName, facetValue);
												SolrFactory.buildSolrValues();
												SolrFactory.getData().then(function(data) {
																$scope.results = data.data;
												});

								}
				}
}());
