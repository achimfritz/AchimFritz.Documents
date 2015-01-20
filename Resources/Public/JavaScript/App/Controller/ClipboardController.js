/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ClipboardController', ClipboardController);

				function ClipboardController($scope, ClipboardFactory, RestService, ExportRestService, SettingsFactory, FlashMessageService) {
								var settings = SettingsFactory.getSettings();

								$scope.collection = [];
								$scope.total = 0;
								$scope.itemsPerPage = settings['rows'];
								$scope.currentPage = 1;
								ClipboardFactory.setCurrentPage(1);
								$scope.collection = ClipboardFactory.getDocs();

								$scope.finished = true;
								$scope.category = '';

								$scope.pdf = {
												'dpi': 300,
												'columns': 3,
												'size': 4
								};

								$scope.pdf = ExportRestService.pdf();
								$scope.zip = ExportRestService.zip();

								$scope.pageChanged = function(newPageNumber) {
												ClipboardFactory.setCurrentPage(newPageNumber);
												$scope.collection = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};

								$scope.pdfDownload = function() {
								};

								$scope.zipDownload = function() {
												$scope.finished = false;
												ExportRestService.zipDownload($scope.zip, $scope.collection).then(function(data) {
																$scope.finished = true;
																var blob = new Blob([data.data], {
																				type: 'application/zip'
																});
																saveAs(blob, $scope.zip.name + '.zip');
												}, function(data) {
																$scope.finished = true;
																FlashMessageService.error(data);
												});
								};

								$scope.merge = function() {
												$scope.finished = false;
												RestService.merge($scope.category, $scope.collection).then(function(data) {
																$scope.finished = true;
																FlashMessageService.show(data.data.flashMessages);
												});
								};

								$scope.remove = function() {
												$scope.finished = false;
												RestService.remove($scope.category, $scope.collection).then(function(data) {
																$scope.finished = true;
																FlashMessageService.show(data.data.flashMessages);
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
