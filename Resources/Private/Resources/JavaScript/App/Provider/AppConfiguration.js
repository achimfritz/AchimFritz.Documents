/* global angular */

(function () {
    'use strict';

    angular
        .module('app')
        .provider('AppConfiguration', AppConfiguration);

    function AppConfiguration() {

        var settings = {
            'restBaseUrl': 'achimfritz.documents',
            'documentListResource': 'documentlist',
            'documentListMergeResource': 'documentlistmerge',
            'documentListRemoveResource': 'documentlistremove'
        };

        this.setSetting = function (name, value) {
            settings[name] = value;
        };

        this.setSetting = function (name, value) {
            settings[name] = value;
        };

        var getSettings = function () {
            return settings;
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
