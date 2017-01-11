/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodIndexController', Mp3IpodIndexController);

    /* @ngInject */
    function Mp3IpodIndexController ($location) {

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
