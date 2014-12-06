/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('FacetFactory', FacetFactory);

				function FacetFactory() {
								var facets = ['mainDirectoryName', 'year'];
								var hFacets = ['paths'];
								var filterQueries = [];

        // Public API
        return {
												getFilterQueries: function() {
																return filterQueries;
												},
												addFilterQuery: function(name, value) {
																filterQueries.push(name + ':' + value);
												},
												rmFq: function(value) {
																filterQueries.splice(value,1);			
												},
												getFacets: function() {
																return facets;
												},
												getHFacets: function() {
																return hFacets;
												},
        };

				}
}());


