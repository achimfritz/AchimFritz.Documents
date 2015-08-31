/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.app', [
            'ngNewRouter',
            'ngSanitize',
            'achimfritz.core',
            'achimfritz.solr',
            'achimfritz.urlBuilder',
            'achimfritz.document'
        ])
        .constant('CONFIG', {
            templatePath: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript-1.4/',
            baseUrl: '/achimfritz.documents/app14'
        })
        .config(TemplateMapping)
        .config(locationConfig)
        .controller('AppController', AppController)
        .controller('UrlBuilderAppController', UrlBuilderAppController)
        .controller('DocumentAppController', DocumentAppController);

    /* @ngInject */
    function DocumentAppController ($router, CONFIG) {
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
                path: CONFIG.baseUrl + '/default/urlbuilder',
                components: {
                    main: 'urlBuilder',
                    navigation: 'navigation'
                }
            },
            {
                path: CONFIG.baseUrl + '/default/document',
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
                'navigation': CONFIG.templatePath + 'Navigation/Navigation.html'
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
}());
