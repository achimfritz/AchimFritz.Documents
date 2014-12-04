/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ClipboardController', ClipboardController);

				function ClipboardController($scope, ClipboardFactory, RestService) {
								$scope.collection = ClipboardFactory.getDocs();
								$scope.category = '';

								$scope.merge = function() {
												RestService.merge($scope.category, $scope.collection).then(function(data) {
																console.log(data);
												});
								};

								$scope.remove = function() {
												RestService.merge($scope.category, $scope.collection).then(function(data) {
																console.log(data);
												});
								};

								$scope.transferAll = function() {
												ClipboardFactory.transferAll();
												$scope.collection = ClipboardFactory.getDocs();
								};
								$scope.empty = function() {
												ClipboardFactory.empty();
												$scope.collection = ClipboardFactory.getDocs();
								};
								$scope.deleteSelected = function() {
												ClipboardFactory.deleteSelected();
												$scope.collection = ClipboardFactory.getDocs();
								};
				
								$scope.transferSelected = function() {
												ClipboardFactory.transferSelected();
												$scope.collection = ClipboardFactory.getDocs();
								};
				}
}());
