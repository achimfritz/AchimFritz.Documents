/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageResultController', ImageResultController);

    /* @ngInject */
    function ImageResultController (ResultFactory, CONFIG, $rootScope, hotkeys, ngDialog, Solr, CoreApiService) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.coreApi = CoreApiService;

        vm.params = {};
        vm.random = 0;
        vm.result = {};
        vm.imageHeight = 240;
        vm.strgPressed = false;
        vm.shiftPressed = false;
        vm.mode = 'view';
        // clipboard
        vm.form = 'close';
        vm.category = '';
        vm.pdf = {
            'columns': 3,
            'size': 4,
            'dpi': 72
        };
        vm.zip = {
            'name': 'download',
            'useThumb': false,
            'useFullPath': false
        };

        vm.solr = Solr;

        // used by the view
        vm.itemClick = itemClick;
        vm.showForm = showForm;

        vm.newRandom = newRandom;
        vm.nextPage = nextPage;

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
                    template: CONFIG.templatePath + 'Image/ImageDoc.html',
                    controller: 'ImageDocController',
                    controllerAs: 'imageDoc',
                    className: 'image-doc',
                    scope: $scope
                });
            }
        }

        function showForm(form) {
            vm.form = form;
        }

        function newRandom() {
            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            vm.random = Solr.newRandomAndUpdate(vm.random);
        }

        function nextPage(pageNumber) {
            var start = (pageNumber -1).toString();
            if (start !== vm.params['start']) {
                Solr.nextPageAndUpdate(pageNumber);
            }
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
