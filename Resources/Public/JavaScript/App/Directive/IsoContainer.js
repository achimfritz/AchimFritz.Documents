/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.directive('isoContainer', IsoContainer);

				function IsoContainer($timeout, ngDialog, ItemService) {

								return {

												scope: {
																items: '=isoContainer',
																total: '@',
																currentPage: '@',
																itemsPerPage: '@'
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Docs.html?xx',

												

											link: function(scope, element, attr) {
																scope.mode = 'select';

																jQuery(document).keydown(function(e) {
																				if (e.keyCode == 39) {
																								// next
																				} else if (e.keyCode == 37) {
																								// prev
																				} else if (e.keyCode == 27) {
																								// close
																				}
																});
																				
																var options = {
																				itemSelector: '.iso-item',
																				layoutMode: 'fitRows'
																};
																element.isotope(options);

																scope.$watch('items', function(newVal, oldVal){
																			$timeout(function(){
																								//element.isotope( 'reloadItems' ).isotope(); 
																								element.isotope('reloadItems').isotope(options);
																			}, 500);
																},true);

																scope.nextPage = function(pageNumber) {
																				scope.$parent.pageChanged(pageNumber);
																};

																scope.next = function() {
																				scope.current = ItemService.getNext(scope.current, scope.items);
																				ngDialog.close();
																				ngDialog.open({
																								template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																								scope: scope
																				});
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
