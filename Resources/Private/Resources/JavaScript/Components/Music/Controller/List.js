/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicListController', MusicListController);

    /* @ngInject */
    function MusicListController ($rootScope, Solr, PathService, DocumentListRestService, ngDialog, CONFIG, HPathService) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.paths = [];

        vm.addFilterQuery = addFilterQuery;

        vm.showRenameCategoryForm = showRenameCategoryForm;

        getSolrData();

        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                var fq = Solr.getFilterQueries();
                var current = fq['hPaths'];
                if (angular.isDefined(current) && angular.isDefined(current[0])) {
                    HPathService.update(current[0], data.facet_counts.facet_fields.hPaths);
                } else {
                    HPathService.initData();
                    HPathService.setDataByIndex(0, data.facet_counts.facet_fields.hPaths);
                }
            }
            vm.paths = HPathService.getData();
        }

        var listener = $scope.$on('solrDataUpdate', function(event, data) {
            getSolrData();
        });

        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            listener();
            killerListener();
        });

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

        function addFilterQuery(name, value) {
            var splitted = PathService.split(value);
            var filterType = splitted[1];
            Solr.addFilterQuery(name, value);
            if (filterType === 'list' && PathService.depth(value) === 4) {
                // TODO SolrDocumentListConnector.merge(path)

                var path = PathService.slice(value, 1);
                var docs = [];
                DocumentListRestService.showByPath(path).then(
                    function(response) {
                        var documentList = response.data.documentList;
                        var cnt = documentList.documentListItems.length;
                        Solr.setParam('rows', cnt);
                        Solr.forceRequest().then(function (response) {
                            angular.forEach(documentList.documentListItems, function (listItem) {
                                angular.forEach(response.data.response.docs, function (solrDoc) {
                                    if (solrDoc.identifier === listItem.document['__identity']) {
                                        docs.push(solrDoc);
                                    }
                                });
                            });
                            response.data.response.docs = docs;
                            Solr.setData(response.data);
                        });
                    },
                    function(response) {
                        Solr.update();
                    }
                );
            } else {
                Solr.update();
            }

        }
    }
})();
