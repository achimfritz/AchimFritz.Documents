/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.directive('isoContainer', IsoContainer);

				function IsoContainer($timeout, ngDialog, ItemService, RestService, FlashMessageService, Solr) {

								return {

												scope: {
																items: '=items'
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/ImageApp/Partials/Docs.html',

												

											link: function($scope, element, attr) {

															$scope.settings = Solr.getSettings();
															$scope.currentPage = ($scope.settings['start']/$scope.settings['rows']) + 1;
															$scope.itemsPerPage = $scope.settings['rows'];

															$scope.nextPage = function(pageNumber) {
																				Solr.setSetting('start', (pageNumber - 1) * $scope.settings.rows);
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
																		$timeout(function(){
																							element.isotope('reloadItems').isotope(options);
																		}, 500);
															},true);

												},

								};

				}
}());
