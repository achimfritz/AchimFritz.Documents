/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.widget')
        .provider('WidgetConfiguration', WidgetConfiguration);

    function WidgetConfiguration() {

        var widgets = {};
        var namespace = '';

        // Public API
        this.$get = function () {
            return {
                getWidgets: function () {
                    return widgets;
                },
                setWidgets: function (newWidgets) {
                    widgets = newWidgets;
                },
                getNamespace: function () {
                    return namespace;
                },
                setNamespace: function (newNamespace) {
                    namespace = newNamespace;
                }
            }
        };

    }
}());
