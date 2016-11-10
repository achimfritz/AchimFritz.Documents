/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageDocListController', ImageDocListController);

    /* @ngInject */
    function ImageDocListController (AppConfiguration) {

        var vm = this;
        vm.big = false;

        // used by the view
        vm.showBig = showBig;

        initController();

        function initController() {
            AppConfiguration.setNamespace('image');
        }

        function showBig(showBig) {
            vm.big = showBig;
        }

    }
})();
