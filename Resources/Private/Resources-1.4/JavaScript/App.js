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
            'achimfritz.home',
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

        var routes = [
            'index',
            'urlbuilder',
            'document',
            'mp3',
            'image',
            'imagelist',
            'mp3list',
            'music',
            'music/result',
            'music/filter',
            'music/player',
            'music/list',
            'mp3ipod',
            'mp3ipod/genre',
            'mp3ipod/artist/:genre',
            'mp3ipod/album/:genre/:artist',
            'mp3ipod/result/:genre/:artist/:album',
            'mp3ipod/search/:search',
            'mp3ipod/list',
            'mp3ipod/result/:genre/:artist/:album/:list/:search',
            'mp3ipod/currentPlaying/:genre/:artist/:album/:list/:search',
            'mp3ipod/playlist/:genre/:artist/:album/:list/:search'
        ];

        var defaultRouterComponents = {
            flashMessage: 'homeFlashMessage',
            configuration: 'homeConfiguration',
            main: 'homeDefault',
            navigation: 'homeNavigation'
        };

        var routeConfig = [];
        var configs = [];
        angular.forEach(routes, function (route) {
            var config = {
                path: CONFIG.baseUrl + '/' + route,
                components: angular.copy(defaultRouterComponents)
            };
            configs[route] = config;
        });
        configs['urlbuilder'].components.main = 'urlBuilder';
        configs['document'].components.main = 'document';
        configs['mp3'].components.main = 'mp3';
        configs['image'].components.main = 'image';
        configs['mp3list'].components.main = 'mp3List';
        configs['imagelist'].components.main = 'imageList';

        configs['music'].components.main = 'music';
        configs['music'].components.navigation = 'musicNavigation';
        configs['music/result'].components.main = 'musicResult';
        configs['music/result'].components.navigation = 'musicNavigation';
        configs['music/filter'].components.main = 'musicFilter';
        configs['music/filter'].components.navigation = 'musicNavigation';
        configs['music/player'].components.main = 'musicPlayer';
        configs['music/player'].components.navigation = 'musicNavigation';
        configs['music/list'].components.main = 'musicList';
        configs['music/list'].components.navigation = 'musicNavigation';

        configs['mp3ipod'].components.main = 'mp3IpodIndex';
        configs['mp3ipod/genre'].components.main = 'mp3IpodGenre';
        configs['mp3ipod/artist/:genre'].components.main = 'mp3IpodArtist';
        configs['mp3ipod/album/:genre/:artist'].components.main = 'mp3IpodAlbum';
        configs['mp3ipod/result/:genre/:artist/:album'].components.main = 'mp3IpodResult';
        configs['mp3ipod/search/:search'].components.main = 'mp3IpodSearch';
        configs['mp3ipod/list'].components.main = 'mp3IpodList';
        configs['mp3ipod/result/:genre/:artist/:album/:list/:search'].components.main = 'mp3IpodResult';
        configs['mp3ipod/currentPlaying/:genre/:artist/:album/:list/:search'].components.main = 'mp3IpodCurrentPlaying';
        configs['mp3ipod/playlist/:genre/:artist/:album/:list/:search'].components.main = 'mp3IpodPlaylist';

        for (var config in configs) {
            routeConfig.push(configs[config]);
        }

        $router.config(routeConfig);
    }

    /* @ngInject */
    function paginationConfiguration(paginationTemplateProvider) {
        paginationTemplateProvider.setPath('/_Resources/Static/Packages/AchimFritz.Documents/JavaScript-1.4/dirPagination.tpl.html');
    }


    /* @ngInject */
    function TemplateMapping($componentLoaderProvider, CONFIG) {

        $componentLoaderProvider.setTemplateMapping(function (name) {
            var res = name.split('mp3Ipod');
            if (res.length === 2) {
                return CONFIG.templatePath + 'Mp3Ipod/' + res[1] + '.html';
            }
            res = name.split('music');
            if (res.length === 2) {
                return CONFIG.templatePath + 'Music/' + res[1] + '.html';
            }
            res = name.split('home');
            if (res.length === 2) {
                return CONFIG.templatePath + 'Home/' + res[1] + '.html';
            }
            return {
                'urlBuilder': CONFIG.templatePath + 'UrlBuilder/UrlBuilder.html',
                'document': CONFIG.templatePath + 'Document/Document.html',
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
