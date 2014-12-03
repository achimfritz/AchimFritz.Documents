/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ClipboardController', ClipboardController);

				function ClipboardController($scope, ClipboardFactory) {
								$scope.collection = ClipboardFactory.getDocs();

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
