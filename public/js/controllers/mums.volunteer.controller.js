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

		$scope.years = [{ label: "Unordered", value: "" }];
		for (var y=2014; y<=year; y++) {
			$scope.years.unshift({ label: y, value: '' + y });
		}
	})

	.controller('mumsViewController', function($scope, $state, $stateParams, promiseTracker, MumService, LettersService) {
		var updateMum = function() {
			return MumService.fetch($stateParams.mumId)
				.success(function(data) {
					$scope.mum = data;
				});
		}

		$scope.letters = {};
		$scope.bearTotal = 0;
		$scope.trinketTotal = 0;
		$scope.statuses = ["", "Designed", "Ordered", "Name ribbons made", "Bagged", "Assembled", "Quality controlled", "Devilvered"];
		$scope.forwardTracker = promiseTracker();
		$scope.backTracker = promiseTracker();
		updateMum()
			.success(function() {
				for (var i=0; i<$scope.mum.Bears.length; i++) {
					$scope.bearTotal += parseFloat($scope.mum.Bears[i].Price);
				}
				for (var i=0; i<$scope.mum.Trinkets.length; i++) {
					$scope.trinketTotal += parseFloat($scope.mum.Trinkets[i].Trinket.Price * $scope.mum.Trinkets[i].Quantity);
				}
			});
		LettersService.get()
			.success(function(data) {
				for (var i=0; i<data.length; i++) {
					$scope.letters[data[i].Id] = data[i];
				}
			});
		$scope.$parent.next = function() {
			AlertsService.add('info', 'There isn\'t actually a checkout page yet. Sorry.');
		}

		$scope.$parent.back = function() {
			$state.go('^.trinkets');
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
	})

	.factory('SearchService', function(cnOffCanvas, MumService) {
		var service;
		return service = {
			criteria: {
				Year: '2014',
			},
			search: function() {
				service.criteria.Ordered = !!service.criteria.Year;
				service.criteria.Unordered = !service.criteria.Year;
				return MumService.get(service.criteria);
			},
			canvas: cnOffCanvas({
				controller: 'searchMenuController',
				templateUrl: 'public/views/volunteer/mums/searchmenu.html'
			})
		};
	});