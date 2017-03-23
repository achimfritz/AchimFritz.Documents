/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('Mp3IndexController', Mp3IndexController);

    /* @ngInject */
    function Mp3IndexController (MusicPlayerService, CONFIG, $rootScope, Solr, $timeout, angularPlayer, ngDialog, $filter, FacetFactory) {

        var vm = this;
        var $scope = $rootScope.$new();
        var id3TagFilters = ['artist', 'album', 'genre', 'year'];
        var categoryFilters = ['hPaths'];

        // solr
        vm.data = {};
        vm.params = {};
        vm.filterQueries = {};
        vm.hasFilterQueries = false;
        vm.facets = [];
        vm.search = '';
        vm.afOnly = false;

        // player
        vm.song = {};
        vm.currentPostion = 0;
        vm.currentDuration = 0;
        vm.playlist = {};


        vm.config = CONFIG;
        vm.solr = Solr;
        vm.musicPlayerService = MusicPlayerService;
        vm.facetFactory = FacetFactory;

        // used by the view

        vm.onDropComplete = onDropComplete;
        vm.editDoc = editDoc;
        vm.isEditableId3Tag = isEditableId3Tag;
        vm.isEditableCategory = isEditableCategory;
        vm.showId3TagForm = showId3TagForm;
        vm.showCategoryForm = showCategoryForm;
        vm.editPlaylist = editPlaylist;
        vm.update = update;
        vm.afOnlyChanged = afOnlyChanged;

        MusicPlayerService.initialize();
        getSolrData();

        function afOnlyChanged() {
            if (vm.afOnly === true) {
                Solr.addFilterQueryAndUpdate('fsProvider', 'af');
            } else {
                Solr.rmFilterQueryAndUpdate('fsProvider', 'af');
            }
        }

        function onDropComplete(index, obj, evt) {
            var objIndex = vm.playlist.indexOf(obj);
            var oldList = vm.playlist;
            var soundIds = [];
            var l = oldList.length;
            var newList = [];
            for (var j = 0; j < l; j++) {
                if (j === index) {
                    newList.push(obj);
                    soundIds.push(obj.id);
                }
                if (j !== objIndex) {
                    newList.push(oldList[j]);
                    soundIds.push(oldList[j].id);
                }
            }
            soundManager.soundIDs = soundIds;
            vm.playlist = newList;
        }

        function update() {
            Solr.setSearchAndUpdate(vm.search);
        }

        function isEditableId3Tag(name) {
            return id3TagFilters.indexOf(name) >= 0;
        }

        function isEditableCategory(name) {
            return categoryFilters.indexOf(name) >= 0;
        }

        function showId3TagForm(tagName, tagValue) {
            var data = {
                name: tagName,
                value: tagValue
            };
            $scope.dialog = ngDialog.open({
                "data" : data,
                "template" : CONFIG.templatePath + 'Music/EditId3Tag.html',
                "controller" : 'MusicEditId3TagController',
                "controllerAs" : 'musicEditId3Tag',
                "scope" : $scope
            });
        }

        function showCategoryForm(facetName, facetValue) {
            var data = {
                name: facetName,
                value: facetValue
            };
            $scope.dialog = ngDialog.open({
                "data" : data,
                "template" : CONFIG.templatePath + 'Music/EditCategory.html',
                "controller" : 'MusicEditCategoryController',
                "controllerAs" : 'musicEditCategory',
                "scope" : $scope
            });
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

        function editPlaylist() {
            $scope.dialog = ngDialog.open({
                "data" : vm.playlist,
                "template" : CONFIG.templatePath + 'Music/EditPlaylist.html',
                "controller" : 'MusicEditPlaylistController',
                "controllerAs" : 'musicEditPlaylist',
                "scope" : $scope
            });
        }

        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                vm.data = Solr.getData();
                vm.params = Solr.getParams();
                vm.filterQueries = Solr.getFilterQueries();
                vm.hasFilterQueries = Object.keys(vm.filterQueries).length > 0;
                if (angular.isDefined(vm.filterQueries.artist) && vm.filterQueries.artist.length > 0){
                    FacetFactory.setVisible('artist', false);
                    FacetFactory.setVisible('album', true);
                } else {
                    FacetFactory.setVisible('artist', true);
                    FacetFactory.setVisible('album', false);
                }
                vm.facets = FacetFactory.updateFacets(data);
            }
        }

        $scope.$on('solrDataUpdate', function(event, data) {
            getSolrData();
        });

        $scope.$on('currentTrack:position', function(event, data) {
            $scope.$apply(function() {
                vm.currentPostion = $filter('humanTime')(data);
            });
        });

        $scope.$on('currentTrack:duration', function(event, data) {
            $scope.$apply(function() {
                vm.currentDuration = $filter('humanTime')(data);
            });
        });

        $scope.$on('player:playlist', function(event, playlist){
            $scope.$apply(function() {
                vm.playlist = playlist;
                if (playlist.length === 0) {
                    vm.song = null;
                }
            });
        });

        $scope.$on('track:id', function(event, data) {
            vm.song = angularPlayer.currentTrackData();
        });

    }
})();
