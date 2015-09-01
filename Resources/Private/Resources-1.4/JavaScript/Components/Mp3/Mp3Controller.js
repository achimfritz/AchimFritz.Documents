/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3Controller', Mp3Controller);

    /* @ngInject */
    function Mp3Controller (CONFIG, $rootScope) {

        var vm = this;
        vm.templatePaths = {};

        // V2
        vm.infoDoc = null;

        // used by the view
        // V2
        vm.showInfoDoc = showInfoDoc;
        vm.hideInfoDoc = hideInfoDoc;
        vm.rate = rate;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            // V1
            vm.templatePaths = {
                nav: CONFIG.templatePath + 'Mp3/Nav.html',
                filterNav: CONFIG.templatePath + 'Mp3/FilterNav.html',
                resultTable: CONFIG.templatePath + 'Mp3/ResultTable.html',
                playerControls: CONFIG.templatePath + 'Mp3/PlayerControls.html',
                playlistTable: CONFIG.templatePath + 'Mp3/PlaylistTable.html',
                currentPlaying: CONFIG.templatePath + 'Mp3/CurrentPlaying.html',
                resultHead: CONFIG.templatePath + 'Mp3/ResultHead.html'
            };
            // V2
            // new
            vm.templatePaths.letterNav = CONFIG.templatePath + 'Mp3/LetterNav.html';
            vm.templatePaths.infoDoc = CONFIG.templatePath + 'Mp3/InfoDoc.html';
            // override
            vm.templatePaths.filterNav = CONFIG.templatePath + 'Mp3/FilterNavSelect.html';
            vm.templatePaths.resultTable = CONFIG.templatePath + 'Mp3/ExtendedResultTable.html';
        }

        function showInfoDoc(doc) {
            vm.infoDoc = doc;
        }

        function hideInfoDoc() {
            vm.infoDoc = null;
        }

        function rate(key, val) {

        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            if (vm.infoDoc !== null) {
                angular.forEach(data.response.docs, function(doc) {
                    if (doc.identifier === vm.infoDoc.identifier) {
                        vm.infoDoc = doc;
                    }
                })
            }
        });

    }
})();
