/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicListController', MusicListController);

    /* @ngInject */
    function MusicListController (ngDialog, $rootScope, $timeout, Mp3PlayerService, Solr) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.docs = [];

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

    }
})();
