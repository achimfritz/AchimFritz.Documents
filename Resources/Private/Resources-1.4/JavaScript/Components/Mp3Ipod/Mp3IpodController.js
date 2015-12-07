/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodController', Mp3IpodController);

    /* @ngInject */
    function Mp3IpodController ($location) {

        var vm = this;

        // used by the view
        vm.forward = forward;

        function forward(name) {
            if (name === 'album') {
                $location.path('app/mp3ipod/album/all/all');
            } else if (name === 'artist') {
                $location.path('app/mp3ipod/artist/all');
            } else if (name === 'search') {
                $location.path('app/mp3ipod/search/all');
            } else {
                $location.path('app/mp3ipod/' + name);
            }
        }
    }
})();
