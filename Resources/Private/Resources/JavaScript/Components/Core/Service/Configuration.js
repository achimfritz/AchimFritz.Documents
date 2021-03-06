/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .service('CoreConfigurationService', CoreConfigurationService);

    /* @ngInject */
    function CoreConfigurationService (SolrConfiguration, AppConfiguration, Solr, CoreApplicationScopeFactory, $location) {

        loadModuleConfiguration(getModuleName($location.absUrl()));

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
            if (name === 'music') {
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
                SolrConfiguration.setVisibleFacets({artist: true, album: true});

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
            } else if (name === 'mp3') {
                resetSolr();
                loadFrontendConfiguration();
                AppConfiguration.setNamespace('mp3');

                SolrConfiguration.setFacets(['artist', 'album', 'genre', 'hPaths', 'year', 'fsProvider', 'artistLetter', 'fsGenre']);
                SolrConfiguration.setHFacets({hPaths: '0'});
                SolrConfiguration.setParam('sort', 'mDateTime desc, fsTitle asc');
                SolrConfiguration.setParam('rows', 15);
                SolrConfiguration.setParam('facet_limit', 5);
                SolrConfiguration.setParam('facet_sort', 'count');
                SolrConfiguration.setSetting('servlet', 'mp3');
                SolrConfiguration.setVisibleFacets({artist: true, hPaths: true});

                callSolr();
            } else if (name === 'document') {
                resetSolr();
                loadFrontendConfiguration();
                callSolr();
            } else if (name === 'image') {
                resetSolr();
                loadFrontendConfiguration();

                AppConfiguration.setNamespace('image');

                SolrConfiguration.setFacets(['pathParts', 'hDates', 'day', 'month', 'categories', 'locations', 'hCategories', 'hPaths', 'hLocations', 'year', 'tags', 'parties', 'mainDirectoryName', 'collections']);
                SolrConfiguration.setHFacets({
                    hPaths: '0',
                    hDates: '0',
                    hCategories: '1/categories',
                    hLocations: '1/locations'
                });
                SolrConfiguration.setSetting('servlet', 'image');
                callSolr();
            }
        }

        function callSolr() {
            Solr.init();
            Solr.update();

        }

        CoreApplicationScopeFactory.registerListener('CoreConfigurationService', '$locationChangeSuccess', function(ev, next, current) {
            if (getModuleName(current) !== getModuleName(next)) {
                loadModuleConfiguration(getModuleName(next));
            }
        });

    }
})();
