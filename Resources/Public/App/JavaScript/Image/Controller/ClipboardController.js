/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.controller('ClipboardController', ClipboardController);

				function ClipboardController($scope, Solr, ClipboardFactory, DocumentCollectionRestService, ExportRestService, FlashMessageService) {

								var solrDocs = ClipboardFactory.getSolrDocs();

								if (solrDocs.length === 0) {
												Solr.getData().then(function(data) {
																solrDocs = data.data.response.docs;
																ClipboardFactory.setSolrDocs(solrDocs);
												});
								}

								$scope.finished = true;
								$scope.docs = ClipboardFactory.getDocs();
								$scope.total = ClipboardFactory.countDocs();

								$scope.category = '';
								$scope.form = 'category';

								$scope.showForm = function(form) {
												$scope.form = form;
								};

								$scope.pdf = {
												'dpi': 300,
												'columns': 3,
												'size': 4
								};

								$scope.pdf = ExportRestService.pdf();
								$scope.zip = ExportRestService.zip();

								$scope.pageChanged = function(newPageNumber) {
												ClipboardFactory.setCurrentPage(newPageNumber);
												$scope.docs = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};

								$scope.pdfDownload = function() {
												$scope.finished = false;
												ExportRestService.pdfDownload($scope.pdf, $scope.docs).then(function(data) {
																$scope.finished = true;
																var blob = new Blob([data.data], {
																				type: 'application/pdf'
																});
																saveAs(blob, 'out.pdf');
												}, function(data) {
																$scope.finished = true;
																FlashMessageService.error(data);
												});
								};

								$scope.zipDownload = function() {
												$scope.finished = false;
												ExportRestService.zipDownload($scope.zip, $scope.docs).then(function(data) {
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
												RestService.merge($scope.category, $scope.docs).then(function(data) {
																$scope.finished = true;
																FlashMessageService.show(data.data.flashMessages);
												});
								};

								$scope.remove = function() {
												$scope.finished = false;
												RestService.remove($scope.category, $scope.docs).then(function(data) {
																$scope.finished = true;
																FlashMessageService.show(data.data.flashMessages);
												});
								};

								$scope.transferAll = function() {
												ClipboardFactory.transferAll();
												$scope.docs = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};
								$scope.empty = function() {
												ClipboardFactory.empty();
												$scope.docs = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};
								$scope.deleteSelected = function() {
												ClipboardFactory.deleteSelected();
												$scope.docs = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};
				
								$scope.transferSelected = function() {
												ClipboardFactory.transferSelected();
												$scope.docs = ClipboardFactory.getDocs();
												$scope.total = ClipboardFactory.countDocs();
								};


				}
}());
