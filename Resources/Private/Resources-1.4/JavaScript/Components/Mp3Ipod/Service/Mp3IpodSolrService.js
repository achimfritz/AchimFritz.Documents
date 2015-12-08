/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .service('Mp3IpodSolrService', Mp3IpodSolrService);

    /* @ngInject */
    function Mp3IpodSolrService (Solr, SolrConfiguration, $q, AppConfiguration, DocumentListRestService) {

        var self = this;
        var initialized = false;

        self.request = request;
        self.initialize = initialize;
        self.facetsToKeyValue = facetsToKeyValue;

        function initialize() {
            if (initialized === false) {
                SolrConfiguration.setParam('facet_limit', 9999999);

                SolrConfiguration.setFacets(['artist', 'genre', 'album', 'spell']);
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
            keyValues.unshift({key: 'all', value: 'all'});
            return keyValues;
        }

        function request(genre, artist, album, list, search) {
            Solr.resetFilterQueries();
            if (search !== 'all') {
                Solr.setParam('q', search);
            } else {
                Solr.setParam('q', '*:*');
            }
            if (genre !== 'all') {
                Solr.addFilterQuery('genre', genre);
            }
            if (album !== 'all') {
                Solr.addFilterQuery('album', album);
            }
            if (artist !== 'all') {
                Solr.addFilterQuery('artist', artist);
            }
            if (list !== 'all') {

                var deferred = $q.defer();
                AppConfiguration.setNamespace('mp3');
                DocumentListRestService.show(list).then(
                    function (result) {
                        // sorting missmatch !!!
                        // better? use API SolrDocumentListController->show(documentList)
                        Solr.addFilterQuery('hPaths', '2/' + result.data.documentList.category.path);
                        Solr.forceRequest().then(
                            function (response) {
                                deferred.resolve(response)
                            },
                            function (data, status, header, config) {
                                deferred.reject(data, status, header, config);
                            }
                        );
                    },
                    function (data, status, header, config) {
                        deferred.reject(data, status, header, config);
                    }
                );

                return deferred.promise;
            }
            return Solr.forceRequest();
        }

    }
})();
