/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ListController', ListController);

				function ListController($scope, SolrFactory, DocumentFactory) {

								$scope.collection = [];
								$scope.total = 0;
								//$scope.itemsPerPage = 1;
								$scope.itemsPerPage = SolrFactory.getByValue('rows');


								$scope.pageChanged = function(newPage) {
												console.log('foo');
												getResultsPage(newPage);
								};

								function getResultsPage(pageNumber) {
												//var start = SolrFactory.getByValue('start');
												var rows = SolrFactory.getByValue('rows');
												//var newStart = 
												//SolrFactory.setByValue('start', pageNumber);
												SolrFactory.setByValue('start', pageNumber * rows);
												SolrFactory.resetApiData();
												SolrFactory.getData().then(function(data) {
																$scope.total = data.data.response.numFound;
																$scope.collection = data.data.response.docs;
												});
								};

								SolrFactory.getData().then(function(data) {
												$scope.total = data.data.response.numFound;
												$scope.collection = data.data.response.docs;
								});
				}
}());
