/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .service('MusicFilterService', MusicFilterService);

    /* @ngInject */
    function MusicFilterService() {

        var self = this;
        var filters = {
            artist: true,
            album: true,
            genre: true,
            fsProvider: true,
            fsGenre: true,
            artistLetter: false,
            year: false,
            hPaths: false,
            fsArtist: false,
            fsAlbum: false
        };

        self.getFilters = getFilters;
        self.toggleFilter = toggleFilter;

        function getFilters() {
            return filters;
        }

        function toggleFilter(name) {
            if (filters[name] === false) {
                filters[name] = true;
            } else {
                filters[name] = false;
            }
            return filters;
        }

    }
})();
