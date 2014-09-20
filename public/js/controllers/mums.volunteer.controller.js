
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('mums.volunteer.controller', [])
	.controller('mumsController', function() {

	})

	.controller('mumsAllController', function($scope, $q, MumService, SearchService) {

		$scope.mums = SearchService.mums;
		$scope.criteria = SearchService.criteria;
		$scope.toggle = function() {
			SearchService.canvas.toggle()
				.then(function() {
					return SearchService.search();
				})
				.then(function(response) {
					$scope.mums = response.data;
				});
		}

		SearchService.search()
			.success(function(data) {
				$scope.mums = data;
			});

	})

	.controller('searchMenuController', function($scope, SearchService) {
		var year = new Date().getFullYear();

		$scope.criteria = SearchService.criteria;
		$scope.search = function() {
			return SearchService.canvas.toggle();
		}
	})

	.controller('mumsViewController', function($scope, $state, $stateParams, promiseTracker, MumService, LettersService, AccessoriesService, PayService) {
		var updateMum = function() {
			return MumService.fetch($stateParams.mumId)
				.success(function(data) {
					$scope.mum = data;
					$scope.priceType = $scope.mum.Product.Name == 'Mum' ? 'MumPrice' : 'GarterPrice';
				});
		}

		$scope.letters = {};
		$scope.bearTotal = 0;
		$scope.accessoryTotal = 0;
		$scope.statuses = ["", "Designed", "Ordered", "Name ribbons made", "Bagged", "Assembled", "Quality controlled", "Devilvered"];
		$scope.forwardTracker = promiseTracker();
		$scope.backTracker = promiseTracker();
		$scope.paidTracker = promiseTracker();
		updateMum()
			.success(function() {
				for (var i=0; i<$scope.mum.Bears.length; i++) {
					$scope.bearTotal += parseFloat($scope.mum.Bears[i].Price);
				}
				for (var i=0; i<$scope.mum.Accessories.length; i++) {
					$scope.accessoryTotal += parseFloat($scope.mum.Accessories[i].Accessory[$scope.priceType] * $scope.mum.Accessories[i].Quantity);
				}
			});
		LettersService.get()
			.success(function(data) {
				for (var i=0; i<data.length; i++) {
					$scope.letters[data[i].Id] = data[i];
				}
			});
		AccessoriesService.categories.get()
			.success(function(data) {
				$scope.categories = data;
			});
		$scope.$parent.next = function() {
			AlertsService.add('info', 'There isn\'t actually a checkout page yet. Sorry.');
		}

		$scope.$parent.back = function() {
			$state.go('^.accessories');
		}

		$scope.setStatus = function(statusId, tracker) {
			var defered = tracker.createPromise();
			MumService.setStatus($stateParams.mumId, statusId)
				.success(function(data) {
					$scope.mum = data;
				}).finally(function() {
					defered.resolve();
				});
		}

		$scope.markPaid = function() {
			var defered = $scope.paidTracker.createPromise();
			PayService.full.markPaid($stateParams.mumId)
				.success(function() {
					updateMum();
				}).finally(function() {
					defered.resolve();
				});
		}
	})

	.factory('SearchService', function(cnOffCanvas, MumService) {
		var service;
		return service = {
			criteria: {
				Ordered: true,
			},
			search: function() {
				return MumService.get(service.criteria);
			},
			canvas: cnOffCanvas({
				controller: 'searchMenuController',
				templateUrl: 'public/views/volunteer/mums/searchmenu.html'
			})
		};
	});