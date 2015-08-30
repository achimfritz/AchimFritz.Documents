/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.urlBuilder')
            .controller('UrlBuilderController', UrlBuilderController);

    /* @ngInject */
    function UrlBuilderController ($sce) {
        var vm = this;

        vm.url = '';
        vm.queryParts = [];
        vm.urlPath = '';
        vm.newQueryPart = {'key': '', 'val': ''};
        vm.iframe = '';

        // used by the view
        vm.updateUrl = updateUrl;
        vm.updateUrlPath = updateUrlPath;
        vm.removeQueryPart = removeQueryPart;
        vm.updateQueryPart = updateQueryPart;
        vm.addQueryPart = addQueryPart;

        // not used by the view
        vm.initController = initController;
        vm.buildUrl = buildUrl;
        vm.updateIframe = updateIframe;

        vm.initController();

        function initController() {
            vm.url = 'http://a10:8080/af/documents/mp3?facet.field=artist&facet.field=album&facet=true&q=*:*';
            updateUrl();
        }

        function addQueryPart() {
            vm.queryParts.push(vm.newQueryPart);
            vm.newQueryPart = {'key': '', 'val': ''};
            vm.buildUrl();
        }

        function updateIframe() {
            vm.iframe = $sce.trustAsResourceUrl(vm.url);
        }

        function buildUrl() {
            vm.url = vm.urlPath + '?';
            var first = true;
            angular.forEach(vm.queryParts, function(queryPart) {
                if (first === true){
                    vm.url = vm.url + queryPart.key + '=' + queryPart.val;
                } else {
                    vm.url = vm.url + '&' + queryPart.key + '=' + queryPart.val;
                }
                first = false;
            });
            vm.updateIframe();

        }

        function updateUrlPath() {
            vm.buildUrl();
        }

        function removeQueryPart(queryPart) {
            var newQueryParts = [];
            angular.forEach(vm.queryParts, function(part) {
                if (part.key !== queryPart.key || part.val !== queryPart.val) {
                    newQueryParts.push({'key': part.key, 'val': part.val});
                }
            });
            vm.queryParts = newQueryParts;
            vm.buildUrl();
        }

        function updateQueryPart() {
            vm.buildUrl();
        }

        function updateUrl() {
            var res = vm.url.split('?');
            vm.queryParts = [];
            vm.urlPath = res[0];
            if (angular.isDefined(res[1])) {
                var params = res[1].split('&');
                angular.forEach(params, function(val) {
                    var keyVal = val.split('=');
                    vm.queryParts.push({'key': keyVal[0], 'val': keyVal[1]})
                });
            }
            vm.updateIframe();
        }

    }
})();
