/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.controller('SearchController', SearchController);

				function SearchController($scope, SolrFactory) {

								$scope.settings = SolrFactory.getSettings();
								$scope.facets = SolrFactory.getFacets();

								$scope.rmFilterQuery = function (name, value) {
												SolrFactory.rmFilterQuery(name, value);
												update();
								};

								$scope.addFitler = function(name, value) {
												SolrFactory.addFilterQuery(name, value);
												update();
								};

								update();

								function update() {
												SolrFactory.getData().then(function(data) {
																$scope.response = data.data.response;
												});
												/*
												SolrFactory.getData().then(function(data) {
																				var facets = {};
																				angular.forEach(FacetFactory.getFacets(), function(val) {
																								facets[val] = data.data.facet_counts.facet_fields[val];
																								});
																				$scope.facets = facets;
																				});
																				*/
								};

				}
}());
