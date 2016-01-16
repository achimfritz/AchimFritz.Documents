/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageController', ImageController);

    /* @ngInject */
    function ImageController ($rootScope, CONFIG, ngDialog, hotkeys, Solr) {

        var vm = this;
        var $scope = $rootScope.$new();
        vm.templatePaths = {};
        vm.finished = true;
        vm.mode = 'view';
        vm.imageHeight = 240;
        vm.data = {};
        vm.strgPressed = false;
        vm.shiftPressed = false;

        // used by the view
        vm.itemClick = itemClick;
        vm.changeImageSize = changeImageSize;
        vm.openImageModal = openImageModal;
        vm.itemClickSelect = itemClickSelect;
        vm.emptyList = emptyList;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.data = Solr.getData();
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

        function emptyList() {
            angular.forEach(vm.data.response.docs, function (val, key) {
                val.selected = '';
            });
            // TODO
            $rootScope.$emit('docsUpdate', vm.data.response.docs);
        }

        function itemClickSelect (doc) {
            var docs = vm.data.response.docs;
            if (doc.selected === 'selected') {
                doc.selected = '';
            } else {
                if (vm.strgPressed === false && vm.shiftPressed === false) {
                    // rm all others
                    angular.forEach(docs, function (val, key) {
                        if (doc.identifier !== val.identifier) {
                            val.selected = '';
                        }
                    });
                } else if (vm.shiftPressed === true) {
                    // select all from last selected
                    var collect = false;
                    for (var i = ( docs.length - 1 ); i >= 0; i--) {
                        var el = docs[i];
                        if (el.identifier === doc.identifier) {
                            collect = true;
                        }
                        if (collect === true) {
                            if (el.selected === 'selected') {
                                collect = false;
                            }
                            el.selected = 'selected';
                        }

                    }
                }
                // add always me
                doc.selected = 'selected';
            }
            $rootScope.$emit('docsUpdate', docs);
        }

        function changeImageSize(diff) {
            vm.imageHeight += diff;
        }

        function itemClick(doc) {
            if (vm.mode === 'select') {
                return vm.itemClickSelect(doc);
            } else {
                return vm.openImageModal(doc);
            }
        }

        function openImageModal(doc) {
            $scope.dialog = ngDialog.open({
                "data": doc,
                "template": CONFIG.templatePath + 'Image/ImageModal.html',
                "controller": 'ImageModalController',
                "controllerAs": 'ctrl',
                "scope": $scope
            });
        }

        var listener = $scope.$on('solrDataUpdate', function (event, data) {
            vm.data = Solr.getData();
        });

        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            listener();
            killerListener();
        });

    }
})();
