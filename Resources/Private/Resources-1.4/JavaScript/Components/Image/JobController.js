/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('JobController', JobController);

    /* @ngInject */
    function JobController (JobRestService, FlashMessageService, toaster, $timeout, $rootScope, $filter, AppConfiguration) {

        var vm = this;

        vm.finished = true;
        vm.job = {};
        vm.directory = '';

        // used by the view
        vm.createJob = createJob;

        // not used by the view
        vm.initController = initController;
        vm.getJob = getJob;
        vm.restError = restError;
        vm.jobWatch = jobWatch;

        vm.initController();

        function initController() {
            var now = new Date();
            vm.directory = $filter('date')(now, 'yyyy_MM_dd_?');
        }

        function createJob(jobName, directory) {

            vm.finished = false;
            vm.directory = directory;
            var applicationRoot = AppConfiguration.getApplicationRoot();
            var command = 'cd ' + applicationRoot + ' && ./flow achimfritz.documents:imagesurf:' + jobName + ' --name ' + directory;
            var job = {
                'command': command
            };
            JobRestService.create(job).then(
                function (data) {
                    vm.finished = true;
                    FlashMessageService.show(data.data.flashMessages);
                    vm.getJob(data.data.job.__identity);
                },
                vm.restError
            );
        }

        function jobWatch(identifier) {
            $timeout(function () {
                vm.getJob(identifier);
            }, 1500)
        }

        function getJob(identifier) {
            JobRestService.show(identifier).then(
                function (data) {
                    var job = data.data.job;
                    vm.job = job;
                    if (job.status < 3) {
                        vm.jobWatch(identifier);
                    } else if (job.status === 3) {
                        // success
                        toaster.pop('success', 'Job', 'finished');
                        $rootScope.$emit('jobFinished', vm.directory);
                    } else if (job.status > 3) {
                        // failed
                        toaster.pop('error', 'Job', 'failed');
                    }
                },
                vm.restError
            );
        }

        function restError(data) {
            vm.finished = true;
            FlashMessageService.error(data);
        }

    }
})();
