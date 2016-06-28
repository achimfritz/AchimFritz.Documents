/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.document')
        .controller('DocumentController', DocumentController);

    /* @ngInject */
    function DocumentController (Solr, $rootScope) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.filterQueries = {};
        vm.data = {};
        vm.search = '';
        vm.params = {};
        vm.random = 0;

        // used by the view
        vm.addFilterQuery = addFilterQuery;
        vm.rmFilterQuery = rmFilterQuery;
        vm.changeRows = changeRows;
        vm.changeFacetCount = changeFacetCount;
        vm.overrideFilterQuery = overrideFilterQuery;
        vm.resetFilterQueries = resetFilterQueries;
        vm.changeFacetSorting = changeFacetSorting;
        vm.setSearch = setSearch;
        vm.nextPage = nextPage;
        vm.showAllRows = showAllRows;
        vm.newRandom = newRandom;
        vm.clearSearch = clearSearch;
        vm.update = update;


        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.random = Solr.newRandom();
            vm.filterQueries = Solr.getFilterQueries();
            vm.data = Solr.getData();
            vm.params = Solr.getParams();
        }

        function newRandom() {
            vm.random = Solr.newRandomAndUpdate();
        }

        function nextPage(pageNumber) {
            Solr.nextPageAndUpdate(pageNumber);
        }

        function changeRows(diff) {
            Solr.changeRowsAndUpdate(diff);
        }

        function showAllRows() {
            Solr.showAllRowsAndUpdate();
        }

        function resetFilterQueries() {
            Solr.resetFilterQueriesAndUpdate();
        }

        function changeFacetCount(facetName, diff) {
            Solr.changeFacetCountAndUpdate(facetName, diff);
        }

        function changeFacetSorting(facetName, sorting) {
            Solr.changeFacetSortingAndUpdate(facetName, sorting);
        }

        function rmFilterQuery(name, value) {
            Solr.rmFilterQueryAndUpdate(name, value);
        }

        function addFilterQuery(name, value) {
            Solr.addFilterQueryAndUpdate(name, value);
        }

        function overrideFilterQuery(name, value) {
            Solr.overrideFilterQueryAndUpdate(name, value);
        }

        function setSearch(search) {
            Solr.setSearchAndUpdate(search);
            vm.search = search;
        }

        function clearSearch() {
            Solr.clearSearchAndUpdate();
            vm.search = '';
        }

        function update() {
            Solr.update();
        }

        var listener = $scope.$on('solrDataUpdate', function(event, data) {
            vm.filterQueries = Solr.getFilterQueries();
            vm.data = Solr.getData();
            vm.params = Solr.getParams();
        });

        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            listener();
            killerListener();
        });

    }
})();
