/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageListController', ImageListController);

    /* @ngInject */
    function ImageListController (FlashMessageService, DocumentListRestService) {

        var vm = this;
        vm.big = false;

        // used by the view
        vm.showBig = showBig;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
        }

        function showBig(showBig) {
            vm.big = showBig;
        }

    }
})();
