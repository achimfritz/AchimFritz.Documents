/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicDocListController', MusicDocListController);

    /* @ngInject */
    function MusicDocListController (AppConfiguration, CoreApiService, $rootScope, $timeout, $location, MusicPlayerService, CONFIG, Solr) {

        var vm = this;
        var $listenerScope = $rootScope.$new();

        vm.big = false;
        vm.documentLists = [];
        vm.documentList = {};
        vm.coreApi = CoreApiService;

        // used by the view
        vm.onDropComplete = onDropComplete;
        vm.play = play;

        AppConfiguration.setNamespace('mp3');
        CoreApiService.listList();

        function play() {

            var listDocs = [];
            angular.forEach(vm.documentList.documentListItems, function(item){
                listDocs.push(item.document);
            });

            var params = {
                rows: '100',
                q: '*:*',
                fq: 'paths:' + vm.documentList.category.path
            };
            Solr.fetchByParams(params).then(function(response){
                var docs = [];
                angular.forEach(listDocs, function (listDoc) {
                    angular.forEach(response.data.response.docs, function(solrDoc){
                        if (solrDoc.identifier === listDoc['__identity']) {
                            docs.push(solrDoc);
                        }
                    });
                });
                MusicPlayerService.playAll(docs);
            });
            
        }


        function onDropComplete (index, obj, evt) {
            var objIndex = vm.documentList.documentListItems.indexOf(obj);
            var oldList = vm.documentList.documentListItems;
            var l = oldList.length;
            var newList = [];
            for (var j = 0; j < l; j++) {
                if (j === index) {
                    newList.push(obj);
                }
                if (j !== objIndex) {
                    newList.push(oldList[j]);
                }
            }
            vm.documentList.documentListItems = newList;
        }

        var listener = $listenerScope.$on('core:apiCallSuccess', function(event, data) {
            if (angular.isDefined(data.data.documentLists)){
                vm.documentLists = data.data.documentLists;
                vm.view = 'list';
            } else if (angular.isDefined(data.data.documentList)) {
                vm.documentList = data.data.documentList;
                vm.view = 'show';
            }
        });

    }
})();
