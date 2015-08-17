/* global angular */

(function () {
    'use strict';
    angular.module('mp3App', ['solr', 'app', 'ngRoute', 'xeditable', 'angularSoundManager', 'toaster'])
        .config(routeConfiguration)
        .config(solrConfiguration)
        .config(appConfiguration)
        .run(xeditableConfig);

    /* @ngInject */
    function appConfiguration(AppConfigurationProvider) {
        AppConfigurationProvider.setSetting('documentListResource', 'mp3documentlist');
        AppConfigurationProvider.setSetting('documentListMergeResource', 'mp3documentlistmerge');
        AppConfigurationProvider.setSetting('documentListRemoveResource', 'mp3documentlistremove');
    };

    /* @ngInject */
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
    }

    /* @ngInject */
    function solrConfiguration(SolrProvider) {
        SolrProvider.setFacets(['artist', 'album', 'genre', 'year', 'fsProvider', 'fsGenre', 'artistLetter', 'fsAlbum', 'fsArtist', 'titleRating', 'albumRating', 'artistRating', 'hPaths']);
        SolrProvider.setHFacets({});
        SolrProvider.setSolrSetting('servlet', 'mp3');
        SolrProvider.setSetting('sort', 'artist asc, album asc, track asc');
        SolrProvider.setSetting('rows', 30);
        SolrProvider.setSetting('facet_limit', 30);
        SolrProvider.setSetting('facet_sort', 'count');
        SolrProvider.setSetting('f_artistLetter_facet_sort', 'index');
        SolrProvider.setSetting('f_artist_facet_sort', 'count');
        SolrProvider.setSetting('f_hPaths_facet_prefix', '2/');
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
