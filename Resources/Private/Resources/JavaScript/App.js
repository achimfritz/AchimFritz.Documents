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
            'mgcrea.jquery',
            'mgcrea.bootstrap.affix',
            'localytics.directives',
            'achimfritz.core',
            'achimfritz.solr',
            'achimfritz.urlBuilder',
            'achimfritz.document',
            'achimfritz.mp3ipod',
            'achimfritz.mp3',
            'achimfritz.music',
            'achimfritz.home',
            'achimfritz.image'
        ])
        .constant('CONFIG', {
            templatePath: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/',
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
            'image',
            'image/result',
            'image/filter',
            'image/integrity',
            'image/docList',
            'image/sitemap',
            'mp3',
            'music',
            'music/result',
            'music/filter',
            'music/player',
            'music/list',
            'music/docList',
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
        configs['urlbuilder'].components.main = 'urlBuilderIndex';
        configs['document'].components.main = 'documentIndex';
        configs['mp3'].components.main = 'mp3Index';
        configs['mp3'].components.navigation = 'mp3Navigation';

        configs['image'].components.navigation = 'imageNavigation';
        configs['image/result'].components.main = 'imageResult';
        configs['image/result'].components.navigation = 'imageNavigation';
        configs['image/filter'].components.main = 'imageFilter';
        configs['image/filter'].components.navigation = 'imageNavigation';
        configs['image/docList'].components.main = 'imageDocList';
        configs['image/docList'].components.navigation = 'imageNavigation';
        configs['image/sitemap'].components.main = 'imageSitemap';
        configs['image/sitemap'].components.navigation = 'imageNavigation';
        configs['image/integrity'].components.main = 'imageIntegrity';
        configs['image/integrity'].components.navigation = 'imageNavigation';
        
        configs['music'].components.navigation = 'musicNavigation';
        configs['music/result'].components.main = 'musicResult';
        configs['music/result'].components.navigation = 'musicNavigation';
        configs['music/filter'].components.main = 'musicFilter';
        configs['music/filter'].components.navigation = 'musicNavigation';
        configs['music/player'].components.main = 'musicPlayer';
        configs['music/player'].components.navigation = 'musicNavigation';
        configs['music/list'].components.main = 'musicList';
        configs['music/list'].components.navigation = 'musicNavigation';
        configs['music/docList'].components.main = 'musicDocList';
        configs['music/docList'].components.navigation = 'musicNavigation';

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
        paginationTemplateProvider.setPath('/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/dirPagination.tpl.html');
    }


    /* @ngInject */
    function TemplateMapping($componentLoaderProvider, CONFIG) {

        $componentLoaderProvider.setTemplateMapping(function (name) {

            var moduleNames = ['mp3Ipod', 'music', 'image', 'urlBuilder', 'document', 'home', 'mp3'];
            var path = '';
            angular.forEach(moduleNames, function(moduleName) {
                var res = name.split(moduleName);
                if (res.length === 2) {
                    var ucfirst = moduleName[0].toUpperCase() + moduleName.substring(1);
                    path = CONFIG.templatePath + ucfirst + '/' + res[1] + '.html';

                }
            });
            if (path === '') {
                console.log('cannto resolve path for ' + name);
            }
            return path;
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
