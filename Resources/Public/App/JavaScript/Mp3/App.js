/* global angular */

(function () {
    'use strict';
				angular.module('mp3App', ['solr'])
								.config(solrConfiguration);

				function solrConfiguration(SolrProvider) {
								SolrProvider.setFacets(['id3Artist', 'id3Album', 'id3Genre', 'id3Year', 'fsProvider', 'fsGenre']);
								SolrProvider.setHFacets({
								});
								SolrProvider.setSetting('rows', 10);
								SolrProvider.setSetting('facet_limit', 10);
				};
}());