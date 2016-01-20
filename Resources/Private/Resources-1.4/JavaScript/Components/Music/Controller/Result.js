/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicResultController', MusicResultController);

    /* @ngInject */
    function MusicResultController (Mp3PlayerService, $location, $timeout, CONFIG, $rootScope, ngDialog, Solr) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.data = {};
        vm.params = {};
        vm.random = 0;

        // used by the view
        vm.playAll = playAll;
        vm.addAll = addAll;
        vm.playOne = playOne;
        vm.addOne = addOne;
        vm.editDoc = editDoc;


        vm.newRandom = newRandom;
        vm.nextPage = nextPage;
        vm.update = update;
        vm.showAllRows =  showAllRows;
        vm.changeRows = changeRows;
        vm.addFilterQuery = addFilterQuery;

        Mp3PlayerService.initialize();


        getSolrData();

        vm.random = Solr.newRandom();

        function newRandom() {
            vm.random = Solr.newRandomAndUpdate();
        }
        function update() {
            Solr.update();
        }
        function nextPage(pageNumber) {
            Solr.nextPageAndUpdate(pageNumber);
        }
        function showAllRows() {
            Solr.showAllRowsAndUpdate();
        }
        function changeRows(diff) {
            Solr.changeRowsAndUpdate(diff);
        }
        function addFilterQuery(name, value) {
            Solr.addFilterQueryAndUpdate(name, value);
        }

        function playAll(docs) {
            Mp3PlayerService.playAll(docs);
            $timeout(function () {
                $location.path(CONFIG.baseUrl + '/music/player');
                $rootScope.$broadcast('music:locationChanged', 'music/player');
            });
        }

        function addAll(docs) {
            Mp3PlayerService.addAll(docs);
        }

        function playOne(doc) {
            Mp3PlayerService.playOne(doc);
            $timeout(function () {
                $location.path(CONFIG.baseUrl + '/music/player');
                $rootScope.$broadcast('music:locationChanged', 'music/player');
            });
        }

        function addOne(doc) {
            Mp3PlayerService.addOne(doc);
        }

        function editDoc(doc) {
            $scope.dialog = ngDialog.open({
                "data" : doc,
                "template" : CONFIG.templatePath + 'Music/EditDoc.html',
                "controller" : 'MusicEditDocController',
                "controllerAs" : 'musicEditDoc',
                "scope" : $scope
            });
        }

        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                vm.data = Solr.getData();
                vm.params = Solr.getParams();
            }
        }

        var listener = $scope.$on('solrDataUpdate', function(event, data) {
            getSolrData();
        });

        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            listener();
            killerListener();
        });

    }
})();
