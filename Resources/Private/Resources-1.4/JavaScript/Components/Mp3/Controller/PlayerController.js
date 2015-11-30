/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('PlayerController', PlayerController);

    /* @ngInject */
    function PlayerController (Mp3PlayerService) {

        var vm = this;

        // used by the view
        vm.playAllDocumentList = playAllDocumentList;
        vm.playAll = playAll;
        vm.addAll = addAll;
        vm.getPlaylistDocs = getPlaylistDocs;

        function playAll(docs) {
            Mp3PlayerService.playAll(docs);
        }

        function addAll(docs) {
            Mp3PlayerService.addAll(docs);
        }

        function playAllDocumentList(documentListItems) {
            Mp3PlayerService.playAllDocumentList(documentListItems);
        }

        function getPlaylistDocs() {
            return Mp3PlayerService.getPlaylistDocs();
        }

    }
})();
