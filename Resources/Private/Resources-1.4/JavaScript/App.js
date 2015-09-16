/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.app', [
            'ngNewRouter',
            'ngSanitize',
            'xeditable',
            'toaster',
            'angularSoundManager',
            'ngDialog',
            'cfp.hotkeys',
            'achimfritz.soundmanager',
            'achimfritz.core',
            'achimfritz.solr',
            'achimfritz.urlBuilder',
            'achimfritz.document',
            'achimfritz.mp3',
            'achimfritz.image'
        ])
        .constant('CONFIG', {
            templatePath: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript-1.4/',
            baseUrl: '/achimfritz.documents/app14'
        })
        .config(TemplateMapping)
        .config(locationConfig)
        .run(xeditableConfig)
        .controller('AppController', AppController)
        .controller('UrlBuilderAppController', UrlBuilderAppController)
        .controller('Mp3AppController', Mp3AppController)
        .controller('ImageAppController', ImageAppController)
        .controller('DocumentAppController', DocumentAppController);

    /* @ngInject */
    function DocumentAppController ($router, CONFIG, SolrSettings) {
        var solrSettingsDiv = jQuery('#solrSettings');
        if (solrSettingsDiv.length) {
            var solrSettings = solrSettingsDiv.data('solrsettings');
            SolrSettings.setSetting('servlet', solrSettings.servlet);
            SolrSettings.setSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
        }
        $router.config([
            {
                path: CONFIG.baseUrl + '/document',
                components: {
                    main: 'document'
                }
            }
        ]);
    }

    /* @ngInject */
    function ImageAppController ($router, CONFIG, SolrSettings, RestConfiguration) {
        RestConfiguration.setSetting('documentListResource', 'imagedocumentlist');
        RestConfiguration.setSetting('documentListMergeResource', 'imagedocumentlistmerge');
        RestConfiguration.setSetting('documentListRemoveResource', 'imagedocumentlistremove');

        SolrSettings.setFacets(['hCategories', 'hPaths', 'hLocations', 'year', 'tags', 'parties', 'mainDirectoryName', 'collections']);
        SolrSettings.setHFacets({
            hPaths: '0',
            hCategories: '1/categories',
            hLocations: '1/locations'
        });

        var solrSettingsDiv = jQuery('#solrSettings');
        if (solrSettingsDiv.length) {
            var solrSettings = solrSettingsDiv.data('solrsettings');
            SolrSettings.setSetting('servlet', solrSettings.servlet);
            SolrSettings.setSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
        }
        $router.config([
            {
                path: CONFIG.baseUrl + '/image',
                components: {
                    main: 'image'
                }
            }
        ]);
    }

    /* @ngInject */
    function Mp3AppController ($router, CONFIG, SolrSettings, RestConfiguration) {
        RestConfiguration.setSetting('documentListResource', 'mp3documentlist');
        RestConfiguration.setSetting('documentListMergeResource', 'mp3documentlistmerge');
        RestConfiguration.setSetting('documentListRemoveResource', 'mp3documentlistremove');

        SolrSettings.setFacets(['artist', 'album', 'fsArtist', 'fsAlbum', 'artistLetter', 'genre', 'year', 'fsProvider', 'fsGenre', 'hPaths']);
        SolrSettings.setHFacets({});
        SolrSettings.setSetting('servlet', 'mp3');
        SolrSettings.setParam('sort', 'track asc, fsTrack asc, fsTitle asc');
        SolrSettings.setParam('rows', 15);
        SolrSettings.setParam('facet_limit', 15);
        SolrSettings.setParam('facet_sort', 'count');
        SolrSettings.setParam('f_artistLetter_facet_sort', 'index');
        SolrSettings.setParam('f_artistLetter_facet_limit', 35);
        SolrSettings.setParam('f_hPaths_facet_prefix', '2/');
        SolrSettings.setParam('f_hPaths_facet_limit', 35);


        var solrSettingsDiv = jQuery('#solrSettings');
        if (solrSettingsDiv.length) {
            var solrSettings = solrSettingsDiv.data('solrsettings');
            SolrSettings.setSetting('servlet', solrSettings.servlet);
            SolrSettings.setSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
        }
        $router.config([
            {
                path: CONFIG.baseUrl + '/mp3',
                components: {
                    main: 'mp3'
                }
            }
        ]);
    }

    /* @ngInject */
    function UrlBuilderAppController ($router, CONFIG) {
        $router.config([
            {
                path: CONFIG.baseUrl + '/urlbuilder',
                components: {
                    main: 'urlBuilder'
                }
            }
        ]);
    }

    /* @ngInject */
    function AppController ($router, CONFIG) {
        $router.config([
            {
                path: CONFIG.baseUrl + '/index',
                components: {
                    main: 'default',
                    navigation: 'navigation'
                }
            },
            {
                path: CONFIG.baseUrl + '/urlbuilder',
                components: {
                    main: 'urlBuilder',
                    navigation: 'navigation'
                }
            },
            {
                path: CONFIG.baseUrl + '/document',
                components: {
                    main: 'document',
                    navigation: 'navigation'
                }
            }
        ]);
    }

    /* @ngInject */
    function TemplateMapping($componentLoaderProvider, CONFIG) {
        $componentLoaderProvider.setTemplateMapping(function (name) {
            return {
                'urlBuilder': CONFIG.templatePath + 'UrlBuilder/UrlBuilder.html',
                'document': CONFIG.templatePath + 'Document/Document.html',
                'default': CONFIG.templatePath + 'Default/Default.html',
                'navigation': CONFIG.templatePath + 'Navigation/Navigation.html',
                'mp3': CONFIG.templatePath + 'Mp3/Mp32.html',
                'image': CONFIG.templatePath + 'Image/Image.html'
            }[name];
        });
    }

    /* @ngInject */
    function locationConfig($locationProvider) {
        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false,
            rewriteLinks: false
        });
    }

    /* @ngInject */
    function xeditableConfig(editableOptions, editableThemes) {
        editableOptions.theme = 'bs3';
        editableThemes.bs3.inputClass = 'input-sm';
        editableThemes.bs3.buttonsClass = 'btn-xs';
    }
}());
