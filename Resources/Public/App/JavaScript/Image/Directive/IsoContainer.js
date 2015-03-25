/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.directive('isoContainer', IsoContainer);

				function IsoContainer($timeout, ngDialog, ItemService, RestService, FlashMessageService, Solr) {

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
																								next($scope);
																				}
																} else if (e.keyCode == 37) {
																				// prev
																				if ($scope.current.identifier) {
																								prev($scope);
																				}
																} else if (e.keyCode == 27) {
																				// close
																				ngDialog.close();
																}
												});
								};

								function dialog($scope) {
												ngDialog.close();
												ngDialog.open({
																template: '/_Resources/Static/Packages/AchimFritz.Documents/App/JavaScript/Image/Partials/Dialog.html',
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
																items: '=items'
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/App/JavaScript/Image/Partials/Docs.html',

												link: function($scope, element, attr) {

																$scope.mode = 'view';
                $scope.finished = true;
                $scope.current = {};

                $scope.prev = function() {
																				itemPrev($scope);
                };

                $scope.next = function() {
																				itemNext($scope);
                };

                $scope.itemClick = function(item) {
																				itemClick($scope, item);
                };

																$scope.nextPage = function(pageNumber) {
																				Solr.setSetting('start', ((pageNumber - 1) * $scope.settings.rows).toString());
																				Solr.getData().then(function(data) {
																								$scope.items = data.data.response.docs;
																				});
																};

																Solr.getData().then(function(data) {
																				$scope.total = data.data.response.numFound;
																});

																var options = {
																			itemSelector: '.iso-item',
																			layoutMode: 'fitRows'
																};
																element.isotope(options);
																$scope.$watch('items', function(newVal, oldVal){
																				update($scope);
																		  $timeout(function(){
																						  	element.isotope('reloadItems').isotope(options);
																		  }, 800);
																},true);

												},

								};

				}
}());
