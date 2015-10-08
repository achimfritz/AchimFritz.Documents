/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('WidgetController', WidgetController);

    /* @ngInject */
    function WidgetController (CONFIG, $rootScope) {

        var vm = this;
        vm.widgets = {
            solrIntegrity: false,
            filterNavSelect: false,
            clipboard: false,
            filterNav: false,
            integrity: false
        };

        // used by the view
        vm.closeWidget = closeWidget;
        vm.openWidget = openWidget;
        vm.getTemplate = getTemplate;

        function getTemplate(name) {
            return CONFIG.templatePath + 'Image/Widget/' + name + '.html';
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
