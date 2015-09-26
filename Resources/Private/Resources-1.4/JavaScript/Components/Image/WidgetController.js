/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('WidgetController', WidgetController);

    /* @ngInject */
    function WidgetController () {

        var vm = this;
        vm.widgets = {
            solrIntegrity: false,
            filterNavSelect: true,
            integrity: false
        };

        // used by the view
        vm.closeWidget = closeWidget;
        vm.openWidget = openWidget;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {

        }

        function closeWidget(widget) {
            vm.widgets[widget] = false;
        }

        function openWidget(widget) {
            vm.widgets[widget] = true;
        }

    }
})();
