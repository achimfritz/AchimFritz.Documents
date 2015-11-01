/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.document')
        .controller('DocumentController', DocumentController);

    /* @ngInject */
    function DocumentController (Solr, $rootScope) {

        var vm = this;

        vm.filterQueries = {};
        vm.data = {};
        vm.search = '';
        vm.params = {};

        // used by the view
        vm.addFilterQuery = addFilterQuery;
        vm.rmFilterQuery = rmFilterQuery;
        vm.update = update;
        vm.changeRows = changeRows;
        vm.changeFacetCount = changeFacetCount;
        vm.overrideFilterQuery = overrideFilterQuery;
        vm.resetFilterQueries = resetFilterQueries;
        vm.changeFacetSorting = changeFacetSorting;
        vm.setSearch = setSearch;
        vm.nextPage = nextPage;
        vm.showAllRows = showAllRows;


        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            Solr.request().then(function (response){
                $rootScope.$emit('solrDataUpdate', response.data);
            })
        }

        function nextPage(pageNumber) {
            Solr.setParam('start', ((pageNumber - 1) * vm.params.rows).toString());
            vm.update();
        }

        function changeRows(diff) {
            Solr.changeRows(diff);
            vm.update();
        }

        function showAllRows() {
            Solr.setParam('rows', vm.data.response.numFound);
            vm.update();
        }

        function resetFilterQueries() {
            Solr.resetFilterQueries();
            vm.update();
        }

        function changeFacetCount(facetName, diff) {
            Solr.changeFacetCount(facetName, diff);
            vm.update();
        }

        function changeFacetSorting(facetName, sorting) {
            Solr.changeFacetSorting(facetName, sorting);
            vm.update();
        }

        function rmFilterQuery(name, value) {
            Solr.rmFilterQuery(name, value);
            vm.update();
        }

        function addFilterQuery(name, value) {
            Solr.addFilterQuery(name, value);
            vm.update();
        }

        function overrideFilterQuery(name, value) {
            Solr.overrideFilterQuery(name, value);
            vm.update();
        }

        function setSearch(search) {
            vm.search = search;
            vm.update();
        }

        function update() {

            if (vm.search !== undefined) {
                if (vm.search !== '') {
                    Solr.setParam('q', vm.search);
                } else {
                    Solr.setParam('q', '*:*');
                }
            }

            Solr.forceRequest().then(function (response) {
                $rootScope.$emit('solrDataUpdate', response.data);
            })
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            vm.filterQueries = Solr.getFilterQueries();
            vm.data = data;
            vm.params = Solr.getParams();
        });
    }
})();
