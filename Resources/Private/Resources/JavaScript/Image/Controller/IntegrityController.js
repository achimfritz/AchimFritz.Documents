/* global angular */

(function () {
    'use strict';

    angular
        .module('imageApp')
        .controller('IntegrityController', IntegrityController);

    function IntegrityController($scope, $filter, IntegrityRestService, FlashMessageService, Solr, toaster, JobRestService, $timeout) {

        var currentDirectory = '';

        $scope.view = 'list';
        $scope.finished = false;
        $scope.showAll = false;
        var now = new Date();
        $scope.newDirectory = $filter('date')(now, 'yyyy_MM_dd');

        $scope.setShowAll = function (showAll) {
            $scope.showAll = showAll;
        };

        function jobWatch(identifier) {
            $timeout(function () {
                getJob(identifier)
            }, 1500);
        };

        function getJob(identifier) {
            JobRestService.show(identifier).then(
                function (data) {
                    var job = data.data.job;
                    $scope.job = job;
                    if (job.status < 3) {
                        jobWatch(identifier);
                    } else if (job.status === 3) {
                        // success
                        toaster.pop('success', 'Job', 'finished');
                        $scope.show(currentDirectory);
                    } else if (job.status > 3) {
                        // failed
                        toaster.pop('error', 'Job', 'failed');
                    }
                },
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.error(data);
                }
            );
        };

        $scope.createJob = function (jobName, directory) {
            $scope.finished = false;
            var command = 'cd /data/www/dev && ./flow achimfritz.documents:imagesurf:' + jobName + ' --name ' + directory;
            currentDirectory = directory;
            var job = {
                'command': command
            };
            JobRestService.create(job).then(
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.show(data.data.flashMessages);
                    getJob(data.data.job.__identity);
                },
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.error(data);
                }
            );

        }

        $scope.show = function (directory) {
            $scope.finished = false;
            IntegrityRestService.show(directory).then(
                function (data) {
                    $scope.finished = true;
                    $scope.integrity = data.data.integrity;
                    $scope.view = 'show';
                },
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.error(data);
                }
            );
        };

        $scope.solr = function (directory) {
            Solr.addFilterQuery('mainDirectoryName', directory);
            toaster.pop('success', 'Solr', 'mainDirectoryName ' + directory + ' added to FilterQueries');
        };

        function list() {
            $scope.finished = false;
            IntegrityRestService.list().then(
                function (data) {
                    $scope.finished = true;
                    $scope.integrities = data.data.integrities;
                    $scope.view = 'list';
                },
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.error(data);
                }
            );
        };

        $scope.list = function () {
            list();
        };

        list();
    }
}());
