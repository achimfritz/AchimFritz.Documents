/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.document')
        .controller('DocumentController', DocumentController);

    /* @ngInject */
    function DocumentController (Solr, CONFIG, $rootScope) {

        var vm = this;

        vm.filterQueries = {};
        vm.data = {};
        vm.search = '';
        vm.params = {};

        /**
        vm.templatePaths = {
            filter: CONFIG.templatePath + 'Document/Filter.html',
            filterQuery: CONFIG.templatePath + 'Document/FilterQuery.html',
            resultTable: CONFIG.templatePath + 'Document/ResultTable.html'
        };
         */

        // used by the view
        vm.addFilterQuery = addFilterQuery;
        vm.rmFilterQuery = rmFilterQuery;
        vm.update = update;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.params = Solr.getParams();
            vm.filterQueries = Solr.getFilterQueries();
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

        function update() {

            if (vm.search !== undefined) {
                if (vm.search !== '') {
                    Solr.setParam('q', vm.search);
                } else {
                    Solr.setParam('q', '*:*');
                }
            }

            Solr.forceRequest().then(function (response){
                //vm.data = response.data;
                $rootScope.$emit('solrDataUpdate', response.data);
            })
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            vm.filterQueries = Solr.getFilterQueries();
            vm.data = data;
        });

    }
})();
