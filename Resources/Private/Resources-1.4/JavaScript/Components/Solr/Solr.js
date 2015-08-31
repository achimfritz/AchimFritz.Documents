/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .service('Solr', Solr);

    function Solr($q, $rootScope) {

        var self = this;

        self.solrSettings = {
            'solrUrl': 'http://localhost:8080/af/documents/',
            'servlet': 'select',
            'debug': true
        };

        self.settings = {
            'rows': 10,
            'q': '*:*',
            'facet_limit': 5,
            'sort': 'mDateTime desc',
            'start': 0,
            'facet': true,
            'json.nl': 'map',
            'facet_mincount': 1

        };

        self.request = request;
        self.buildUrl = buildUrl;

        self.manager = new AjaxSolr.Manager(self.solrSettings);
        self.manager.init();

        function getSolrSettings () {
            var res = {};
            angular.forEach(self.settings, function (val, key) {
                var a = key.replace(/_/g, '.');
                res[a] = val;

            });
            return res;
        }

        function request() {

            var solrSettings = getSolrSettings();
            angular.forEach(solrSettings, function (val, key) {
                self.manager.store.addByValue(key, val);
            });

            var defer = $q.defer();

            /*
            var manager = new AjaxSolr.Manager(self.solrSettings);
            manager.init();
            var url = manager.buildUrl();
            */

            var url = self.manager.buildUrl();
            $http.jsonp(url).then(function (data) {
                defer.resolve(data);
            });
            return defer.promise;
        }

        function buildUrl() {

        }

    }
}());
