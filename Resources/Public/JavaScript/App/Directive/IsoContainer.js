/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.directive('isoContainer', IsoContainer);

				function IsoContainer($timeout) {

								return {

												scope: {
																items: '=isoContainer'
												},
												//template:'<div class="item" ng-click="logHello()" ng-repeat="item in items | orderBy:\'fileName\'"><img src="{{item.webPreviewPath}}" /></div>',
												template:'<div><div class="iso-item" ng-click="logHello()" ng-repeat="item in items" ><img src="{{item.webPreviewPath}}" /></div></div>',
												

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
																			}, 500);
																},true);
																
																scope.logHello = function(){
																		console.log("hello world")
																}
											}
								};

				}
}());
