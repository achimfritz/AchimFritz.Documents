/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ClipboardController', ClipboardController);

    /* @ngInject */
    function ClipboardController ($rootScope, FlashMessageService, ExportRestService, DocumentListRestService, DocumentCollectionRestService) {

        var vm = this;
        vm.docs = [];
        vm.form = 'category';
        vm.category = '';
        vm.zip = {};
        vm.pdf = {};


        // used by the view
        vm.showForm = showForm;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
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
        }

        function showForm(form) {
            vm.form = form;
        }

        $rootScope.$on('docsUpdate', function (event, docs) {
            vm.docs = [];
            angular.forEach(docs, function (val, key) {
                if (val.selected === 'selected') {
                   vm.docs.push(val);
                }
            });
            if (vm.docs.length > 0) {
                $rootScope.$emit('openWidget', 'clipboard');
            }
        })

    }
})();
