/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.document')
        .controller('DocumentIndexController', DocumentIndexController);

    /* @ngInject */
    function DocumentIndexController (Solr, CoreApplicationScopeFactory) {

        var vm = this;

        vm.filterQueries = {};
        vm.data = {};
        vm.search = '';
        vm.params = {};

        vm.solr = Solr;

        getSolrData();

        function getSolrData() {
            vm.filterQueries = Solr.getFilterQueries();
            vm.data = Solr.getData();
            vm.params = Solr.getParams();
        }

        CoreApplicationScopeFactory.registerListener('DocmentIndexController', 'solrDataUpdate', function(event, data) {
            getSolrData();
        });

    }
})();
