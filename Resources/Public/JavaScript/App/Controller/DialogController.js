/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('DialogController', DialogController);

				function DialogController($scope, ngDialog) {

								$scope.next = function() {
												var newItem = {};
												var found = false;
												angular.forEach($scope.items, function(val) {
																if (found === true) {
																				newItem = val;
																				found = false;
																}
																if (val.identifier === $scope.item.identifier) {
																				found = true;
																}
																//console.log(val.identifier);
												});
												$scope.item = newItem;
												console.log('foo');
												var newScope = {
																'item': $scope.item,
																'items': $scope.items
												};
												//console.log($scope.$parent.item);
												//$scope.next = $scope.$parent.next;
												//$scope.$parent.item = $scope.item;
												ngDialog.close();
												ngDialog.open({
																template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																//scope: $scope,
																controller: 'DialogController'
												});
								};

				}
}());
