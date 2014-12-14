/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.directive('isoContainer', IsoContainer);

				function IsoContainer($timeout) {

								return {

												scope: {
																items: '=isoContainer',
																total: '@',
																itemsPerPage: '@',
																pageChanged: '&',
																//collection: '=collection'
																// same name
																collection: '='
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Docs.html?xx',
												

											link: function(scope, element, attr) {
																var strgPressed = false;
																var shiftPressed = false;

																jQuery(document).keyup(function(e) {
																				shiftPressed = false;
																				strgPressed = false;
																});
																jQuery(document).keydown(function(e) {
																				if (e.keyCode == 16) {
																								shiftPressed = true;
																				}
																				if (e.keyCode == 17) {
																								strgPressed = true;
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

																scope.toggleItem = function(item, items) {
																				if (item.selected === 'selected') {
																								item.selected = '';
																				} else {
																								if (strgPressed === false) {
																												// rm all others
																												angular.forEach(items, function(val, key) {
																																if (item.identifier !== val.identifier) {
																																				val.selected = '';
																																}
																												});
																								} else if (shiftPressed === true) {
																												// select all from last selected
																												var collect = false;
																												for (var i = ( items.length - 1 ); i >= 0; i--) {
																																var el = items[i];
																																if (el.identifier === item.identifier) {
																																				collect = true;
																																}
																																if (collect === true) {
																																				if (el.selected === 'selected') {
																																								collect = false;
																																				}
																																				el.selected = 'selected';
																																}
																																
																												}
																								}

																								// add always me 
																								item.selected = 'selected';
																				}
																};
												},

								};

				}
}());
