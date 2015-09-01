(function () {
    'use strict';

    angular
        .module('achimfritz.soundmanager')
        .directive('afPlayAll', AfPlayAll);

    function AfPlayAll(angularPlayer) {
        return {
            restrict: "EA",
            scope: {
                docs: '=afPlayAll'
            },
            link: function(scope, element, attrs) {
                element.bind('click', function(event) {
                    if (attrs.play != 'false') {
                        //first clear the playlist
                        angularPlayer.clearPlaylist(function (data) {
                            //add songs to playlist
                            for (var i = 0; i < scope.docs.length; i++) {
                                var song = {
                                    id: scope.docs[i]['identifier'],
                                    title: scope.docs[i]['title'],
                                    url: scope.docs[i]['webPath'],
                                    doc: scope.docs[i]
                                };
                                angularPlayer.addTrack(song);
                            }
                        });
                        //play first song
                        angularPlayer.play();
                    } else {
                        for (var i = 0; i < scope.docs.length; i++) {
                            var song = {
                                id: scope.docs[i]['identifier'],
                                title: scope.docs[i]['title'],
                                url: scope.docs[i]['webPath'],
                                doc: scope.docs[i]
                            };
                            angularPlayer.addTrack(song);
                        }
                    }
                });
            }
        };
    }
}());
