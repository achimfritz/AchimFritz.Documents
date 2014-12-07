/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('FacetFactory', FacetFactory);

				function FacetFactory(HierarchicalFacetFactory) {
								var facets = ['mainDirectoryName', 'year', 'paths'];
								var hFacets = ['paths'];
								var filterQueries = {};
								var facetPrefixes = {};


								angular.forEach(hFacets, function(val) {
												facetPrefixes[val] = '0';
								});

        // Public API
        return {
												getFilterQueries: function() {
																return filterQueries;
												},
												addFilterQuery: function(name, value) {
																filterQueries[name] = value;
																// hFacet ?
																var index = hFacets.indexOf(name);
																if (index > -1) {
																				facetPrefixes[name] = HierarchicalFacetFactory.increase(value);
																}
												},

												getFacetPrefixes: function() {
																return facetPrefixes;
												},
												rmFilterQuery: function(name, value) {
																// hFacet ?
																var index = hFacets.indexOf(name);
																if (index > -1) {
																				filterQueries[name] = HierarchicalFacetFactory.decreaseFq(value);
																				facetPrefixes[name] = HierarchicalFacetFactory.decrease(value);
																} else {
																				filterQueries[name] = '';
																}
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


