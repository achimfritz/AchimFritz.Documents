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
            'achimfritz.music',
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
                path: CONFIG.baseUrl + '/music',
                components: {
                    main: 'musicResult',
                    navigation: 'musicNavigation'
                }
            },


            {
                path: CONFIG.baseUrl + '/music/result',
                components: {
                    main: 'musicResult',
                    navigation: 'musicNavigation'
                }
            },

            {
                path: CONFIG.baseUrl + '/music/filter',
                components: {
                    main: 'musicFilter',
                    navigation: 'musicNavigation'
                }
            },

            {
                path: CONFIG.baseUrl + '/music/player',
                components: {
                    main: 'musicPlayer',
                    navigation: 'musicNavigation'
                }
            },

            {
                path: CONFIG.baseUrl + '/mp3ipod',
                components: {
                    main: 'mp3IpodIndex'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/genre',
                components: {
                    main: 'mp3IpodGenre'
                }
            },
            /*
            nur/ohne mit einem artist
            filter on after search
            sort newest
             */
            {
                path: CONFIG.baseUrl + '/mp3ipod/artist/:genre',
                components: {
                    main: 'mp3IpodArtist'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/album/:genre/:artist',
                components: {
                    main: 'mp3IpodAlbum'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/result/:genre/:artist/:album',
                components: {
                    main: 'mp3IpodResult'
                }
            },


            {
                path: CONFIG.baseUrl + '/mp3ipod/search/:search',
                components: {
                    main: 'mp3IpodSearch'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/list',
                components: {
                    main: 'mp3IpodList'
                }
            },

            {
                path: CONFIG.baseUrl + '/mp3ipod/result/:genre/:artist/:album/:list/:search',
                components: {
                    main: 'mp3IpodResult'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/currentPlaying/:genre/:artist/:album/:list/:search',
                components: {
                    main: 'mp3IpodCurrentPlaying'
                }
            },
            {
                path: CONFIG.baseUrl + '/mp3ipod/playlist/:genre/:artist/:album/:list/:search',
                components: {
                    main: 'mp3IpodPlaylist'
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

        /*
        $componentLoaderProvider.setTemplateMapping(function (name) {
            var templateName = name.charAt(0).toUpperCase() + name.slice(1);
            return CONFIG.templatePath + templateName + '/' + templateName + '.html';
        });
*/
        $componentLoaderProvider.setTemplateMapping(function (name) {
            var res = name.split('mp3Ipod');
            if (res.length === 2) {
                return CONFIG.templatePath + 'Mp3Ipod/' + res[1] + '.html';
            }
            res = name.split('music');
            if (res.length === 2) {
                return CONFIG.templatePath + 'Music/' + res[1] + '.html';
            }
            return {
                'urlBuilder': CONFIG.templatePath + 'UrlBuilder/UrlBuilder.html',
                'document': CONFIG.templatePath + 'Document/Document.html',
                'default': CONFIG.templatePath + 'Default/Default.html',
                'mp3': CONFIG.templatePath + 'Mp3/Mp3.html',
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
