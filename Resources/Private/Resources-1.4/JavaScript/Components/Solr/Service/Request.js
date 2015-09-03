/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .service('Request', Request);

    function Request($q, $http) {

        var result = {},
            initialized = false,
            loading = false,
            waiting = [];

        var self = this;
        self.request = request;
        self.forceRequest = forceRequest;

        function forceRequest (url) {
            initialized = false;
            loading = false;
            result = {};
            return request(url);
        }

        function request(url) {
            var deferred = $q.defer();

            if (initialized && !loading) {
                deferred.resolve(result);
            } else if (!loading) {
                loading = true;
                initialized = false;
                //console.log(url);
                $http.jsonp(url).then(
                    function (response) {
                        result = response;
                        deferred.resolve(response);
                        loading = false;
                        initialized = true;
                        waiting.forEach(function(promise) {
                            promise.resolve(response);
                        });
                    },
                    function (data, status, header, config) {
                        deferred.reject(data, status, header, config);
                        loading = false;
                        waiting.forEach(function(promise) {
                            promise.reject(data, status, header, config);
                        });
                    }
                );
            } else {
                waiting.push(deferred);
            }
            return deferred.promise;

        }

    }
}());
