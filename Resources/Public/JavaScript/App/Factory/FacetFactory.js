/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('FacetFactory', FacetFactory);

				function FacetFactory(HierarchicalFacetFactory) {
								var facets = ['mainDirectoryName', 'year', 'collections', 'parties', 'tags', 'locations', 'categories'];
								var hFacets = ['locations', 'categories'];
								var filterQueries = {};
								var facetPrefixes = {};


								angular.forEach(hFacets, function(val) {
												facetPrefixes[val] = '0';
								});

        // Public API
        return {
												category: function(facetName, facetValue) {
																// hFacet ?
																var index = hFacets.indexOf(facetName);
																if (index > -1) {
																				return HierarchicalFacetFactory.category(facetName, facetValue);
																} else {
																				return facetName + '/' + facetValue;
																}
												},

												getFilterQueries: function() {
																return filterQueries;
												},
												addFilterQuery: function(name, value) {
																if (filterQueries[name] === undefined) {
																				filterQueries[name] = [];
																}
																// hFacet ?
																var index = hFacets.indexOf(name);
																if (index > -1) {
																				facetPrefixes[name] = HierarchicalFacetFactory.increase(value);
																				filterQueries[name] = [];
																}
																filterQueries[name].push(value);
												},

												getFacetPrefixes: function() {
																return facetPrefixes;
												},
												rmFilterQuery: function(name, value) {
																// hFacet ?
																var index = hFacets.indexOf(name);
																if (index > -1) {
																				filterQueries[name] = [];
																				var fq = HierarchicalFacetFactory.decreaseFq(value);
																				if (fq !== '') {
																								filterQueries[name].push(fq);
																				}
																				facetPrefixes[name] = HierarchicalFacetFactory.decrease(value);
																} else {
																				var index = filterQueries[name].indexOf(value);
																				filterQueries[name].splice(index, 1);
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


