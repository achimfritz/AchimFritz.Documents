/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageController', ImageController);

    /* @ngInject */
    function ImageController ($rootScope, FlashMessageService, Solr, CONFIG) {

        var vm = this;
        vm.templatePaths = {};
        vm.finished = true;

        // used by the view
        vm.getTemplate = getTemplate;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;

        vm.initController();

        function initController() {

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

        $rootScope.$on('solrDataUpdate', function (event, data) {
        })

    }
})();
