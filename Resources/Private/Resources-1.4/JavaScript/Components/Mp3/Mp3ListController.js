/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3ListController', Mp3ListController);

    /* @ngInject */
    function Mp3ListController (angularPlayer, FlashMessageService, CONFIG, $rootScope, $timeout) {

        var vm = this;
        var $scope = $rootScope.$new();

        // used by the view
        vm.getTemplate = getTemplate;
        vm.playAll = playAll;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;

        vm.initController();

        function initController() {
        }

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

            //play first song
            //angularPlayer.play();
        }

        function getTemplate(name) {
            return CONFIG.templatePath + 'Mp3/' + name + '.html';
        }


        function restSuccess(data) {
            vm.finished = true;
            FlashMessageService.show(data.data.flashMessages);
        }

        function restError(data) {
            vm.finished = true;
            FlashMessageService.error(data);
        }

    }
})();
