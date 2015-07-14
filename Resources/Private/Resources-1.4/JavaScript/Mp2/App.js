/* global angular */

(function () {
    'use strict';
    angular.module('mp2App', ['solr', 'app', 'ngRoute', 'angularSoundManager', 'xeditable'])
        .config(routeConfiguration)
        .config(solrConfiguration)
								.run(xeditableConfig);

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
        SolrProvider.setFacets(['artist', 'album', 'genre', 'year', 'fsProvider', 'fsGenre', 'artistLetter', 'fsAlbum', 'fsArtist']);
        SolrProvider.setHFacets({});
        SolrProvider.setSolrSetting('servlet', 'mp3');
        SolrProvider.setSetting('sort', 'artist asc, album asc, track asc');
        SolrProvider.setSetting('rows', 30);
        SolrProvider.setSetting('facet_limit', 30);
        SolrProvider.setSetting('facet_sort', 'count');
        SolrProvider.setSetting('f_artistLetter_facet_sort', 'index');
        var solrSettingsDiv = jQuery('#solrSettings');
        if (solrSettingsDiv.length) {
            var solrSettings = solrSettingsDiv.data('solrsettings');
            SolrProvider.setSolrSetting('servlet', solrSettings.servlet);
            SolrProvider.setSolrSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
        }
    }

    /* @ngInject */
    function xeditableConfig(editableOptions, editableThemes) {
        editableOptions.theme = 'bs3';
        editableThemes.bs3.inputClass = 'input-sm';
        editableThemes.bs3.buttonsClass = 'btn-xs';
    }

}());
