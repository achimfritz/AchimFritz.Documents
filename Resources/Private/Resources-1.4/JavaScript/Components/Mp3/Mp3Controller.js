/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3Controller', Mp3Controller);

    /* @ngInject */
    function Mp3Controller ($rootScope, WidgetConfiguration, SolrConfiguration, AppConfiguration) {

        var vm = this;

        // V2
        var $scope = $rootScope.$new();
        vm.infoDoc = null;
        vm.category = '';
        vm.random = 0;
        vm.cddb = {};
        vm.zip = {};
        vm.tagPath = '';
        vm.cddbSearch = '';
        vm.playListForm = false;
        vm.artistSearch = '';
        vm.facets = {};

        // used by the view
        vm.showInfoDoc = showInfoDoc;
        vm.hideInfoDoc = hideInfoDoc;
        vm.setPlayListForm = setPlayListForm;
        vm.toggleFacet = toggleFacet;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            AppConfiguration.setNamespace('mp3');
            WidgetConfiguration.setNamespace('Mp3');


            WidgetConfiguration.setWidgets({
                filter: true,
                docs: true,
                letterNav: false,
                filterNav: false,
                filterNavSelect: false,
                integrity: false,
                lists: false
            });
            SolrConfiguration.setFacets(['artist', 'album', 'fsArtist', 'fsAlbum', 'artistLetter', 'genre', 'year', 'fsProvider', 'fsGenre', 'hPaths']);
            SolrConfiguration.setHFacets({});
            SolrConfiguration.setParam('sort', 'track asc, fsTrack asc, fsTitle asc');
            SolrConfiguration.setParam('rows', 15);
            SolrConfiguration.setParam('facet_limit', 15);
            SolrConfiguration.setParam('facet_sort', 'count');
            SolrConfiguration.setParam('f_artistLetter_facet_sort', 'index');
            SolrConfiguration.setParam('f_artistLetter_facet_limit', 35);
            SolrConfiguration.setParam('f_hPaths_facet_prefix', '2/');
            SolrConfiguration.setParam('f_hPaths_facet_limit', 35);
            SolrConfiguration.setSetting('servlet', 'mp3');
            vm.facets = {
                artist: true,
                album: true,
                genre: true,
                artistLetter: false,
                year: false,
                fsProvider: false,
                fsGenre: false,
                hPaths: false,
                fsArtist: false,
                fsAlbum: false
            };

            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            vm.cddb = {
                'path': '',
                'format': 1,
                'url': ''
            };
            vm.zip = {
                'name': 'download',
                'useThumb': false,
                'useFullPath': false
            };
        }

        function toggleFacet(name) {
            if (vm.facets[name] === false) {
                vm.facets[name] = true;
            } else {
                vm.facets[name] = false;
            }
        }

        function showInfoDoc(doc) {
            vm.infoDoc = doc;
            vm.zip.name = doc.fsArtist + '_' + doc.fsAlbum;
            vm.zip.name = vm.zip.name.replace(/ /g, '');
            vm.cddb.path = doc.mainDirectoryName;
            vm.cddbSearch = doc.fsArtist + ' ' + doc.fsAlbum;
        }

        function hideInfoDoc() {
            vm.infoDoc = null;
        }

        function setPlayListForm(val) {
            vm.playListForm = val;
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            vm.zip.name = '';
            vm.cddb.path = '';
            var found = false;
            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            if (vm.infoDoc !== null) {
                angular.forEach(data.response.docs, function(doc) {
                    if (doc.identifier === vm.infoDoc.identifier) {
                        vm.infoDoc = doc;
                        found = true;
                    }
                })
            }
            if (found === false) {
                vm.infoDoc = null;
            }
        });

    }
})();
