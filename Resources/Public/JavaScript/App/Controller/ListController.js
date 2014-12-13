/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ListController', ListController);

				function ListController($scope, SolrFactory, SettingsFactory, ClipboardFactory, ngDialog ) {

								var settings = SettingsFactory.getSettings();

								$scope.collection = [];
								$scope.total = 0;
								$scope.itemsPerPage = settings['rows'];

								$scope.pageChanged = function(newPageNumber) {
												getResultsPage(newPageNumber);
								};

								function getResultsPage(pageNumber) {
												var settings = SettingsFactory.getSettings();
												SettingsFactory.setSetting('start', (pageNumber - 1) * settings.rows);
												SolrFactory.buildSolrValues();
												SolrFactory.getData().then(function(data) {
																$scope.total = data.data.response.numFound;
																$scope.collection = data.data.response.docs;
																ClipboardFactory.setSolrDocs(data.data.response.docs);
																$scope.current = $scope.collection[0];
												});
								};


								$scope.next = function() {
												console.log('foo');
												var newItem = {};
												var found = false;
												angular.forEach($scope.collection, function(val) {
																if (found === true) {
																				newItem = val;
																				found = false;
																}
																if (val.identifier === $scope.current.identifier) {
																				found = true;
																}
												});
												$scope.current = newItem;
												ngDialog.close();
												ngDialog.open({
																template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																scope: $scope
												});
								};

								function openDialog() {
								};


								SolrFactory.getData().then(function(data) {
												$scope.total = data.data.response.numFound;
												$scope.collection = data.data.response.docs;
												ClipboardFactory.setSolrDocs(data.data.response.docs);
												$scope.current = $scope.collection[0];
												/*
												ngDialog.open({
																template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																scope: $scope
												});
												*/
								});
				}
}());
