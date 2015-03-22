/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.directive('navPath', NavPath);

				function NavPath(SolrFactory, PathService) {
								return {
												restrict: 'E',
												link: function(scope, element, attr) {
																if (SolrFactory.isHFacet(attr.facet)) {
																				element.replaceWith(PathService.last(attr.path));
																} else {
																				element.replaceWith(attr.path);
																}
												}
								};
				}
}());
