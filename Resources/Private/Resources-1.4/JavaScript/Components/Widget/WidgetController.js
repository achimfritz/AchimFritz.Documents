/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.widget')
        .controller('WidgetController', WidgetController);

    /* @ngInject */
    function WidgetController (CONFIG, $rootScope, WidgetConfiguration) {

        var vm = this;
        vm.widgets = {};
        vm.namespace = '';

        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.namespace = WidgetConfiguration.getNamespace();
            vm.widgets = WidgetConfiguration.getWidgets();
        }

        // used by the view
        vm.closeWidget = closeWidget;
        vm.openWidget = openWidget;
        vm.getTemplate = getTemplate;

        function getTemplate(name) {
            return CONFIG.templatePath + vm.namespace + '/Widget/' + name + '.html';
        }

        function closeWidget(widget) {
            vm.widgets[widget] = false;
        }

        function openWidget(widget) {
            vm.widgets[widget] = true;
        }

        $rootScope.$on('openWidget', function (ev, widget) {
            vm.openWidget(widget);
        });

    }
})();
