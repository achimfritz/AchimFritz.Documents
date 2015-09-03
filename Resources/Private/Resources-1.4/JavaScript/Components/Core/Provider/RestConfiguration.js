/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .provider('RestConfiguration', RestConfiguration);

    function RestConfiguration() {

        var settings = {
            'restBaseUrl': 'achimfritz.documents',
            'documentListResource': 'documentlist',
            'documentListMergeResource': 'documentlistmerge',
            'documentListRemoveResource': 'documentlistremove'
        };

        // Public API
        this.$get = function () {
            return {
                getSettings: function () {
                    return settings;
                },
                getSetting: function (name) {
                    return settings[name];
                },
                setSetting: function (name, value) {
                    settings[name] = value;
                }
            }
        };

    }
}());
