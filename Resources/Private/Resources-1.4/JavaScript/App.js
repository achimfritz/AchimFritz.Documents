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
       /*
        .config(['$componentLoaderProvider', function($componentLoaderProvider){
            $componentLoaderProvider.setTemplateMapping(function (name) {
                return CONFIG.templatePath + name + '/' + name + '.html';
                //return 'parallel/components/' + name + '/' + name + '.html';
            });
        }])
        */
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
                path: CONFIG.baseUrl + '/mp3ipod/genre',
                components: {
                    main: 'mp3IpodGenre'
                }
            },
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
            return {
                'urlBuilder': CONFIG.templatePath + 'UrlBuilder/UrlBuilder.html',
                'document': CONFIG.templatePath + 'Document/Document.html',
                'default': CONFIG.templatePath + 'Default/Default.html',
                'mp3': CONFIG.templatePath + 'Mp3/Mp3.html',

                'mp3Ipod': CONFIG.templatePath + 'Mp3Ipod/Mp3Ipod.html',
                'mp3IpodArtist': CONFIG.templatePath + 'Mp3Ipod/Artist.html',
                'mp3IpodAlbum': CONFIG.templatePath + 'Mp3Ipod/Album.html',
                'mp3IpodGenre': CONFIG.templatePath + 'Mp3Ipod/Genre.html',
                'mp3IpodCurrentPlaying': CONFIG.templatePath + 'Mp3Ipod/CurrentPlaying.html',
                'mp3IpodPlaylist': CONFIG.templatePath + 'Mp3Ipod/Playlist.html',
                'mp3IpodResult': CONFIG.templatePath + 'Mp3Ipod/Result.html',
                'mp3IpodPlayer': CONFIG.templatePath + 'Mp3Ipod/Player.html',
                'mp3IpodSearch': CONFIG.templatePath + 'Mp3Ipod/Search.html',
                'mp3IpodList': CONFIG.templatePath + 'Mp3Ipod/List.html',

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
