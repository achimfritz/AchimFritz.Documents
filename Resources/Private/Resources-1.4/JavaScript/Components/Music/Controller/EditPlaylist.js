/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicEditPlaylistController', MusicEditPlaylistController);

    /* @ngInject */
    function MusicEditPlaylistController ($scope, CoreApiService, ngDialog, $rootScope) {

        var vm = this;
        var docs = [];
        var $localScope = $rootScope.$new();

        vm.view = {
            tag: '',
            list: '',
            zip: {
                name: 'download',
                useThumb: false,
                useFullPath: false
            }
        };

        vm.actions = {
            updateId3Tag: updateId3TagAction,
            zipDownload: zipDownloadAction,
            listMerge: listMergeAction
        };

        initController();

        function initController() {
            angular.forEach($scope.ngDialogData, function (val, key) {
                docs.push(val.doc);
            });
            var name = docs[0].fsArtist + '_' + docs[0].fsAlbum;
            vm.view.zip.name = name.replace(/ /g, '');
        }

        function updateId3TagAction() {
            CoreApiService.writeId3Tag(vm.view.tag, docs);
        }

        function listMergeAction() {
            CoreApiService.listMerge(vm.view.list, docs);
        }

        function zipDownloadAction() {
            CoreApiService.zipDownload(vm.view.zip, docs);
        }

        var listener = $localScope.$on('core:apiCallSuccess', function(event, data) {
            ngDialog.close($scope.dialog.id);
            listener();
        });
    }
})();
