/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageController', ImageController);

    /* @ngInject */
    function ImageController ($rootScope, CONFIG, ngDialog, hotkeys, WidgetConfiguration, SolrConfiguration, AppConfiguration, PathService) {

        var vm = this;
        var $scope = $rootScope.$new();
        vm.templatePaths = {};
        vm.finished = true;
        vm.mode = 'view';
        vm.imageWidth = 320;
        vm.data = {};
        vm.strgPressed = false;
        vm.shiftPressed = false;
        vm.renameCategory = false;
        vm.renameCategoryFacet = '';

        // used by the view
        vm.itemClick = itemClick;
        vm.changeImageSize = changeImageSize;
        vm.openImageModal = openImageModal;
        vm.itemClickSelect = itemClickSelect;
        vm.emptyList = emptyList;
        vm.showRenameCategoryForm = showRenameCategoryForm;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {

            AppConfiguration.setNamespace('image');

            SolrConfiguration.setFacets(['hCategories', 'hPaths', 'hLocations', 'year', 'tags', 'parties', 'mainDirectoryName', 'collections']);
            SolrConfiguration.setHFacets({
                hPaths: '0',
                hCategories: '1/categories',
                hLocations: '1/locations'
            });
            SolrConfiguration.setSetting('servlet', 'image');
            WidgetConfiguration.setNamespace('Image');
            WidgetConfiguration.setWidgets({
                docs: true,
                filter: true,
                solrIntegrity: false,
                filterNav: false,
                filterNavSelect: false,
                clipboard: false,
                integrity: false
            });
            WidgetConfiguration.setFilters({
                hCategories: false,
                hLocations: false,
                hPaths: false,
                year: false,
                tags: false,
                parties: false,
                mainDirectoryName: false,
                collections: false
            });

            hotkeys.bindTo($scope).add({
                combo: 'ctrl',
                action: 'keydown',
                callback: function () {
                    vm.strgPressed = true;
                }
            }).add({
                combo: 'ctrl',
                action: 'keyup',
                callback: function () {
                    vm.strgPressed = false;
                }
            }).add({
                combo: 'shift',
                action: 'keyup',
                callback: function () {
                    vm.shiftPressed = false;
                }
            }).add({
                combo: 'shift',
                action: 'keydown',
                callback: function () {
                    vm.shiftPressed = true;
                }
            });
        }

        function showRenameCategoryForm(facetName, facetValue) {
            vm.renameCategoryFacet = facetName;
            var path = '';
            if (PathService.depth(facetValue) === 1) {
                path = facetName + PathService.delimiter + facetValue;
            } else {
                path = PathService.slice(facetValue, 1);
            }
            vm.renameCategory = {
                'oldPath': path,
                'newPath': path
            };
        }

        function emptyList() {
            angular.forEach(vm.data.response.docs, function (val, key) {
                val.selected = '';
            });
            $rootScope.$emit('docsUpdate', vm.data.response.docs);
        }

        function itemClickSelect (doc) {
            var docs = vm.data.response.docs;
            if (doc.selected === 'selected') {
                doc.selected = '';
            } else {
                if (vm.strgPressed === false && vm.shiftPressed === false) {
                    // rm all others
                    angular.forEach(docs, function (val, key) {
                        if (doc.identifier !== val.identifier) {
                            val.selected = '';
                        }
                    });
                } else if (vm.shiftPressed === true) {
                    // select all from last selected
                    var collect = false;
                    for (var i = ( docs.length - 1 ); i >= 0; i--) {
                        var el = docs[i];
                        if (el.identifier === doc.identifier) {
                            collect = true;
                        }
                        if (collect === true) {
                            if (el.selected === 'selected') {
                                collect = false;
                            }
                            el.selected = 'selected';
                        }

                    }
                }
                // add always me
                doc.selected = 'selected';
            }
            $rootScope.$emit('docsUpdate', docs);
        }

        function changeImageSize(diff) {
            vm.imageWidth += diff;
        }

        function itemClick(doc) {
            if (vm.mode === 'select') {
                return vm.itemClickSelect(doc);
            } else {
                return vm.openImageModal(doc);
            }
        }

        function openImageModal(doc) {
            $scope.dialog = ngDialog.open({
                "data": doc,
                "template": CONFIG.templatePath + 'Image/ImageModal.html',
                "controller": 'ImageModalController',
                "controllerAs": 'ctrl',
                "scope": $scope
            });
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            vm.data = data;
            vm.renameCategory = null;
            vm.renameCategoryFacet = '';
            $rootScope.$emit('docsUpdate', data.response.docs);
        })

    }
})();
