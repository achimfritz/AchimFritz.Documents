/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicEditDocController', MusicEditDocController);

    /* @ngInject */
    function MusicEditDocController ($scope, ngDialog, $rootScope) {

        var vm = this;

        vm.cddbSearch = '';
        vm.category = '';

        vm.doc = $scope.ngDialogData;

        vm.cddb = {
            'path': '',
            'format': 1,
            'url': ''
        };

        docUpdate();

        function docUpdate() {
            vm.cddb.path = vm.doc.mainDirectoryName;
            vm.cddbSearch = vm.doc.fsArtist + ' ' + vm.doc.fsAlbum;
        }


        $rootScope.$on('solrDataUpdate', function (event, data) {

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
