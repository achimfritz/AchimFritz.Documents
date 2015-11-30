/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .service('Mp3PlayerService', Mp3PlayerService);

    /* @ngInject */
    function Mp3PlayerService (angularPlayer, $timeout) {

        var self = this;
        var initialized = false;

        self.playAll = playAll;
        self.addAll = addAll;
        self.playAllDocumentList = playAllDocumentList;
        self.getPlaylistDocs = getPlaylistDocs;
        self.initialize = initialize;
        self.playOne = playOne;
        self.addOne = addOne;

        function initialize() {
            if (initialized === false) {
                angularPlayer.init();
                initialized = true;
            }
        }

        function playOne(doc) {
            $timeout(function () {
                angularPlayer.clearPlaylist(function (data) {
                        var song = {
                            id: doc['identifier'],
                            title: doc['title'],
                            url: doc['webPath'],
                            doc: doc
                        };
                        angularPlayer.addTrack(song);

                    //play first song
                    $timeout(function () {
                        angularPlayer.setCurrentTrack(doc['identifier']);
                        angularPlayer.play();
                    });

                });
            });
        }

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
                        //console.log(song);
                        angularPlayer.addTrack(song);
                    }
                    //play first song
                    $timeout(function () {
                        angularPlayer.setCurrentTrack(docs[0]['identifier']);
                        angularPlayer.play();
                    });

                });
            });
        }

        function addOne(doc) {
            $timeout(function () {
                    var song = {
                        id: doc['identifier'],
                        title: doc['title'],
                        url: doc['webPath'],
                        doc: doc
                    };
                    angularPlayer.addTrack(song);
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

        function getPlaylistDocs() {
            var docs = [];
            var playlist = angularPlayer.getPlaylist();
            angular.forEach(playlist, function (val, key) {
                docs.push(val.doc);
            });
            return docs;
        }

    }
})();
