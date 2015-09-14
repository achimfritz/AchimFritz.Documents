/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageController', ImageController);

    /* @ngInject */
    function ImageController ($rootScope, FlashMessageService, Solr, CONFIG, ngDialog) {

        var vm = this;
        var $scope = $rootScope.$new();
        vm.templatePaths = {};
        vm.finished = true;
        vm.mode = 'view';
        vm.imageWidth = 320;

        // used by the view
        vm.getTemplate = getTemplate;
        vm.itemClick = itemClick;
        vm.changeImageSize = changeImageSize;
        vm.openImageModal = openImageModal;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;

        vm.initController();

        function initController() {

        }

        function changeImageSize(diff) {
            vm.imageWidth += diff;
        }

        function itemClick(doc) {
            return vm.openImageModal(doc);
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
        })

    }
})();
