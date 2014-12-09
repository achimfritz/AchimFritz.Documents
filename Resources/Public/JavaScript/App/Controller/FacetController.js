/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('FacetController', FacetController);

				function FacetController($scope, SolrFactory, FacetFactory) {

								function update() {
												SolrFactory.buildSolrValues();
												SolrFactory.getData().then(function(data) {
																var facets = {};
																angular.forEach(FacetFactory.getFacets(), function(val) {
																				facets[val] = data.data.facet_counts.facet_fields[val];
																});
																$scope.facets = facets;
												});
								};

								update();

								$scope.filterQueries = FacetFactory.getFilterQueries();

								$scope.rmFilterQuery = function (name, value) {
												FacetFactory.rmFilterQuery(name, value);
												update();
								};

								$scope.addFacet = function(facetName, facetValue) {
												FacetFactory.addFilterQuery(facetName, facetValue);
												update();
								};
				}
}());
