/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('ClipboardController', ClipboardController);

				function ClipboardController($scope, DocumentFactory, SolrFactory) {
								$scope.collection = DocumentFactory.getDocs();
				
								$scope.transfer = function() {
												SolrFactory.getData().then(function(data) {
																jQuery.each(data.data.response.docs, function(i, el) {
																				if (el.selected === 'selected') {
																								DocumentFactory.addDoc(el);
																				}
																});
																$scope.collection = DocumentFactory.getDocs();
												});
								}
				}
}());
