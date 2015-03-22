/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.controller('IndexController', IndexController);

				function IndexController($scope, Solr) {

								$scope.settings = Solr.getSettings();
								$scope.facets = Solr.getFacets();
								$scope.filterQueries = Solr.getFilterQueries();
								$scope.search = '';
/*
															$scope.pageChanged = function(pageNumber) {
																console.log(pageNumber);
															};
															*/

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
																//$scope.total = data.data.response.numFound;
												});
								};

				}
}());
