/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .service('CoreConfigurationService', CoreConfigurationService);

    /* @ngInject */
    function CoreConfigurationService (SolrConfiguration, AppConfiguration, Solr, $rootScope, WidgetConfiguration, FilterConfiguration, $location) {


        function getModuleName(path) {
            var res = path.split('/');
            return res[4];
        }

        function resetSolr() {
            SolrConfiguration.setDefaults();
            Solr.reset();
        }

        function loadFrontendConfiguration() {
            var frontendConfigurationDiv = jQuery('#frontendConfiguration');
            if (frontendConfigurationDiv.length) {
                var frontendConfiguration = frontendConfigurationDiv.data('frontendconfiguration');
                var solrSettings = frontendConfiguration.solrSettings;
                SolrConfiguration.setSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
                AppConfiguration.setApplicationRoot(frontendConfiguration.applicationRoot);
            }
        }

        function loadModuleConfiguration(name) {
            if (name === 'mp3') {
                resetSolr();
                loadFrontendConfiguration();

                AppConfiguration.setNamespace('mp3');
                WidgetConfiguration.setNamespace('Mp3');
                WidgetConfiguration.setWidgets({
                    result: true,
                    filter: false,
                    ratingFilter: false,
                    integrity: false,
                    lists: false,
                    player: false
                });

                FilterConfiguration.setFilters({
                    artist: true,
                    genre: true,
                    fsProvider: true,
                    fsGenre: true,
                    album: false,
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
                SolrConfiguration.setParam('sort', 'mDateTime desc');
                SolrConfiguration.setParam('rows', 15);
                SolrConfiguration.setParam('facet_limit', 15);
                SolrConfiguration.setParam('facet_sort', 'count');
                SolrConfiguration.setParam('f_artistLetter_facet_sort', 'index');
                SolrConfiguration.setParam('f_artistLetter_facet_limit', 35);
                SolrConfiguration.setParam('f_hPaths_facet_limit', 35);
                SolrConfiguration.setSetting('servlet', 'mp3');

                callSolr();
            } else if (name === 'music') {
                resetSolr();
                loadFrontendConfiguration();

                AppConfiguration.setNamespace('mp3');

                SolrConfiguration.setFacets(['artist', 'album', 'fsArtist', 'fsAlbum', 'artistLetter', 'bitrate', 'genre', 'year', 'fsProvider', 'fsGenre', 'hPaths']);
                SolrConfiguration.setHFacets({hPaths: '0'});
                SolrConfiguration.setParam('sort', 'mDateTime desc, fsTitle asc');
                SolrConfiguration.setParam('rows', 15);
                SolrConfiguration.setParam('facet_limit', 15);
                SolrConfiguration.setParam('facet_sort', 'count');
                SolrConfiguration.setParam('f_artistLetter_facet_sort', 'index');
                SolrConfiguration.setParam('f_artistLetter_facet_limit', 35);
                SolrConfiguration.setParam('f_hPaths_facet_limit', 35);
                SolrConfiguration.setSetting('servlet', 'mp3');

                //Solr.init();
                callSolr();
            } else if (name === 'mp3ipod') {
                resetSolr();
                loadFrontendConfiguration();
                AppConfiguration.setNamespace('mp3');
                SolrConfiguration.setParam('facet_limit', 9999999);
                SolrConfiguration.setFacets(['artist', 'genre', 'album', 'spell']);
                SolrConfiguration.setHFacets({});
                SolrConfiguration.setSetting('servlet', 'mp3');
                callSolr();
            } else if (name === 'document') {
                resetSolr();
                loadFrontendConfiguration();
                callSolr();
            } else if (name === 'image') {
                resetSolr();
                loadFrontendConfiguration();

                AppConfiguration.setNamespace('image');

                SolrConfiguration.setFacets(['pathParts', 'motto', 'day', 'month', 'categories', 'locations', 'hCategories', 'hPaths', 'hLocations', 'year', 'tags', 'parties', 'mainDirectoryName', 'collections']);
                SolrConfiguration.setHFacets({
                    hPaths: '0',
                    hCategories: '1/categories',
                    hLocations: '1/locations'
                });
                SolrConfiguration.setSetting('servlet', 'image');
                WidgetConfiguration.setNamespace('Image');
                WidgetConfiguration.setWidgets({
                    result: true,
                    filter: false,
                    solrIntegrity: false,
                    clipboard: false,
                    integrity: false
                });
                FilterConfiguration.setFilters({
                    pathParts: true,
                    hCategories: false,
                    hLocations: false,
                    hPaths: false,
                    categories: true,
                    locations: true,
                    mainDirectoryName: true,
                    motto: true,
                    year: true,
                    month: false,
                    day: false,
                    tags: false,
                    parties: false,
                    collections: false
                });
                FilterConfiguration.setConfiguration({
                    categories: ['hPaths', 'hLocations', 'hCategories', 'parties', 'tags']
                });

                callSolr();
            }
        }

        loadModuleConfiguration(getModuleName($location.absUrl()));

        function callSolr() {
            Solr.init();
            Solr.update();

        }

        $rootScope.$on('$locationChangeSuccess', function(ev, next, current) {
            if (getModuleName(current) !== getModuleName(next)) {
                loadModuleConfiguration(getModuleName(next));
            }
        });
    }
})();
