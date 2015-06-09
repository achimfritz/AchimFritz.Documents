/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.controller('SearchController', SearchController);

				function SearchController($scope) {

								$scope.tag = '';
								$scope.write = function() {
												console.log($scope.tag);
								};

				}
}());
