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
            //if (Solr.hasFilterQuery('hPaths')) {
            //    Solr.rmFilterQuery('hPaths', '2/' + documentList.category.path);
            //}
            Solr.addFilterQuery('hPaths', '2/' + documentList.category.path);
            Solr.forceRequest().then(function (response) {
                //Solr.rmFilterQuery('hPaths', '2/' + documentList.category.path);
                Solr.setParam('f_hPaths_facet_prefix', '0/');
                //Solr.resetFilterQueries();
                angular.forEach(documentList.documentListItems, function (listItem) {
                    angular.forEach(response.data.response.docs, function (solrDoc) {
                        if (solrDoc.identifier === listItem.document['__identity']) {
                            listItem.document.solrDoc = solrDoc;
                            vm.docs.push(solrDoc);
                        }
                    });
                });
                $rootScope.$emit('solrDataUpdate', response.data);
            });
        });

    }
})();
