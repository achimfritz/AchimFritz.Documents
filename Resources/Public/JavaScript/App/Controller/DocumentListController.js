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

								$scope.show = function(name) {
												$scope.finished = false;
												DocumentListRestService.show(name).then(function(data) {
																$scope.finished = true;
																$scope.documentList = data.data.documentList;
																$scope.view = 'show';
												});
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
												});
								};
				}
}());
