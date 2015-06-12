/* global angular */

(function () {
    'use strict';
				angular.module('mp3App', ['solr', 'app', 'ngRoute', 'xeditable', 'angularUtils.directives.dirPagination', 'angularSoundManager', 'toaster'])
								.config(routeConfiguration)
								.config(paginationConfiguration)
								.config(solrConfiguration)
								.run(xeditableConfig);

				/* @ngInject */
				function paginationConfiguration(paginationTemplateProvider) {
								paginationTemplateProvider.setPath('/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/dirPagination.tpl.html');
				};

    function routeConfiguration($routeProvider) {
        var templatePath = '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/Mp3/Templates/';
        $routeProvider.
            when('/', {
                templateUrl: templatePath + 'Search.html',
                controller: 'SearchController'
            }).
            otherwise({
                redirectTo: '/'
            });
    };

				function solrConfiguration(SolrProvider) {
								SolrProvider.setFacets(['id3Artist', 'id3Album', 'id3Genre', 'id3Year', 'fsProvider', 'fsGenre', 'artistLetter']);
								SolrProvider.setHFacets({
								});
								SolrProvider.setSolrSetting('servlet', 'mp3');
								SolrProvider.setSetting('sort', 'id3Artist asc, id3Album asc, id3Track asc');
								SolrProvider.setSetting('rows', 30);
								SolrProvider.setSetting('facet_limit', 30);
								SolrProvider.setSetting('facet_sort', 'count');
								SolrProvider.setSetting('f_artistLetter_facet_sort', 'index');
				};

				/* @ngInject */
				function xeditableConfig(editableOptions) {
								editableOptions.theme = 'bs3';
				}

}());
