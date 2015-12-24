/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicFilterController', MusicFilterController);

    /* @ngInject */
    function MusicFilterController (ngDialog, $rootScope, CONFIG, Solr) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.showRenameCategoryForm = showRenameCategoryForm;

        function showRenameCategoryForm(facetName, facetValue, editType) {

            var data = {
                facetName: facetName,
                facetValue: facetValue,
                editType: editType
            };

            $scope.dialog = ngDialog.open({
                "data" : data,
                "template" : CONFIG.templatePath + 'Music/EditCategory.html',
                "controller" : 'MusicEditCategoryController',
                "controllerAs" : 'musicEditCategory',
                "scope" : $scope
            });
        }

        $rootScope.$on('apiId3TagUpdate', function (event, data) {
            ngDialog.close($scope.dialog.id);
            if (Solr.hasFilterQuery(data.facetName) === true) {
                Solr.rmFilterQuery(data.facetName, data.renameCategory.oldPath);
                Solr.addFilterQuery(data.facetName, data.renameCategory.newPath);
            }
            Solr.forceRequest().then(function (response) {
                $rootScope.$emit('solrDataUpdate', response.data);
            });
        });

    }
})();
