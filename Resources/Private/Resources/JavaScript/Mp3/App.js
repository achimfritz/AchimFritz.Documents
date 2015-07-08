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
								SolrProvider.setFacets(['artist', 'album', 'genre', 'year', 'fsProvider', 'fsGenre', 'artistLetter']);
								SolrProvider.setHFacets({
								});
								SolrProvider.setSolrSetting('servlet', 'mp3');
								SolrProvider.setSetting('sort', 'artist asc, album asc, track asc');
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
