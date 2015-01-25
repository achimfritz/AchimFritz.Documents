/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('HierarchicalFacetFactory', HierarchicalFacetFactory);

				function HierarchicalFacetFactory() {

								var delimiter = '/';

								// 0/foo -> 1/foo
								var increase = function(path) {
												var splitPath = path.split(delimiter);
												var level = splitPath.shift();
												level ++;
												return level + delimiter + splitPath.join(delimiter);
								};

								// 0/foo -> 0
								var decrease = function(path) {
												var splitPath = path.split(delimiter);
												var last = splitPath.pop();
												return splitPath.join(delimiter);
								};

								// 2/foo/bar -> 1/foo
								// 0/foo -> ''
								var decreaseFq = function(path) {
												var splitPath = path.split(delimiter);
												var last = splitPath.pop();
												var level = parseInt(splitPath.shift());
												if (level === 0) {
																return '';
												}
												level--;
												return level + delimiter + splitPath.join(delimiter);
								};

								// location, 2/foo/bar -> location/foo/bar
								var category = function(facetName, facetValue) {
												var splitPath = facetValue.split(delimiter);
												var level = splitPath.shift();
												return facetName + delimiter + splitPath.join(delimiter);
								};

        // Public API
        return {
												increase: function(path) {
																return increase(path);
												},
												decrease: function(path) {
																return decrease(path);
												},
												decreaseFq: function(path) {
																return decreaseFq(path);
												},
												category: function(facetName, facetValue) {
																return category(facetName, facetValue);
												}
        };

				}
}());


