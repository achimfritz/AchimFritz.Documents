/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicEditDocController', MusicEditDocController);

    /* @ngInject */
    function MusicEditDocController ($scope, ngDialog, $rootScope, CoreApiService, Solr) {

        var vm = this;
        var $listenerScope = $rootScope.$new();

        vm.rate = rate;

        vm.cddbSearch = '';
        vm.category = '';

        vm.doc = $scope.ngDialogData;

        vm.cddb = {
            'path': '',
            'format': 1,
            'url': ''
        };

        function rate(rate) {
            CoreApiService.rate(rate);
        }

        docUpdate();

        function docUpdate() {
            vm.cddb.path = vm.doc.mainDirectoryName;
            vm.cddbSearch = vm.doc.fsArtist + ' ' + vm.doc.fsAlbum;
        }

        var listener = $listenerScope.$on('core:apiCallSuccess', function(event, data) {
            Solr.update();
        });

        var dialogListener = $listenerScope.$on('ngDialog.closing', function (e, $dialog) {
            solrListener();
            listener();
            dialogListener();
        });

        var solrListener = $listenerScope.$on('solrDataUpdate', function (event, data) {
            var found = false;
            angular.forEach(data.response.docs, function(doc) {
                if (doc.identifier === vm.doc.identifier) {
                    vm.doc = doc;
                    found = true;
                    docUpdate();
                }
            });
            if (found === false) {
                ngDialog.close($scope.dialog.id);
            }
        });


    }
})();
