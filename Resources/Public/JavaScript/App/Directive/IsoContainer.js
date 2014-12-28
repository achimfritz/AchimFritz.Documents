/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.directive('isoContainer', IsoContainer);

				function IsoContainer($timeout, ngDialog, ItemService, RestService, FlashMessageService) {

								return {

												scope: {
																items: '=isoContainer',
																total: '@',
																currentPage: '@',
																itemsPerPage: '@'
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Docs.html?xx',

												

											link: function(scope, element, attr) {
																scope.mode = 'view';
																scope.finished = true;
																scope.current = {};

																jQuery(document).keydown(function(e) {
																				if (e.keyCode == 39) {
																								// next
																								if (scope.current.identifier) {
																												scope.next();
																								}
																				} else if (e.keyCode == 37) {
																								// prev
																								if (scope.current.identifier) {
																												scope.prev();
																								}
																				} else if (e.keyCode == 27) {
																								// close
																								ngDialog.close();
																				}
																});
																				
																var options = {
																				itemSelector: '.iso-item',
																				layoutMode: 'fitRows'
																};
																element.isotope(options);

																scope.$watch('items', function(newVal, oldVal){
																			$timeout(function(){
																								element.isotope('reloadItems').isotope(options);
																			}, 500);
																},true);

																scope.addTag = function() {
																				var tag = jQuery('#addTag').val();
																				var docs = [];
																				docs.push(scope.current);
																				scope.finished = false;
																				RestService.merge('tags/' + tag, docs).then(function(data) {
																								scope.finished = true;
																								FlashMessageService.show(data.data.flashMessages);
																				});
																};

																scope.nextPage = function(pageNumber) {
																				scope.$parent.pageChanged(pageNumber);
																};

																scope.prev = function() {
																				var current = ItemService.getPrev(scope.current, scope.items);
																				if (current.identifier) {
																								scope.current = current;
																								ngDialog.close();
																								ngDialog.open({
																												template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																												scope: scope
																								});
																				}
																};

																scope.next = function() {
																				var current = ItemService.getNext(scope.current, scope.items);
																				if (current.identifier) {
																								scope.current = current;
																								ngDialog.close();
																								ngDialog.open({
																												template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																												scope: scope
																								});
																				}
																};


																scope.itemClick = function(item) {
																				var items = scope.items;
																				if (scope.mode === 'select') {
																								ItemService.itemClick(item, scope.items);
																				} else { // mode = view
																								scope.current = item;
																								ngDialog.close();
																								ngDialog.open({
																												template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																												scope: scope
																								});
																				}
																};
												},

								};

				}
}());
