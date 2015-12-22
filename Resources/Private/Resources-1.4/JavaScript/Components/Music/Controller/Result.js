/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicResultController', MusicResultController);

    /* @ngInject */
    function MusicResultController (Mp3PlayerService, $location, $timeout, CONFIG, $rootScope, ngDialog) {

        var vm = this;
        var $scope = $rootScope.$new();

        // used by the view
        vm.playAll = playAll;
        vm.addAll = addAll;
        vm.editDoc = editDoc;

        Mp3PlayerService.initialize();

        function playAll(docs) {
            Mp3PlayerService.playAll(docs);
            $timeout(function () {
                $location.path(CONFIG.baseUrl + '/music/player');
                $rootScope.$emit('locationChanged', 'player');
            });
        }

        function addAll(docs) {
            Mp3PlayerService.addAll(docs);
        }

        function editDoc(doc) {
            $scope.dialog = ngDialog.open({
                "data" : doc,
                "template" : CONFIG.templatePath + 'Music/EditDoc.html',
                "controller" : 'MusicEditDocController',
                "controllerAs" : 'musicEditDoc',
                "scope" : $scope
            });
        }

    }
})();
