/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .service('PathService', PathService);

    function PathService() {

        this.delimiter = '/';

        this.setDelimiter = function(delimiter) {
            this.delimiter = delimiter;
        };

        // 0/foo -> 2
        this.depth = function (path) {
            var splitPath = path.split(this.delimiter);
            return splitPath.length;
        };

        this.split = function(path) {
            return path.split(this.delimiter);
        };

        // /foo/bar -> 1/foo/bar
        this.prependLevel = function (path) {
            var level = this.depth(path) - 1;
            return level + this.delimiter + path;
        };

        // 0/foo/bar, 1  -> foo/bar
        // 0/foo/bar, 2  -> bar
        this.slice = function (path, depth) {
            var splitPath = path.split(this.delimiter);
            var sliced = splitPath.slice(depth);
            return sliced.join(this.delimiter);
        };

        // 0/foo -> 1/foo
        this.increase = function (path) {
            var splitPath = path.split(this.delimiter);
            var level = splitPath.shift();
            level++;
            return level + this.delimiter + splitPath.join(this.delimiter);
        };

        // 0/foo -> 0
        this.decrease = function (path) {
            var splitPath = path.split(this.delimiter);
            var last = splitPath.pop();
            return splitPath.join(this.delimiter);
        };

        // 1/foo -> 0/foo
        this.decreaseLevel = function (path) {
            var splitPath = path.split(this.delimiter);
            var level = splitPath.shift();
            level--;
            return level + this.delimiter + splitPath.join(this.delimiter);
        };

        // 0/foo -> foo
        this.last = function (path) {
            var splitPath = path.split(this.delimiter);
            var last = splitPath.pop();
            return last;
        };

        // 2/foo/bar -> 1/foo
        // 0/foo -> ''
        this.decreaseFq = function (path) {
            var splitPath = path.split(this.delimiter);
            var last = splitPath.pop();
            var level = parseInt(splitPath.shift());
            if (level === 0) {
                return '';
            }
            level--;
            return level + this.delimiter + splitPath.join(this.delimiter);
        };

    }
}());
