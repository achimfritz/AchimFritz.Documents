/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageDocController', ImageDocController);

    /* @ngInject */
    function ImageDocController ($scope, hotkeys, ResultFactory, ngDialog, CONFIG, $timeout) {

        var vm = this;
        var currentDialog = null;
        vm.doc = $scope.ngDialogData;

        vm.fullPath = fullPath;
        vm.editDoc = editDoc;
        vm.closeDialog = closeDialog;

        
        initController();

        function initController() {
            hotkeys.bindTo($scope).add({
                combo: 'n',
                description: 'next',
                callback: function () {
                    vm.doc = ResultFactory.getNext(vm.doc);
                    updateCurrentDialogs();
                }
            }).add({
                combo: 'b',
                callback: function () {
                    vm.doc = ResultFactory.getPrev(vm.doc);
                    updateCurrentDialogs();
                }
            });
        }

        function closeDialog() {
            $scope.closeThisDialog();
        }

        function updateCurrentDialogs() {
            if (vm.doc === false) {
                if (currentDialog !== null) {
                    currentDialog.close();
                }
                $scope.closeThisDialog();
            }
            else if (currentDialog !== null) {
                currentDialog.close();
                currentDialog = ngDialog.open({
                    data: vm.doc,
                    template: CONFIG.templatePath + 'Image/EditDoc.html',
                    controller: 'ImageEditDocController',
                    controllerAs: 'imageEditDoc',
                    scope: $scope
                });
            }
        }

        $scope.$on('ngDialog.closing', function (e, $dialog) {
            currentDialog = null;
        });

        function editDoc() {
            currentDialog = ngDialog.open({
                data: vm.doc,
                template: CONFIG.templatePath + 'Image/EditDoc.html',
                controller: 'ImageEditDocController',
                controllerAs: 'imageEditDoc',
                scope: $scope
            });
        }
        
        function fullPath() {
            // TODO
            return 'thumbs/1920x1080/' + vm.doc.name;
            //return 'thumbs/1280x1024/' + vm.doc.name;
            
        }

    }
})();
