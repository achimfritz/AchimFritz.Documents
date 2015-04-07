/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.controller('DocumentListController', DocumentListController);

				function DocumentListController($scope, Solr, ClipboardFactory, ngDialog ) {
/*
								var settings = SettingsFactory.getSettings();

								$scope.collection = [];
								$scope.total = 0;
								$scope.currentPage = (settings['start']/settings['rows']) + 1;
								$scope.itemsPerPage = settings['rows'];

								$scope.pageChanged = function(pageNumber) {
												var settings = SettingsFactory.getSettings();
												SettingsFactory.setSetting('start', (pageNumber - 1) * settings.rows);
												SolrFactory.getData().then(function(data) {
																$scope.total = data.data.response.numFound;
																$scope.collection = data.data.response.docs;
																ClipboardFactory.setSolrDocs(data.data.response.docs);
												});
								};

								SolrFactory.getData().then(function(data) {
												$scope.total = data.data.response.numFound;
												$scope.collection = data.data.response.docs;
												ClipboardFactory.setSolrDocs(data.data.response.docs);
								});
								*/
				}
}());
