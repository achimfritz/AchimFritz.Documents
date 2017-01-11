/* global angular */

(function () {
    'use strict';



    angular
        .module('achimfritz.core')
        .factory('CoreApplicationScopeFactory', CoreApplicationScopeFactory);

    function CoreApplicationScopeFactory($rootScope) {

        var applicationScope = null;
        var listener = [];

        return {
            getApplicationScope: function() {
                if (applicationScope === null) {
                    applicationScope = $rootScope.$new();
                }
                return applicationScope;
            },
            registerListener: function(namespace, name, callback) {

                if (applicationScope === null) {
                    applicationScope = $rootScope.$new();
                }

                if (angular.isUndefined(listener[namespace])) {
                    listener[namespace] = [];
                }
                if (angular.isUndefined((listener[namespace][name]))) {
                    listener[namespace][name] = true;
                    applicationScope.$on(name, callback);
                }
            }
        };



    }

}());
