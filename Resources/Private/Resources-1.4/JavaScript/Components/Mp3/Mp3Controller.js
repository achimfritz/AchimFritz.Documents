/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3Controller', Mp3Controller);

    /* @ngInject */
    function Mp3Controller (Solr, CONFIG, $rootScope) {

        var vm = this;
        vm.data = {};

        vm.templatePaths = {
            nav: CONFIG.templatePath + 'Mp3/Nav.html',
            filterNav: CONFIG.templatePath + 'Mp3/FilterNav.html',
            resultTable: CONFIG.templatePath + 'Mp3/ResultTable.html',
            playerControls: CONFIG.templatePath + 'Mp3/PlayerControls.html',
            resultHead: CONFIG.templatePath + 'Mp3/ResultHead.html'
        };

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            Solr.request().then(function (response){
                vm.data = response.data;
            })
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            vm.data = data;
        });

    }
})();
