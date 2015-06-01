/* global angular */

(function () {
    'use strict';
				angular.module('mp3App', ['solr', 'angularSoundManager'])
								.config(solrConfiguration);

				function solrConfiguration(SolrProvider) {
								SolrProvider.setFacets(['id3Artist', 'id3Album', 'id3Genre', 'id3Year', 'fsProvider', 'fsGenre', 'artistLetter']);
								SolrProvider.setHFacets({
								});
								SolrProvider.setSolrSetting('servlet', 'mp3');
								SolrProvider.setSetting('rows', 30);
								SolrProvider.setSetting('facet_limit', 30);
								SolrProvider.setSetting('facet_sort', 'count');
								SolrProvider.setSetting('f_artistLetter_facet_sort', 'index');
				};
}());
