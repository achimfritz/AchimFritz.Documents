/* global angular */

(function () {
    'use strict';
    angular.module('mp2App', ['solr', 'app', 'ngRoute', 'angularSoundManager'])
        .config(routeConfiguration)
        .config(solrConfiguration);

    /* @ngInject */
    function routeConfiguration($routeProvider) {
        var templatePath = '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/Mp2/Templates/';
        $routeProvider.
            when('/', {
                templateUrl: templatePath + 'Search.html',
                controller: 'SearchController'
            }).
            otherwise({
                redirectTo: '/'
            });
    }

    /* @ngInject */
    function solrConfiguration(SolrProvider) {
        SolrProvider.setFacets(['artist', 'album', 'genre', 'year', 'fsProvider', 'fsGenre']);
        SolrProvider.setHFacets({});
        SolrProvider.setSolrSetting('servlet', 'mp3');
        SolrProvider.setSetting('sort', ' track asc, artist asc, album asc');
        SolrProvider.setSetting('rows', 15);
        SolrProvider.setSetting('facet_limit', 15);
        SolrProvider.setSetting('facet_sort', 'count');
        SolrProvider.setSetting('f_artistLetter_facet_sort', 'index');
        var solrSettingsDiv = jQuery('#solrSettings');
        if (solrSettingsDiv.length) {
            var solrSettings = solrSettingsDiv.data('solrsettings');
            SolrProvider.setSolrSetting('servlet', solrSettings.servlet);
            SolrProvider.setSolrSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
        }
    }

}());
