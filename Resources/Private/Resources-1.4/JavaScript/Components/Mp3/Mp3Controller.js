/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3Controller', Mp3Controller);

    /* @ngInject */
    function Mp3Controller ($rootScope, WidgetConfiguration, SolrConfiguration, AppConfiguration, FilterConfiguration, Solr) {

        var vm = this;

        // V2
        var $scope = $rootScope.$new();
        vm.infoDoc = null;
        vm.category = '';
        vm.cddb = {};
        vm.zip = {};
        vm.tagPath = '';
        vm.cddbSearch = '';
        vm.playListForm = false;
        vm.artistSearch = '';

        // used by the view
        vm.showInfoDoc = showInfoDoc;
        vm.hideInfoDoc = hideInfoDoc;
        vm.setPlayListForm = setPlayListForm;
        vm.togglePlayer = togglePlayer;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
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

        function togglePlayer(enable) {
            if (enable === true) {
                $rootScope.$emit('openWidget', 'player');
                $rootScope.$emit('closeWidget', 'result');
            } else {
                $rootScope.$emit('closeWidget', 'player');
                $rootScope.$emit('openWidget', 'result');
            }
        }

        $rootScope.$on('player:playlist', function(event, playlist){
            if (playlist.length === 0) {
                vm.togglePlayer(false);
            }
        });


        $rootScope.$on('music:isPlaying', function(event, isPlaying){
            vm.togglePlayer(true);
        });

        var listener = $scope.$on('solrDataUpdate', function (event, data) {
            vm.zip.name = '';
            vm.cddb.path = '';
            var params = Solr.getParams();
            if (params['q'] !== '*:*') {
                // TODO
                $rootScope.$emit('openWidget', 'filter');
            }
            var found = false;
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

        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            listener();
            killerListener();
        });

    }
})();
