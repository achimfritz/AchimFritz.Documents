/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('FacetController', FacetController);

				function FacetController($scope, SolrFactory, FacetFactory, SettingsFactory) {

								$scope.category = '';

								function update() {
												$scope.category = '';
												SolrFactory.getData().then(function(data) {
																var facets = {};
																angular.forEach(FacetFactory.getFacets(), function(val) {
																				facets[val] = data.data.facet_counts.facet_fields[val];
																});
																$scope.facets = facets;
												});
								};

								update();

								var settings = SettingsFactory.getSettings();
								$scope.query = settings.q;

								$scope.integrity = function(query) {
												$scope.category = '';
												$scope.query = query;
												SettingsFactory.setSetting('q', query);
												update();
								};

								$scope.filterQueries = FacetFactory.getFilterQueries();

								$scope.rmFilterQuery = function (name, value) {
												$scope.category = '';
												FacetFactory.rmFilterQuery(name, value);
												update();
								};

								$scope.addFacet = function(facetName, facetValue) {
												$scope.category = '';
												FacetFactory.addFilterQuery(facetName, facetValue);
												update();
								};

								$scope.editCategory = function(facetName, facetValue) {
												$scope.category = FacetFactory.category(facetName, facetValue);
								};

								$scope.deleteCategory = function(category) {
								};

								$scope.updateCategory = function(category) {
								};
				}
}());
