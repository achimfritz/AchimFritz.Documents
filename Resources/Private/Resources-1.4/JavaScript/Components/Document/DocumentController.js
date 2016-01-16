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
        vm.update = update;
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


        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            vm.filterQueries = Solr.getFilterQueries();
            vm.data = Solr.getData();
            vm.params = Solr.getParams();
        }

        function newRandom() {
            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            Solr.setParam('sort', vm.random);
            vm.update();
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

        function clearSearch() {
            Solr.setParam('q', '*:*');
            vm.search = '';
            vm.update();
        }

        function update() {
            if (vm.search !== undefined) {
                if (vm.search !== '') {
                    Solr.setParam('q', vm.search);
                }
            }

            Solr.forceRequest().then(function (response) {
                Solr.setData(response.data);
            })
        }

        var listener = $scope.$on('solrDataUpdate', function (event, data) {
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
