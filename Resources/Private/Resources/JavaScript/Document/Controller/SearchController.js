/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('SearchController', SearchController);

				function SearchController($scope, Solr) {

								$scope.settings = Solr.getSettings();
								$scope.facets = Solr.getFacets();
								$scope.filterQueries = Solr.getFilterQueries();
								$scope.search = '';

								$scope.pageChanged = function(pageNumber) {
												//Solr.setSetting('start', ((pageNumber - 1) * $scope.settings.rows).toString());
								//				console.log(pageNumber);
												//$scope.settings.start = newPageNumber;
												//update();
								};

								$scope.rmFilterQuery = function (name, value) {
												Solr.rmFilterQuery(name, value);
												update();
								};

								$scope.addFilterQuery = function(name, value) {
												Solr.addFilterQuery(name, value);
												update();
								};

								$scope.update = function(search) {
												update(search);
								};

								update();

								function update(search) {
												if (search !== undefined) {
																if (search !== '') {
																				Solr.setSetting('q', search);
																} else {
																				Solr.setSetting('q', '*:*');
																}
												}
												Solr.getData().then(function(data) {
																$scope.data = data.data;
																$scope.docs = data.data.response.docs;
																$scope.total = data.data.response.numFound;
												});
								};

				}
}());
