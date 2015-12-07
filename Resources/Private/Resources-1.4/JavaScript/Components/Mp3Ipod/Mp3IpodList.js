/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodListController', Mp3IpodListController);

    /* @ngInject */
    function Mp3IpodListController ($location, $routeParams, DocumentListRestService, AppConfiguration) {

        var vm = this;
        vm.documentLists = {};

        // used by the view
        vm.back = back;
        vm.play = play;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            AppConfiguration.setNamespace('mp3');
            DocumentListRestService.list().then(
                function (data) {
                    vm.documentLists = data.data.documentLists;
                },
                function(data) {

                }
            );
        }

        function play(identifier) {
            $location.path('app/mp3ipod/result/all/all/all/' + identifier + '/all');
        }

        function back() {
            $location.path('app/mp3ipod');
        }

    }
})();
