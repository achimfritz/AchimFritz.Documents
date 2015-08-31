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
        vm.changeRows = changeRows
        vm.changeFacetCount = changeFacetCount;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.params = Solr.getParams();
            vm.filterQueries = Solr.getFilterQueries();
            Solr.request().then(function (response){
                vm.data = response.data;
            })
        }

        function changeRows(diff) {
            var newVal = vm.params['rows'] + diff;
            Solr.setParam('rows', newVal);
            update();
        }

        function changeFacetCount(facetName, diff) {
            var solrKey = 'f_' + facetName + '_facet_limit';
            var newVal = vm.params['facet_limit'] + diff;
            if (angular.isDefined(vm.params[solrKey])) {
                newVal = vm.params[solrKey] + diff;
            }
            if (newVal > 0) {
                Solr.setParam(solrKey, newVal);
                update();
            }
        }

        function rmFilterQuery(name, value) {
            Solr.rmFilterQuery(name, value);
            vm.update();
        }

        function addFilterQuery(name, value) {
            Solr.addFilterQuery(name, value);
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
