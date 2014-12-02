/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.directive('isoContainer', IsoContainer);

				function IsoContainer($timeout, DocumentFactory) {

								return {

												scope: {
																items: '=isoContainer'
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Docs.html',
												

											link: function(scope, element, attr) {
																var options = {
																				itemSelector: '.iso-item',
																				layoutMode: 'fitRows'
																};
																				
																element.isotope(options);

																scope.$watch('items', function(newVal, oldVal){
																			$timeout(function(){
																								//element.isotope( 'reloadItems' ).isotope(); 
																								element.isotope('reloadItems').isotope(options);
																								//SelectableService.init();
																			}, 500);
																},true);

																scope.toggleItem = function(item, el) {
																				if (item.selected === 'selected') {
																								item.selected = '';
																								DocumentFactory.rmDoc(item);
																				} else {
																								item.selected = 'selected';
																								DocumentFactory.addDoc(item);
																				}
																};
																scope.toggleClipboardItem = function(item, el) {
																				if (item.selected === 'selected') {
																								item.selected = '';
																				} else {
																								item.selected = 'selected';
																				}
																};
																
												},

								};

				}
}());
