/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .provider('AppConfiguration', AppConfiguration);

    function AppConfiguration() {

        var namespace = '';

        var applicationRoot = '';

        var restBaseUrl = 'achimfritz.documents';

        var resources = {
            'documentListResource': 'documentlist',
            'documentListMergeResource': 'documentlistmerge',
            'documentListRemoveResource': 'documentlistremove'
        };

        // Public API
        this.$get = function () {
            return {
                getResourceUrl: function (name) {
                    return restBaseUrl + '/' + resources[name] + '/';
                },
                getNamespacedResourceUrl: function (name) {
                    return restBaseUrl + '/' + namespace + resources[name] + '/';
                },
                setApplicationRoot: function (newApplicationRoot) {
                    applicationRoot = newApplicationRoot;
                },
                getApplicationRoot: function () {
                    return applicationRoot;
                },
                setNamespace: function (newNamespace) {
                    namespace = newNamespace;
                }
            }
        };

    }
}());
