/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.document')
        .controller('DocumentIndexController', DocumentIndexController);

    /* @ngInject */
    function DocumentIndexController (Solr, $rootScope) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.filterQueries = {};
        vm.data = {};
        vm.search = '';
        vm.params = {};

        vm.solr = Solr;

        // used by the view
        vm.update = update;


        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.filterQueries = Solr.getFilterQueries();
            vm.data = Solr.getData();
            vm.params = Solr.getParams();
        }

        function update() {
            Solr.setSearchAndUpdate(vm.search);
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
