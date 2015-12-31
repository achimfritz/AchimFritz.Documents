/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicListController', MusicListController);

    /* @ngInject */
    function MusicListController (ngDialog, $rootScope, $timeout, Mp3PlayerService, Solr, PathService, DocumentListRestService) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.docs = [];
        vm.paths = [];

        vm.addFilterQuery = addFilterQuery;


        // TODO DocumntController.initController(), rm Solr from ApiController
        // or do not use DocumentController?

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
                DocumentListRestService.showByPath(path).then(
                    function(response) {
                        var documentList = response.data.documentList;
                        Solr.forceRequest().then(function (response) {
                            angular.forEach(documentList.documentListItems, function (listItem) {
                                angular.forEach(response.data.response.docs, function (solrDoc) {
                                    if (solrDoc.identifier === listItem.document['__identity']) {
                                        vm.docs.push(solrDoc);
                                    }
                                });
                            });
                            response.data.response.docs = vm.docs;
                            $rootScope.$emit('solrDataUpdate', response.data);
                            //console.log(vm.docs);
                        });
                    },
                    function(response) {

                    }
                );
            } else {
                Solr.forceRequest().then(function (response) {
                    $rootScope.$emit('solrDataUpdate', response.data);
                });
            }

        }

    }
})();
