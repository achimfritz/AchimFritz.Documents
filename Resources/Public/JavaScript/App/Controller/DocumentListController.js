/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('DocumentListController', DocumentListController);

				function DocumentListController($scope, DocumentListRestService) {

								$scope.list = [];
								$scope.view = 'list';
								$scope.finished = false;
								$scope.documentList = {};

								$scope.show = function(identifier) {
												$scope.finished = false;
												DocumentListRestService.show(identifier).then(function(data) {
																$scope.finished = true;
																$scope.documentList = data.data.documentList;
																$scope.view = 'show';
												});
								};

								$scope.onDropComplete = function (index, obj, evt) {
												var objIndex = $scope.documentList.documentListItems.indexOf(obj);
												var oldList = $scope.documentList.documentListItems;
												var l = oldList.length;
												var newList = [];
												for (var j = 0; j < l; j++) {
																if (j === index) {
																				newList.push(obj);
																}
																if (j !== objIndex) {
																				newList.push(oldList[j]);
																}
												}
												$scope.documentList.documentListItems = newList;
								};


								$scope.list = function() {
												list();
								};

								list();

								function list() {
												$scope.finished = false;
												DocumentListRestService.list().then(function(data) {
																$scope.finished = true;
																$scope.documentLists = data.data.documentLists;
																$scope.view = 'list';
												}, function(data) {
																$scope.finished = true;
																FlashMessageService.error(data);
												});
								};
				}
}());
