/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ListController', ListController);

				function ListController($scope, SolrFactory ) {

								$scope.collection = [];
								$scope.total = 0;
								$scope.itemsPerPage = SolrFactory.getByValue('rows');

								$scope.pageChanged = function(newPageNumber) {
												getResultsPage(newPageNumber);
								};

								function getResultsPage(pageNumber) {
												//var start = SolrFactory.getByValue('start');
												var rows = SolrFactory.getByValue('rows');
												//var newStart = 
												console.log(pageNumber);
												SolrFactory.addByValue('start', pageNumber * rows);
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
