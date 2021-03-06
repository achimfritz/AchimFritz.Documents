/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageNavigationController', ImageNavigationController);

    /* @ngInject */
    function ImageNavigationController ($location, CONFIG, $rootScope, Solr, ngDialog, CoreApplicationScopeFactory, $timeout) {

        var vm = this;
        var $scope = $rootScope.$new();

        /* navigation */
        vm.current = {location: ''};
        vm.items = [
            {name: 'home', location: 'index'},
            {name: 'result', location: 'image/result'},
            {name: 'filter', location: 'image/filter'},
            {name: 'docList', location: 'image/docList'},
            {name: 'integrity', location: 'image/integrity'},
            {name: 'sitemap', location: 'image/sitemap'}
        ];
        vm.forward = forward;

        /* solr */
        vm.search = '';
        vm.params = {};
        vm.filterQueries = {};
        vm.solr = Solr;

        vm.clearSearch = clearSearch;
        vm.showSolrIntegrity = showSolrIntegrity;

        initController();

        function initController() {
            getSolrData();

            var path = $location.path();
            if (path === CONFIG.baseUrl + '/image' || path === CONFIG.baseUrl + '/image/') {
                $timeout(function () {
                    forward('image/result');
                });
            }
            vm.current.location = path.replace(CONFIG.baseUrl + '/', '');
        }

        function showSolrIntegrity() {
            $scope.dialog = ngDialog.open({
                template: CONFIG.templatePath + 'Image/SolrIntegrity.html',
                controller: 'ImageSolrIntegrityController',
                controllerAs: 'imageSolrIntegrity',
                scope: $scope
            });
        }

        function forward(newLocation) {
            vm.current.location = newLocation;
            $location.path('app/' + newLocation);
        }

        function clearSearch() {
            Solr.clearSearchAndUpdate();
            vm.search = '';
        }

        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                vm.filterQueries = Solr.getFilterQueries();
                vm.params = Solr.getParams();
                if (vm.params.q !== '*:*') {
                    vm.search = vm.params.q;
                }
            }
        }

        /* listener */

        var listener = $scope.$on('solrDataUpdate', function(event, data) {
            getSolrData();
        });

        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            var path = next.split('/app/')
            vm.current.location = path[1];
            listener();
            killerListener();
        });

    }
})();
