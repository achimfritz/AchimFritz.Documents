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
        vm.filters = {};
        vm.namespace = '';

        // used by the view
        vm.closeWidget = closeWidget;
        vm.openWidget = openWidget;
        vm.getTemplate = getTemplate;
        vm.getWidgetTemplate = getWidgetTemplate;
        vm.toggleWidget = toggleWidget;
        vm.toggleFilter = toggleFilter;

        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.namespace = WidgetConfiguration.getNamespace();
            vm.widgets = WidgetConfiguration.getWidgets();
            vm.filters = WidgetConfiguration.getFilters();
        }

        function toggleFilter(name) {
            if (vm.filters[name] === false) {
                vm.filters[name] = true;
            } else {
                vm.filters[name] = false;
            }
        }

        function getTemplate(name) {
            return CONFIG.templatePath + vm.namespace + '/Widget/' + name + '.html';
        }

        function getWidgetTemplate(name) {
            return CONFIG.templatePath + 'Widget/' + name + '.html';
        }

        function closeWidget(widget) {
            vm.widgets[widget] = false;
        }

        function toggleWidget(widget) {
            if (vm.widgets[widget] === true) {
                vm.widgets[widget] = false;
            } else {
                vm.widgets[widget] = true;
            }

        }

        function openWidget(widget) {
            vm.widgets[widget] = true;
        }

        $rootScope.$on('openWidget', function (ev, widget) {
            vm.openWidget(widget);
        });

    }
})();
