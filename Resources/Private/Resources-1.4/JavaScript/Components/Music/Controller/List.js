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
        vm.providers = {};
        vm.lists = {};


        // TODO DocumntController.initController(), rm Solr from ApiController
        // or do not use DocumentController?

        Solr.resetFilterQueries();
        Solr.setParam('f_hPaths_facet_prefix', '1/list/');
        Solr.forceRequest().then(function (response) {
            vm.providers = response.data.facet_counts.facet_fields.hPaths;
            $rootScope.$emit('solrDataUpdate', response.data);
        });

        $rootScope.$on('solrDataUpdate', function (event, data) {
            var fq = Solr.getFilterQueries();
            var current = fq['hPaths'];
            if (angular.isDefined(current)) {
                if (PathService.depth(current[0]) === 3) {
                    vm.lists = data.facet_counts.facet_fields.hPaths;
                } else if (PathService.depth(current[0]) === 4) {
                    // new RestController DocumentListByCategory
                    /*
                    - merge documentList (sorting)
                    - response.data.response.docs = vm.docs;
                     $rootScope.$emit('solrDataUpdate', response.data);
                     !!!
                     */
                }

            }
            //console.log(current);
            //vm.lists = data.facet_counts.facet_fields.hPaths;
            //console.log(vm.lists);
        });

        /*
        Mp3PlayerService.initialize();

        $rootScope.$on('documentListLoaded', function (event, data) {
            Solr.resetFilterQueries();
            vm.docs = [];
            var documentList = data;
            Solr.setParam('f_hPaths_facet_prefix', '2/');
            Solr.setParam('rows', documentList.documentListItems.length);
            Solr.addFilterQuery('hPaths', '2/' + documentList.category.path);
            Solr.forceRequest().then(function (response) {
                Solr.setParam('f_hPaths_facet_prefix', '0/');
                angular.forEach(documentList.documentListItems, function (listItem) {
                    angular.forEach(response.data.response.docs, function (solrDoc) {
                        if (solrDoc.identifier === listItem.document['__identity']) {
                            listItem.document.solrDoc = solrDoc;
                            vm.docs.push(solrDoc);
                        }
                    });
                });
                response.data.response.docs = vm.docs;
                $rootScope.$emit('solrDataUpdate', response.data);
            });
        });
        */

    }
})();
