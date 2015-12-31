/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicEditPlaylistController', MusicEditPlaylistController);

    /* @ngInject */
    function MusicEditPlaylistController ($scope) {

        var vm = this;
        vm.playlist = $scope.ngDialogData;

        vm.zip = {
            'name': 'download',
            'useThumb': false,
            'useFullPath': false
        };
        vm.tagPath = '';

        vm.getPlaylistDocs = getPlaylistDocs;

        docUpdate();

        function docUpdate() {
            var first = vm.playlist[0];
            var doc = first.doc;
            vm.zip.name = doc.fsArtist + '_' + doc.fsAlbum;
            vm.zip.name = vm.zip.name.replace(/ /g, '');
        }

        function getPlaylistDocs() {
            var docs = [];
            angular.forEach(vm.playlist, function (val, key) {
                docs.push(val.doc);
            });
            return docs;
        }


    }
})();
