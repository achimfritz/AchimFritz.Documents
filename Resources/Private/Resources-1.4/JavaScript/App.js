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
            'ngDraggable',
            'LiveSearch',
            'achimfritz.soundmanager',
            'achimfritz.core',
            'achimfritz.solr',
            'achimfritz.urlBuilder',
            'achimfritz.document',
            'achimfritz.documentlist',
            'achimfritz.mp3',
            'achimfritz.image'
        ])
        .constant('CONFIG', {
            templatePath: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript-1.4/',
            baseUrl: '/app'
        })
        .config(TemplateMapping)
        .config(locationConfig)
        .run(xeditableConfig)
        .controller('AppController', AppController);



    /* @ngInject */
    function imageConfig(SolrConfiguration, RestConfiguration) {
        RestConfiguration.setSetting('documentListResource', 'imagedocumentlist');
        RestConfiguration.setSetting('documentListMergeResource', 'imagedocumentlistmerge');
        RestConfiguration.setSetting('documentListRemoveResource', 'imagedocumentlistremove');

        SolrConfiguration.setFacets(['hCategories', 'hPaths', 'hLocations', 'year', 'tags', 'parties', 'mainDirectoryName', 'collections']);
        SolrConfiguration.setHFacets({
            hPaths: '0',
            hCategories: '1/categories',
            hLocations: '1/locations'
        });

        var solrSettingsDiv = jQuery('#imageSolrSettings');
        if (solrSettingsDiv.length) {
            var solrSettings = solrSettingsDiv.data('solrsettings');
            SolrConfiguration.setSetting('servlet', solrSettings.servlet);
            SolrConfiguration.setSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
        }
    }

    /* @ngInject */
    function mp3Config(SolrConfiguration, RestConfiguration) {

        RestConfiguration.setSetting('documentListResource', 'mp3documentlist');
        RestConfiguration.setSetting('documentListMergeResource', 'mp3documentlistmerge');
        RestConfiguration.setSetting('documentListRemoveResource', 'mp3documentlistremove');

        SolrConfiguration.setFacets(['artist', 'album', 'fsArtist', 'fsAlbum', 'artistLetter', 'genre', 'year', 'fsProvider', 'fsGenre', 'hPaths']);
        SolrConfiguration.setHFacets({});
        SolrConfiguration.setParam('sort', 'track asc, fsTrack asc, fsTitle asc');
        SolrConfiguration.setParam('rows', 15);
        SolrConfiguration.setParam('facet_limit', 15);
        SolrConfiguration.setParam('facet_sort', 'count');
        SolrConfiguration.setParam('f_artistLetter_facet_sort', 'index');
        SolrConfiguration.setParam('f_artistLetter_facet_limit', 35);
        SolrConfiguration.setParam('f_hPaths_facet_prefix', '2/');
        SolrConfiguration.setParam('f_hPaths_facet_limit', 35);

        var solrSettingsDiv = jQuery('#mp3SolrSettings');
        if (solrSettingsDiv.length) {
            var solrSettings = solrSettingsDiv.data('solrsettings');
            SolrConfiguration.setSetting('servlet', solrSettings.servlet);
            SolrConfiguration.setSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
        }
    }

    /* @ngInject */
    function AppController ($router, CONFIG, $location, RestConfiguration, SolrConfiguration) {


        if ($location.path() === CONFIG.baseUrl + '/mp3'
            || $location.path() === CONFIG.baseUrl + '/mp3ipad'
            || $location.path() === CONFIG.baseUrl + '/mp3list'
        ) {
            mp3Config(SolrConfiguration, RestConfiguration);
        } else if ($location.path() === CONFIG.baseUrl + '/document') {
            var solrSettingsDiv = jQuery('#solrSettings');
            if (solrSettingsDiv.length) {
                var solrSettings = solrSettingsDiv.data('solrsettings');
                SolrConfiguration.setSetting('servlet', solrSettings.servlet);
                SolrConfiguration.setSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
            }

        } else if ($location.path() === CONFIG.baseUrl + '/image' || $location.path() === CONFIG.baseUrl + '/imagelist') {
            imageConfig(SolrConfiguration, RestConfiguration);
        }



        $router.config([
            {
                path: CONFIG.baseUrl + '',
                components: {
                    main: 'default'
                }
            },
            {
                path: CONFIG.baseUrl + '/urlbuilder',
                components: {
                    main: 'urlBuilder'
                }
            },
            {
                path: CONFIG.baseUrl + '/document',
                components: {
                    main: 'document'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3',
                components: {
                    main: 'mp3'
                }
            },
            {
                path: CONFIG.baseUrl + '/image',
                components: {
                    main: 'image'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipad',
                components: {
                    main: 'mp3Ipad'
                }
            },
            {
                path: CONFIG.baseUrl + '/imagelist',
                components: {
                    main: 'imageList'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3list',
                components: {
                    main: 'mp3List'
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
                'mp3Ipad': CONFIG.templatePath + 'Mp3/Mp3Ipad.html',
                'mp3List': CONFIG.templatePath + 'Mp3/Mp3List.html',
                'imageList': CONFIG.templatePath + 'Image/ImageList.html',
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
