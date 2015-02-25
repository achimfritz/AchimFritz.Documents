/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.controller('SearchController', SearchController);

				function SearchController($scope) {

								$scope.filterQueries = {
												'x': ['b', 'c']
								};
								$scope.search = 'aa';

				}
}());
