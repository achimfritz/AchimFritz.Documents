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
            filterNavSelect: true
        };

        // used by the view
        vm.closeWidget = closeWidget;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {

        }

        function closeWidget(widget) {
            vm.widgets[widget] = false;
        }

    }
})();
