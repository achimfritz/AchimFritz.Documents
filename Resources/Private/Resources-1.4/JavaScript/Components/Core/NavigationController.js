/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .controller('NavigationController', NavigationController);

    /* @ngInject */
    function NavigationController (CONFIG) {

        var vm = this;

        vm.urls = [];


        // used by the view

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.urls = [
                {name: 'home', url: CONFIG.baseUrl + '/index'},
                {name: 'url builder', url: CONFIG.baseUrl + '/urlbuilder'},
                {name: 'document', url: CONFIG.baseUrl + '/document'}
            ];
        }

    }
})();
