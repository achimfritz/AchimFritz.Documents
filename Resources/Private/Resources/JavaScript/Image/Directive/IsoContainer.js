/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.directive('isoContainer', IsoContainer);

				function IsoContainer(ngDialog, ItemService, DocumentCollectionRestService, FlashMessageService, Solr) {

								function update($scope) {
												$scope.settings = Solr.getSettings();
												$scope.currentPage = ($scope.settings['start']/$scope.settings['rows']) + 1;
												$scope.itemsPerPage = $scope.settings['rows'];
								};

								function bindKeys($scope) {
												jQuery(document).keydown(function(e) {
																if (e.keyCode == 39) {
																				// next
																				if ($scope.current.identifier) {
																								itemNext($scope);
																				}
																} else if (e.keyCode == 37) {
																				// prev
																				if ($scope.current.identifier) {
																								itemPrev($scope);
																				}
																} else if (e.keyCode == 27) {
																				// close
																				ngDialog.closeAll();
																}
												});
								};

								function dialog($scope) {
												ngDialog.closeAll();
												ngDialog.open({
																template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/Image/Partials/Dialog.html',
																scope: $scope
												});
								};

								function itemPrev($scope) {
												var current = ItemService.getPrev($scope.current, $scope.items);
												if (current.identifier) {
																$scope.current = current;
																dialog($scope);
												}
								};

								function itemNext($scope) {
												var current = ItemService.getNext($scope.current, $scope.items);
												if (current.identifier) {
																$scope.current = current;
																dialog($scope);
												}
								};


								function itemClick($scope, item) {
												var items = $scope.items;
												if ($scope.mode === 'select') {
																ItemService.itemClick(item, $scope.items);
												} else { // mode = view
																$scope.current = item;
																dialog($scope);
												}
								};

								return {

												scope: {
																items: '=items',
																total: '=total'
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/Image/Partials/Docs.html',

												link: function($scope, element, attr) {

																$scope.mode = 'view';
                $scope.finished = true;
                $scope.current = {};

																bindKeys($scope);

                $scope.prev = function() {
																				itemPrev($scope);
                };

                $scope.next = function() {
																				itemNext($scope);
                };

                $scope.itemClick = function(item) {
																				itemClick($scope, item);
                };

																// TODO directive ?
                $scope.addTag = function() {
                    var tag = jQuery('#addTag').val();
                    var docs = [];
                    docs.push($scope.current);
                    $scope.finished = false;
                    DocumentCollectionRestService.merge('tags/' + tag, docs).then(function(data) {
                        $scope.finished = true;
                        FlashMessageService.show(data.data.flashMessages);
                    });
                };
																$scope.$watch('items', function(newVal, oldVal){
																				update($scope);
																},true);
												},

								};

				}
}());
