/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageDocListController', ImageDocListController);

    /* @ngInject */
    function ImageDocListController (AppConfiguration, CoreApiService, $rootScope) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.big = false;
        vm.documentLists = [];
        vm.documentList = {};
        vm.coreApi = CoreApiService;

        // used by the view
        vm.showBig = showBig;
        vm.onDropComplete = onDropComplete;

        AppConfiguration.setNamespace('image');
        CoreApiService.listList();


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

        function showBig(showBig) {
            vm.big = showBig;
        }

        var listener = $scope.$on('core:apiCallSuccess', function(event, data) {
            if (angular.isDefined(data.data.documentLists)){
                vm.documentLists = data.data.documentLists;
                vm.view = 'list';
            } else if (angular.isDefined(data.data.documentList)) {
                vm.documentList = data.data.documentList;
                vm.view = 'show';
            }
        });

        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            listener();
            killerListener();
        });

    }
})();
