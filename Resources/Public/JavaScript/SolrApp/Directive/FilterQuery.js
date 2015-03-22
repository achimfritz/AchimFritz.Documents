/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.directive('filterQuery', FilterQuery);

				function FilterQuery(SolrFactory, PathService) {
								return {
												restrict: 'E',
												link: function(scope, element, attr) {
																if (SolrFactory.isHFacet(attr.facet)) {
																				var hFacet = SolrFactory.getHFacet(attr.facet);
																				var depth = PathService.depth(hFacet);
																				element.replaceWith(PathService.slice(attr.path, depth));
																} else {
																				element.replaceWith(attr.path);
																}
												}
								};
				}
}());
