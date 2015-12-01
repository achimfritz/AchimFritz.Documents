/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .service('Mp3IpodSolrService', Mp3IpodSolrService);

    /* @ngInject */
    function Mp3IpodSolrService (Solr, SolrConfiguration) {

        var self = this;
        var initialized = false;

        self.request = request;
        self.initialize = initialize;
        self.facetsToKeyValue = facetsToKeyValue;

        function initialize() {
            if (initialized === false) {
                SolrConfiguration.setParam('facet_limit', 9999999);

                SolrConfiguration.setFacets(['artist', 'genre', 'album']);
                SolrConfiguration.setHFacets({});
                SolrConfiguration.setSetting('servlet', 'mp3');

                Solr.init();
                Solr.setParam('rows', 999);
                Solr.setParam('sort', 'track asc, fsTrack asc, album asc, artist asc');
                initialized = true;
            }
        }

        function facetsToKeyValue(facets) {
            var keyValues = Solr.facetsToKeyValue(facets);
            //keyValues.shift({key: 'all', value: 'all'});
            return keyValues;
        }

        function request(genre, artist, album) {
            Solr.resetFilterQueries();
            if (genre !== 'all') {
                Solr.addFilterQuery('genre', genre);
            }
            if (album !== 'all') {
                Solr.addFilterQuery('album', album);
            }
            if (artist !== 'all') {
                Solr.addFilterQuery('artist', artist);
            }
            return Solr.forceRequest();
        }

    }
})();
