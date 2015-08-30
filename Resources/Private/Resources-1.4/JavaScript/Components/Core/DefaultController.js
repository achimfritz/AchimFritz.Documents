/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .controller('DefaultController', DefaultController);

    /* @ngInject */
    function DefaultController () {
        var vm = this;


        // used by the view

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
        }

    }
})();
