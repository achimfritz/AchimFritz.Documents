/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.service('PathService', PathService);

				function PathService() {

								//this.delimiter = '/';

								// 0/foo -> 1/foo
								this.increase = function(path) {
												var splitPath = path.split(delimiter);
												var level = splitPath.shift();
												level ++;
												return level + delimiter + splitPath.join(delimiter);
								};

								// 0/foo -> 0
								this.decrease = function(path) {
												var splitPath = path.split(delimiter);
												var last = splitPath.pop();
												return splitPath.join(delimiter);
								};

								// 2/foo/bar -> 1/foo
								// 0/foo -> ''
								this.decreaseFq = function(path) {
												var splitPath = path.split(delimiter);
												var last = splitPath.pop();
												var level = parseInt(splitPath.shift());
												if (level === 0) {
																return '';
												}
												level--;
												return level + delimiter + splitPath.join(delimiter);
								};

				}
}());


