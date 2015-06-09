/* global angular */

(function () {
    'use strict';

				angular
				.module('mp3App')
				.controller('IndexController', IndexController);

				function IndexController($scope) {

								$scope.tag = '';
								$scope.write = function() {
												console.log($scope.tag);
								};

				}
}());
