/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageIntegrityController', ImageIntegrityController);

    /* @ngInject */
    function ImageIntegrityController ($filter, CoreApiService, AppConfiguration, $timeout, Solr, $rootScope) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.view = 'list';
        vm.job = {};
        vm.coreApi = CoreApiService;
        vm.directory = $filter('date')(new Date(), 'yyyy_MM_dd_?');
        vm.solr = Solr;

        vm.createJob = createJob;
        vm.resetJob = resetJob;

        CoreApiService.integrityList();

        function resetJob() {
            vm.job = {};
        }

        function createJob(jobName, directory) {
            vm.directory = directory;
            var applicationRoot = AppConfiguration.getApplicationRoot();
            var command = 'cd ' + applicationRoot + ' && ./flow achimfritz.documents:imagesurf:' + jobName + ' --name ' + vm.directory;
            var job = {
                'command': command
            };
            CoreApiService.jobCreate(job);
        }

        function jobWatch(identifier) {
            $timeout(function () {
                CoreApiService.jobShow(identifier);
            }, 1500)
        }

        var listener = $scope.$on('core:apiCallSuccess', function(event, data) {
            if (angular.isDefined(data.data.integrities)){
                vm.integrities = data.data.integrities;
                vm.view = 'list';
            } else if (angular.isDefined(data.data.integrity)) {
                vm.integrity = data.data.integrity;
                vm.view = 'show';
            } else if (angular.isDefined(data.data.job)) {
                // created or show
                var job = data.data.job;
                vm.job = job;
                if (job.status < 3) {
                    jobWatch(job.__identity);
                } else if (job.status === 3) {
                    $rootScope.$broadcast('home:flashMessage', [{severity: 'OK', message: 'Job executed'}]);
                    CoreApiService.integrityShow(vm.directory);
                } else if (job.status > 3) {
                    $rootScope.$broadcast('home:flashMessage', [{severity: 'error', message: 'Job failed'}]);
                    CoreApiService.integrityShow(vm.directory);
                }
            }
        });

        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            listener();
            killerListener();
        });

    }
})();
