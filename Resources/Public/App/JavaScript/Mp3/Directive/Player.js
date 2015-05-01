/* global angular */

(function () {
    'use strict';

				angular
				.module('mp3App')
				.directive('player', Player);


				function Player() {

	function Mp3() {
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
		
		this.init = function(doc) {
				this.doc = doc;
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
			var info = doc.fsArtist + ' - ' + doc.fsAlbum + ' - ' + doc.fsTitle;
			return info;
		};
		
	};
	
	function Mp3Player() {
		var list = [],
			current = 0,
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

			this.items = function() {
				return list;
			}
				
		this.clear = function() {
			//soundManager.reboot();
			list = [];
			current = 0;
				this.info = 'list empty';
		};
		
		this.volumePlus = function() {
			if (list.length) {
				volume = list[current].getSound().volume + 5;
				list[current].getSound().setVolume(volume);
			}
		};
		
		this.volumeMinus = function() {
			if (list.length) {
				volume = list[current].getSound().volume - 5;
				list[current].getSound().setVolume(volume);
			}
		};
		
		this.next = function() {
			if (list.length) {
				//list[current].getSound().stop();
				list[current].setStatus('stopped');
				if (list[current + 1]) {
					current++;		
				} else {
					current = 0;
				}
			}
			this.play();
		};
		
		this.pause = function() {
			if (list.length) {
				if (list[current].getStatus() == 'paused') {
					list[current].setStatus('playing');
					//list[current].getSound().resume();
				} else {
					list[current].setStatus('paused');
					//list[current].getSound().pause();
				}
			}
		};
		
		this.play = function() {
			if (list.length) {
				list[current].setStatus('playing');
				//list[current].getSound().setVolume(volume);
				//list[current].getSound().play();
			}
		};
		
		this.stop = function() {
			if (list.length) {
				list[current].setStatus('stopped');
				//list[current].getSound().stop();
			}
		};
		
		this.prev = function() {
			if (list.length) {
				//list[current].getSound().stop();
				list[current].setStatus('stopped');
				if (list[current - 1]) {
					current--;		
				} else {
					current = list.length -1;
				}
			}
			this.play();
		};
		
						this.transfer = function(items) {
								angular.forEach(items, function(item) {
												var mp3 = new Mp3();
												mp3.init(item);
												list.push(mp3);
								});
								this.info = list.length + ' items transferd';
						}
		};

								return {

												scope: {
																items: '=items'
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/App/JavaScript/Mp3/Partials/Player.html',

												link: function($scope, element, attr) {

																var mp3Player = new Mp3Player();
																mp3Player.transfer($scope.items);
																$scope.player = mp3Player;

                $scope.transfer = function() {
																				mp3Player.transfer($scope.items);
                };
												},

								};

				}
}());
