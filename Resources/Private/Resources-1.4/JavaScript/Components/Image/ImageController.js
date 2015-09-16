/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageController', ImageController);

    /* @ngInject */
    function ImageController ($rootScope, FlashMessageService, Solr, CONFIG, ngDialog, hotkeys) {

        var vm = this;
        var $scope = $rootScope.$new();
        vm.templatePaths = {};
        vm.finished = true;
        vm.mode = 'view';
        vm.imageWidth = 320;
        vm.data = {};
        vm.strgPressed = false;
        vm.shiftPressed = false;

        // used by the view
        vm.getTemplate = getTemplate;
        vm.itemClick = itemClick;
        vm.changeImageSize = changeImageSize;
        vm.openImageModal = openImageModal;
        vm.itemClickSelect = itemClickSelect;
        vm.emptyList = emptyList;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;

        vm.initController();

        function initController() {

            vm.mode = 'select';

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
                action: 'keyup',
                callback: function () {
                    vm.shiftPressed = false;
                }
            });

            Solr.request().then(function (response){
                vm.data = response.data;
            })
        }

        function emptyList() {
            angular.forEach(vm.data.response.docs, function (val, key) {
                val.selected = '';
            });
            $rootScope.$emit('docsUpdate', vm.data.response.docs);
        }

        function itemClickSelect (doc) {
            console.log(vm.shiftPressed);
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
        };

        function changeImageSize(diff) {
            vm.imageWidth += diff;
        }

        function itemClick(doc) {
            if (vm.mode === 'select') {
                return vm.itemClickSelect(doc);
            } else {
                return vm.openImageModal(doc);
            }
        }

        function getTemplate(name) {
            return CONFIG.templatePath + 'Image/' + name + '.html';
        }

        function restSuccess(data) {
            vm.finished = true;
            FlashMessageService.show(data.data.flashMessages);
            Solr.forceRequest().then(function (response) {
                $rootScope.$emit('solrDataUpdate', response.data);
            })
        }

        function restError(data) {
            vm.finished = true;
            FlashMessageService.error(data);
        }

        function openImageModal(doc) {
            $scope.dialog = ngDialog.open({
                "data": doc,
                "template": vm.getTemplate('ImageModal'),
                "controller": 'ImageModalController',
                "controllerAs": 'ctrl',
                "scope": $scope
            });
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            vm.data = data;
        })

    }
})();
