/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.controller('SearchController', SearchController);

				function SearchController($scope, SolrFactory) {

								$scope.settings = SolrFactory.getSettings();
								$scope.facets = SolrFactory.getFacets();
								$scope.filterQueries = SolrFactory.getFilterQueries();
								$scope.search = '';

								$scope.rmFilterQuery = function (name, value) {
												SolrFactory.rmFilterQuery(name, value);
												update();
								};

								$scope.addFilterQuery = function(name, value) {
												SolrFactory.addFilterQuery(name, value);
												update();
								};

								$scope.update = function() {
												update();
								};

								$scope.autocomplete = function(search) {
												SolrFactory.getAutocomplete(search, 'search').then(function(data) {
																console.log(data.data);
												});
								};

								update();

								function update() {
												if ($scope.search !== '') {
																SolrFactory.setSetting('q', $scope.search);
												}
												SolrFactory.getData().then(function(data) {
																$scope.data = data.data;
												});
								};

				}
}());
