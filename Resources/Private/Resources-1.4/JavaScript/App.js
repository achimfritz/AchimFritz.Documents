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
            'angularUtils.directives.dirPagination',
            'localytics.directives',
            'achimfritz.core',
            'achimfritz.widget',
            'achimfritz.filter',
            'achimfritz.solr',
            'achimfritz.urlBuilder',
            'achimfritz.document',
            'achimfritz.documentlist',
            'achimfritz.mp3',
            'achimfritz.mp3ipod',
            'achimfritz.image'
        ])
        .constant('CONFIG', {
            templatePath: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript-1.4/',
            baseUrl: '/app'
        })
        .config(TemplateMapping)
        .config(locationConfig)
        .config(paginationConfiguration)
        .run(xeditableConfig)
        .controller('AppController', AppController);



    /* @ngInject */
    function AppController ($router, CONFIG) {

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
                path: CONFIG.baseUrl + '/mp3ipod',
                components: {
                    main: 'mp3Ipod'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/filter/:filter',
                components: {
                    main: 'mp3IpodFilter'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/subfilter/:filter/:filterValue/:subFilter',
                components: {
                    main: 'mp3IpodSubFilter'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/result/:filter/:filterValue/:subFilter/:subFilterValue',
                components: {
                    main: 'mp3IpodResult'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/player/:filter/:filterValue/:subFilter/:subFilterValue',
                components: {
                    main: 'mp3IpodPlayer'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/currentPlaying/:filter/:filterValue/:subFilter/:subFilterValue',
                components: {
                    main: 'mp3IpodCurrentPlaying'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/playlist/:filter/:filterValue/:subFilter/:subFilterValue',
                components: {
                    main: 'mp3IpodPlaylist'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/start',
                components: {
                    main: 'mp3IpodStart'
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
    function paginationConfiguration(paginationTemplateProvider) {
        paginationTemplateProvider.setPath('/_Resources/Static/Packages/AchimFritz.Documents/JavaScript-1.4/dirPagination.tpl.html');
    };


    /* @ngInject */
    function TemplateMapping($componentLoaderProvider, CONFIG) {
        $componentLoaderProvider.setTemplateMapping(function (name) {
            return {
                'urlBuilder': CONFIG.templatePath + 'UrlBuilder/UrlBuilder.html',
                'document': CONFIG.templatePath + 'Document/Document.html',
                'default': CONFIG.templatePath + 'Default/Default.html',
                'mp3': CONFIG.templatePath + 'Mp3/Mp3.html',
                'mp3Ipod': CONFIG.templatePath + 'Mp3Ipod/Mp3Ipod.html',
                'mp3IpodFilter': CONFIG.templatePath + 'Mp3Ipod/Mp3IpodFilter.html',
                'mp3IpodCurrentPlaying': CONFIG.templatePath + 'Mp3Ipod/Mp3IpodCurrentPlaying.html',
                'mp3IpodPlaylist': CONFIG.templatePath + 'Mp3Ipod/Mp3IpodPlaylist.html',
                'mp3IpodSubFilter': CONFIG.templatePath + 'Mp3Ipod/Mp3IpodSubFilter.html',
                'mp3IpodResult': CONFIG.templatePath + 'Mp3Ipod/Mp3IpodResult.html',
                'mp3IpodPlayer': CONFIG.templatePath + 'Mp3Ipod/Mp3IpodPlayer.html',
                'mp3IpodStart': CONFIG.templatePath + 'Mp3Ipod/Mp3IpodStart.html',
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
