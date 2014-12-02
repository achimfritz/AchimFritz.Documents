/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ClipboardController', ClipboardController);

				function ClipboardController($scope, DocumentFactory) {
								$scope.collection = DocumentFactory.getDocs();
				
								$scope.transfer = function() {
												//var docs = SelectableService.getSelected();
								}
				}
}());
