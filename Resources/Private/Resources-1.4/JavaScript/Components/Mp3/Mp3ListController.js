/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3ListController', Mp3ListController);

    /* @ngInject */
    function Mp3ListController (AppConfiguration) {

        var vm = this;

        vm.initController = initController;

        vm.initController();

        function initController() {
            AppConfiguration.setNamespace('mp3');
        }

    }
})();
