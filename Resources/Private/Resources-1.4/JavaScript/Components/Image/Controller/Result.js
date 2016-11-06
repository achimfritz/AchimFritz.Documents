/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageResultController', ImageResultController);

    /* @ngInject */
    function ImageResultController (ResultFactory, CONFIG, $rootScope, hotkeys, ngDialog, Solr) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.params = {};
        vm.random = 0;
        vm.result = {};
        vm.imageHeight = 240;
        vm.strgPressed = false;
        vm.shiftPressed = false;
        vm.mode = 'view';

        // used by the view
        vm.itemClick = itemClick;

        vm.newRandom = newRandom;
        vm.nextPage = nextPage;
        vm.update = update;

        vm.showAllRows =  showAllRows;
        vm.changeRows = changeRows;
        vm.changeImageSize = changeImageSize;


        initController();

        function initController() {
            getSolrData();
            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            hotkeys.bindTo($scope).add({
                combo: 'ctrl',
                action: 'keydown',
                callback: function () {
                    vm.strgPressed = true;
                }
            }).add({
                combo: 'ctrl',
                action: 'keyup',
                callback: function () {
                    vm.strgPressed = false;
                }
            }).add({
                combo: 'shift',
                action: 'keyup',
                callback: function () {
                    vm.shiftPressed = false;
                }
            }).add({
                combo: 'shift',
                action: 'keydown',
                callback: function () {
                    vm.shiftPressed = true;
                }
            });
        }

        function itemClick(doc) {
            if (vm.mode === 'select') {
                vm.result = ResultFactory.itemClick(doc, vm.strgPressed, vm.shiftPressed);
            } else {
                $scope.dialog = ngDialog.open({
                    data: doc,
                    template: CONFIG.templatePath + 'Image/Doc.html',
                    controller: 'ImageDocController',
                    controllerAs: 'imageDoc',
                    className: 'image-doc',
                    scope: $scope
                });
            }
        }

        function newRandom() {
            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            vm.random = Solr.newRandomAndUpdate(vm.random);
        }

        function update() {
            Solr.update();
        }

        function nextPage(pageNumber) {
            var start = (pageNumber -1).toString();
            if (start !== vm.params['start']) {
                Solr.nextPageAndUpdate(pageNumber);
            }
        }
        function showAllRows() {
            Solr.showAllRowsAndUpdate();
        }
        function changeRows(diff) {
            Solr.changeRowsAndUpdate(diff);
        }

        function changeImageSize(diff) {
            vm.imageHeight += diff;
        }


        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                vm.params = Solr.getParams();
                vm.result = ResultFactory.updateResult(data);
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
