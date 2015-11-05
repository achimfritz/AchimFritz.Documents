/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.filter')
        .controller('FilterController', FilterController);

    /* @ngInject */
    function FilterController (FilterConfiguration) {

        var vm = this;
        vm.filters = {};

        // used by the view
        vm.toggleFilter = toggleFilter;

        vm.initController = initController;

        vm.initController();

        function initController() {
             vm.filters = FilterConfiguration.getFilters();
        }

        function toggleFilter(name) {
            if (vm.filters[name] === false) {
                vm.filters[name] = true;
            } else {
                vm.filters[name] = false;
            }
        }

    }
})();
