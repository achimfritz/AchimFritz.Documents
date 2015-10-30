/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .provider('AppConfiguration', AppConfiguration);

    function AppConfiguration() {

        var namespace = '';

        var documentRoot = '';

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
                setDocumentRoot: function (newDocumentRoot) {
                    documentRoot = newDocumentRoot;
                },
                getDocumentRoot: function () {
                    return documentRoot;
                },
                setNamespace: function (newNamespace) {
                    namespace = newNamespace;
                }
            }
        };

    }
}());
