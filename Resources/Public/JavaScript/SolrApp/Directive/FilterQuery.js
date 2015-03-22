/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.directive('filterQuery', FilterQuery);

				function FilterQuery(Solr, PathService) {
								return {
												restrict: 'E',
												link: function(scope, element, attr) {
																if (Solr.isHFacet(attr.facet)) {
																				var hFacet = Solr.getHFacet(attr.facet);
																				var depth = PathService.depth(hFacet);
																				element.replaceWith(PathService.slice(attr.path, depth));
																} else {
																				element.replaceWith(attr.path);
																}
												}
								};
				}
}());
