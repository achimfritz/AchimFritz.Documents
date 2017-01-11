/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicEditDocController', MusicEditDocController);

    /* @ngInject */
    function MusicEditDocController ($scope, ngDialog, $rootScope, CoreApiService, Solr) {

        var vm = this;
        var $listenerScope = $rootScope.$new();

        // api
        vm.rate = rate;
        vm.cddbUpdate = cddbUpdate;
        vm.folderUpdate = folderUpdate;
        vm.listMergeOne = listMergeOne;
        vm.updateId3Tag = updateId3Tag;

        vm.cddbSearch = '';
        vm.category = '';

        vm.doc = $scope.ngDialogData;

        vm.cddb = {
            'path': '',
            'format': '1',
            'url': ''
        };
        vm.folder = {
            'path': '',
            'url': ''
        };

        function updateId3Tag(mp3DocumentId3Tag) {
            CoreApiService.updateId3Tag(mp3DocumentId3Tag);
        }
        function rate(rate) {
            CoreApiService.rate(rate);
        }

        function cddbUpdate() {
            CoreApiService.cddbUpdate(vm.cddb);
        }

        function folderUpdate() {
            CoreApiService.folderUpdate(vm.folder);
        }

        function listMergeOne() {
            CoreApiService.listMergeOne(vm.category, vm.doc);
        }

        docUpdate();

        function docUpdate() {
            vm.cddb.path = vm.doc.mainDirectoryName;
            vm.cddbSearch = vm.doc.fsArtist + ' ' + vm.doc.fsAlbum;
            vm.folder.path = vm.doc.mainDirectoryName;
        }

        var listener = $listenerScope.$on('core:apiCallSuccess', function(event, data) {
            Solr.update();
        });

        var dialogListener = $listenerScope.$on('ngDialog.closing', function (e, $dialog) {
            solrListener();
            listener();
            dialogListener();
        });

        var solrListener = $listenerScope.$on('solrDataUpdate', function (event, data) {
            var found = false;
            angular.forEach(data.response.docs, function(doc) {
                if (doc.identifier === vm.doc.identifier) {
                    vm.doc = doc;
                    docUpdate();
                }
            });
        });


    }
})();
