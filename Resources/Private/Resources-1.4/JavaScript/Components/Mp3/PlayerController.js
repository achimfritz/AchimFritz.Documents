/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('PlayerController', PlayerController);

    /* @ngInject */
    function PlayerController (angularPlayer, CONFIG,  $timeout) {

        var vm = this;

        // used by the view
        vm.playAllDocumentList = playAllDocumentList;
        vm.playAll = playAll;
        vm.addAll = addAll;

        function playAll(docs) {
            $timeout(function () {
                angularPlayer.clearPlaylist(function (data) {
                    //add songs to playlist
                    for (var i = 0; i < docs.length; i++) {
                        var song = {
                            id: docs[i]['identifier'],
                            title: docs[i]['title'],
                            url: docs[i]['webPath'],
                            doc: docs[i]
                        };
                        angularPlayer.addTrack(song);
                    }
                });
                //play first song
                angularPlayer.play();
            });
        }

        function addAll(docs) {
            $timeout(function () {
                for (var i = 0; i < docs.length; i++) {
                    var song = {
                        id: docs[i]['identifier'],
                        title: docs[i]['title'],
                        url: docs[i]['webPath'],
                        doc: docs[i]
                    };
                    angularPlayer.addTrack(song);
                }
            });
        }

        function playAllDocumentList(documentListItems) {
            //console.log(documentListItems);
            $timeout(function () {



                angularPlayer.clearPlaylist(function (data) {
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
