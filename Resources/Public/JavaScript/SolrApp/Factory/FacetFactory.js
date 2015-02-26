/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.factory('FacetFactory', FacetFactory);

				function FacetFactory() {
								var facets = ['mainDirectoryName', 'year', 'collections', 'parties', 'tags', 'locations', 'categories', 'search'];
								var filterQueries = {};

        // Public API
        return {
												getFilterQueries: function() {
																return filterQueries;
												},
												addFilterQuery: function(name, value) {
																if (filterQueries[name] === undefined) {
																				filterQueries[name] = [];
																}
																filterQueries[name].push(value);
												},

												rmFilterQuery: function(name, value) {
																var index = filterQueries[name].indexOf(value);
																filterQueries[name].splice(index, 1);
												},
												getFacets: function() {
																return facets;
												}
        };

				}
}());


