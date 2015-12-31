/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicListController', MusicListController);

    /* @ngInject */
    function MusicListController ($rootScope, $timeout, Solr, PathService, DocumentListRestService, $location, CONFIG) {

        var vm = this;

        vm.paths = [];

        vm.addFilterQuery = addFilterQuery;


        Solr.resetFilterQueries();
        Solr.setFacetPrefix('hPaths', '0');
        Solr.forceRequest().then(function (response) {
            vm.paths[0] = response.data.facet_counts.facet_fields.hPaths;
            $rootScope.$emit('solrDataUpdate', response.data);
        });


        $rootScope.$on('solrDataUpdate', function (event, data) {
            var fq = Solr.getFilterQueries();
            var current = fq['hPaths'];
            if (angular.isDefined(current) && angular.isDefined(current[0])) {
                if (PathService.depth(current[0]) === 2) {
                    vm.paths[1] = data.facet_counts.facet_fields.hPaths;

                } else if (PathService.depth(current[0]) === 3) {
                    vm.paths[2] = data.facet_counts.facet_fields.hPaths;
                }
            }
        });

        function addFilterQuery(name, value) {
            var splitted = PathService.split(value);
            var filterType = splitted[1];
            Solr.addFilterQuery(name, value);
            if (filterType === 'list') {
                var path = PathService.slice(value, 1);
                var docs = [];
                DocumentListRestService.showByPath(path).then(
                    function(response) {
                        var documentList = response.data.documentList;
                        var cnt = documentList.documentListItems.length;
                        Solr.setParam('rows', cnt);
                        Solr.forceRequest().then(function (response) {
                            angular.forEach(documentList.documentListItems, function (listItem) {
                                angular.forEach(response.data.response.docs, function (solrDoc) {
                                    if (solrDoc.identifier === listItem.document['__identity']) {
                                        docs.push(solrDoc);
                                    }
                                });
                            });
                            response.data.response.docs = docs;
                            $rootScope.$emit('solrDataUpdate', response.data);
                            $timeout(function () {
                                $location.path(CONFIG.baseUrl + '/music/result');
                                $rootScope.$emit('locationChanged', 'result');
                            });
                        });
                    },
                    function(response) {
                        // standard on error
                        Solr.forceRequest().then(function (response) {
                            $rootScope.$emit('solrDataUpdate', response.data);
                            $timeout(function () {
                                $location.path(CONFIG.baseUrl + '/music/result');
                                $rootScope.$emit('locationChanged', 'result');
                            });
                        });
                    }
                );
            } else {
                Solr.forceRequest().then(function (response) {
                    $rootScope.$emit('solrDataUpdate', response.data);
                    $timeout(function () {
                        $location.path(CONFIG.baseUrl + '/music/result');
                        $rootScope.$emit('locationChanged', 'result');
                    });
                });
            }

        }

    }
})();
