/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ClipboardController', ClipboardController);

				function ClipboardController($scope, ClipboardFactory, RestService, SettingsFactory) {
								var settings = SettingsFactory.getSettings();

								$scope.collection = [];
								$scope.total = 0;
								$scope.itemsPerPage = settings['rows'];
								$scope.currentPage = 1;
								ClipboardFactory.setCurrentPage(1);
								$scope.collection = ClipboardFactory.getDocs();

								$scope.category = '';

								$scope.pageChanged = function(newPageNumber) {
												ClipboardFactory.setCurrentPage(newPageNumber);
												$scope.collection = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};


								$scope.merge = function() {
												RestService.merge($scope.category, $scope.collection).then(function(data) {
																console.log(data);
												});
								};

								$scope.remove = function() {
												RestService.remove($scope.category, $scope.collection).then(function(data) {
																console.log(data);
												});
								};

								$scope.transferAll = function() {
												ClipboardFactory.transferAll();
												$scope.collection = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};
								$scope.empty = function() {
												ClipboardFactory.empty();
												$scope.collection = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};
								$scope.deleteSelected = function() {
												ClipboardFactory.deleteSelected();
												$scope.collection = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};
				
								$scope.transferSelected = function() {
												ClipboardFactory.transferSelected();
												$scope.collection = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};
				}
}());
