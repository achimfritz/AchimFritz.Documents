/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.controller('SearchController', SearchController);

				function SearchController($scope, SolrFactory, FacetFactory) {

								$scope.settings = SolrFactory.getSettings();
								$scope.facets = FacetFactory.getFacets();
								console.log(FacetFactory.getFacets());

								SolrFactory.getData().then(function(data) {
												$scope.response = data.data.response;
								});

				}
}());
