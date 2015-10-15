/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .controller('WidgetController', WidgetController);

    /* @ngInject */
    function WidgetController (CONFIG, $rootScope) {

        var vm = this;
        vm.widgets = {
            solrIntegrity: false,
            filterNavSelect: false,
            clipboard: false,
            filterNav: false,
            integrity: false,
            docs: true
        };
        vm.namespace = "Image";

        vm.initController = initController;

        vm.initController();

        function initController() {
            console.log(vm.namespace);
        }

        // used by the view
        vm.closeWidget = closeWidget;
        vm.openWidget = openWidget;
        vm.getTemplate = getTemplate;

        function getTemplate(name, namespace) {
            if (angular.isUndefined(namespace)) {
                namespace = 'Image';
                return CONFIG.templatePath + namespace + '/Widget/' + name + '.html';
            } else {
                return CONFIG.templatePath + '/Mp3/' + name + '.html';
            }
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
