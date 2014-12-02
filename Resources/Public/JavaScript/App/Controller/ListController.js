/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ListController', ListController);

				function ListController($scope, SolrFactory, DocumentFactory) {
								SolrFactory.getData().then(function(data) {
												var docs = data.data.response.docs;
												/*
												if (DocumentFactory.getJson() !== angular.toJson(data.data.response.docs)) {				
																DocumentFactory.setJson(angular.toJson(docs));
																DocumentFactory.setDocs(docs);
												}
												*/
												//$scope.collection = DocumentFactory.getDocs();
												$scope.collection = docs;
								});
				}
}());
