/* global angular */

(function () {
    'use strict';



    angular
        .module('achimfritz.solr')
        .factory('FacetFactory', FacetFactory);

    function FacetFactory(Solr, SolrConfiguration) {

        var solrConfiguration = SolrConfiguration.getConfiguration();
        console.log(solrConfiguration);
        var visibleFacets = solrConfiguration.visibleFacets;
        console.log(visibleFacets);
        var facets = [];

        var isVisibleFacet = function (facet) {
            if (angular.isUndefined(visibleFacets[facet])) {
                return false;
            }
            return visibleFacets[facet];
        };

        return {
            updateFacets: function(data) {
                facets = [];
                if (angular.isDefined(data.facet_counts.facet_fields)) {
                    angular.forEach(data.facet_counts.facet_fields, function (facetField, key) {
                        var facetFields = [];
                        angular.forEach(facetField, function(val, key){
                            facetFields.push({index: key, count: val});
                        });
                        facets.push({
                            name: key,
                            orderBy: Solr.getFacetSorting(key),
                            isVisible: isVisibleFacet(key),
                            fields: facetFields
                        });
                    });
                }
                return facets;
            },
            getFacets: function() {
                return facets;
            },
            toggleFacet: function(name) {
                if (angular.isUndefined(visibleFacets[name]) || visibleFacets[name] === false) {
                    visibleFacets[name] = true;
                } else {
                    visibleFacets[name] = false;
                }
                angular.forEach(facets, function(facet) {
                   if (facet.name === name) {
                       facet.isVisible = isVisibleFacet(name);
                   }
                });
                return facets;
            }
        };



    }

}());
