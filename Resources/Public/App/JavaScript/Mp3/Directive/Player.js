/* global angular */

(function () {
    'use strict';

				angular
				.module('mp3App')
				.directive('player', Player);


				function Player() {
								function foo() {
												var testvar;
												this.testvar = function() {
																console.log('foo');
												}
								};

	var mp3 = function() {
		var init,
			element,
			status = 'stopped',
			setStatus,
			getStatus,
			doc,
			getInfo,
			sound,
			getSound,
			index;
		
		this.init = function(doc, i, vol, finish) {
			index = i;
			/*
			sound = soundManager.createSound({
                id: doc.webPath,
                url: doc.webPath,
                volume: vol,
                onfinish: function () {
					return finish();
				}
             });
													*/
		};
		
		this.getSound = function () {
			return sound;
		};
		
		this.setStatus = function(s) {
			status = s;
		};
		
		this.getStatus = function() {
			return status;
		};

		this.getDoc = function() {
			return doc;
		}
		
		this.getInfo = function() {
			//console.log(doc);
			var info = doc.fsArtist + ' - ' + doc.fsAlbum + ' - ' + doc.fsTitle;
			return info;
		};
		
	};
	
	var mp3Player = function() {
		var list = [],
			current = 0,
			init,
			next,
			prev,
			stop,
			play,
			info,
			volumePlus,
			volumeMinus,
			volume = 50,
			clear,
			transfer,
			pause;
				
		clear = function() {
			//soundManager.reboot();
			list = [];
			current = 0;
			info();
		};
		
		volumePlus = function() {
			if (list.length) {
				volume = list[current].getSound().volume + 5;
				list[current].getSound().setVolume(volume);
			}
			info();
		};
		
		volumeMinus = function() {
			if (list.length) {
				volume = list[current].getSound().volume - 5;
				list[current].getSound().setVolume(volume);
			}
			info();
		};
		
		next = function() {
			if (list.length) {
				list[current].getSound().stop();
				list[current].setStatus('stopped');
				if (list[current + 1]) {
					current++;		
				} else {
					current = 0;
				}
			}
			play();
		};
		
		pause = function() {
			if (list.length) {
				if (list[current].getStatus() == 'paused') {
					list[current].setStatus('playing');
					list[current].getSound().resume();
				} else {
					list[current].setStatus('paused');
					list[current].getSound().pause();
				}
			}
			info();
		};
		
		play = function() {
			if (list.length) {
				list[current].setStatus('playing');
				list[current].getSound().setVolume(volume);
				list[current].getSound().play();
			}
			info();
		};
		
		stop = function() {
			if (list.length) {
				list[current].setStatus('stopped');
				list[current].getSound().stop();
			}
			info();
		};
		
		prev = function() {
			if (list.length) {
				list[current].getSound().stop();
				list[current].setStatus('stopped');
				if (list[current - 1]) {
					current--;		
				} else {
					current = list.length -1;
				}
			}
			play();
		};
		
		info = function() {
		};
		
		transfer = function(items) {
		}
		};

								return {

												scope: {
																items: '=items'
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/App/JavaScript/Mp3/Partials/Player.html',

												link: function($scope, element, attr) {

																var mp3Items = $scope.items;
																var t3 = new foo();
																t3.testvar();
																//mp3Player.transfer($scope.items);
																//var mp3Player = new mp3Player();

																console.log($scope.items);

                $scope.current = {};

                $scope.prev = function() {
                };

                $scope.next = function() {
                };

                $scope.itemClick = function(item) {
                };
/*
																$scope.nextPage = function(pageNumber) {
																				Solr.setSetting('start', ((pageNumber - 1) * $scope.settings.rows).toString());
																				Solr.getData().then(function(data) {
																								$scope.items = data.data.response.docs;
																				});
																};
*/
																// TODO directive ?

																//Solr.getData().then(function(data) {
																				//$scope.total = data.data.response.numFound;
																//});
												},

								};

				}
}());
