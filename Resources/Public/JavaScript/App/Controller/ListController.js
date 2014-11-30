/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ListController', ListController);

				function ListController($scope, SolrFactory) {
								var currentCollection = '';
								SolrFactory.getData().then(function(data) {
								//console.log('foo');
								//console.log(data);
																if (currentCollection != JSON.stringify(data.data.response.docs)) {
																				currentCollection = JSON.stringify(data.data.response.docs);
																				$scope.collection = data.data.response.docs;
																}
								});
				}
}());
