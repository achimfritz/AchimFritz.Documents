/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('FacetController', FacetController);

				function FacetController($scope, SolrFactory, FacetFactory, SettingsFactory, CategoryRestService, FlashMessageService) {

								$scope.renameCategory = null;
								$scope.finished = true;
								$scope.search = '';

								function update() {
												$scope.renameCategory = null;
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

								$scope.doSearch = function() {
												$scope.category = '';
												FacetFactory.addFilterQuery('search', $scope.search);
												update();
								};

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
												$scope.renameCategory = {
																'oldPath': FacetFactory.category(facetName, facetValue),
																'newPath': FacetFactory.category(facetName, facetValue)
												}
								};

								$scope.updateCategory = function(renameCategory) {
												$scope.finished = false;
												CategoryRestService.update(renameCategory).then(function(data) {
																$scope.finished = true;
																FlashMessageService.show(data.data.flashMessages);
																$scope.renameCategory = null;
												}, function(data) {
																$scope.finished = true;
																FlashMessageService.error(data);
												});
								};
				}
}());
