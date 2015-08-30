/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.document')
        .controller('DocumentController', DocumentController);

    /* @ngInject */
    function DocumentController (Solr) {
        var vm = this;


        // used by the view

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
        }

    }
})();
