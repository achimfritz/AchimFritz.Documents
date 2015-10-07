/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3ListController', Mp3ListController);

    /* @ngInject */
    function Mp3ListController (angularPlayer, CONFIG,  $timeout) {

        var vm = this;

        // used by the view
        vm.getTemplate = getTemplate;
        vm.playAll = playAll;

        function playAll(documentListItems) {
            $timeout(function () {
                angularPlayer.clearPlaylist(function (documentListItems) {
                    //add songs to playlist
                    for (var i = 0; i < documentListItems.length; i++) {
                        var song = {
                            id: documentListItems[i]['document']['__identity'],
                            title: documentListItems[i]['document']['absolutePath'],
                            url: '/' + documentListItems[i]['document']['webPath']
                        };
                        angularPlayer.addTrack(song);
                    }
                    angularPlayer.play();
                });
            });
        }

        function getTemplate(name) {
            return CONFIG.templatePath + 'Mp3/' + name + '.html';
        }

    }
})();
