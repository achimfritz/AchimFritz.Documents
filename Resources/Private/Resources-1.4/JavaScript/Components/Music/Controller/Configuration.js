/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicConfigurationController', MusicConfigurationController);

    /* @ngInject */
    function MusicConfigurationController (SolrConfiguration, AppConfiguration, FilterConfiguration) {

        var frontendConfigurationDiv = jQuery('#frontendConfiguration');
        if (frontendConfigurationDiv.length) {
            var frontendConfiguration = frontendConfigurationDiv.data('frontendconfiguration');
            var solrSettings = frontendConfiguration.solrSettings;
            SolrConfiguration.setSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
            AppConfiguration.setApplicationRoot(frontendConfiguration.applicationRoot);
        }

        AppConfiguration.setNamespace('mp3');

        FilterConfiguration.setFilters({
            artist: true,
            album: true,
            genre: true,
            fsProvider: true,
            fsGenre: true,
            artistLetter: false,
            year: false,
            hPaths: false,
            fsArtist: false,
            fsAlbum: false
        });
        FilterConfiguration.setConfiguration({
            categories: ['hPaths'],
            id3Tags: ['artist', 'album', 'genre', 'year']
        });
        SolrConfiguration.setFacets(['artist', 'album', 'fsArtist', 'fsAlbum', 'artistLetter', 'genre', 'year', 'fsProvider', 'fsGenre', 'hPaths']);
        SolrConfiguration.setHFacets({hPaths: '0'});
        //SolrConfiguration.setParam('sort', 'album asc, artist asc, track asc');
        SolrConfiguration.setParam('sort', 'mDateTime desc');
        SolrConfiguration.setParam('rows', 15);
        SolrConfiguration.setParam('facet_limit', 15);
        SolrConfiguration.setParam('facet_sort', 'count');
        SolrConfiguration.setParam('f_artistLetter_facet_sort', 'index');
        SolrConfiguration.setParam('f_artistLetter_facet_limit', 35);
        // SolrConfiguration.setParam('f_hPaths_facet_prefix', '2/');
        SolrConfiguration.setParam('f_hPaths_facet_limit', 35);
        SolrConfiguration.setSetting('servlet', 'mp3');

    }
})();
