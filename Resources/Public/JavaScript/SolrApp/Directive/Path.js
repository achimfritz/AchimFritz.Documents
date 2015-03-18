/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.directive('path', Path);

				function Path(SolrFactory, PathService) {

																return {
																				restrict: 'E',
																				scope: {
																								facet: '=facet',
																								path: '=path'
																				},
																				link: function(scope) {
																								if (SolrFactory.isHFacet(scope.facet)) {
																												scope.path = PathService.last(scope.path);
																								} 
																				},
																				template: function(el,attr) {
																								return '{{path}}'
																				}
																};
				}
}());
