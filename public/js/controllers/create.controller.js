angular.module('create.controller', [])
	.controller('createController', function($scope, $state, $stateParams, MumService) {

		$scope.updateMum = function() {
			MumService.fetch($stateParams.mumId)
				.success(function(data) {
					$scope.mum = data;
				});
		}

		$scope.getStarted = function() {
			$state.go('^.base.product');
		}

	})

	.controller('createAccentBowController', function($scope, $state, MumService, AccentBowsService, promiseTracker) {
		$scope.tracker = promiseTracker();
		$scope.updateMum();

		AccentBowsService.get()
			.success(function(data) {
				$scope.accentbows = data;
			});

		$scope.back = function() {
			$state.go('create.base.product');
		}

		$scope.next = function() {
			var defered = $scope.tracker.createPromise();
			MumService.update($stateParams.mumId, {
				AccentBowId: $scope.accentBowId
			}).success(function(data) {
				$state.go('create.nameribbons');
			}).error(function(data) {
				AlertsService.add('danger', 'An error occured. Please try again.');
				console.log(data);
			}).finally(function() {
				defered.resolve();
			});
		}
	})

	.controller('createProductController', function($scope, $state, $stateParams, MumService, MumtypesService, promiseTracker) {
		var back = {
			'grade': '^.product',
			'size': '^.grade',
			'backing': '^.size'
		};
		var forwards = {
			'product': '^.grade',
			'grade': '^.size',
			'size': '^.backing',
			'backing': 'create.accentbow'
		};
		$scope.tracker = promiseTracker();

		$scope.selectedParent = {};

		MumtypesService.grades.get()
			.success(function(data) {
				$scope.grades = data;
			});

		MumtypesService.products.get()
			.success(function(data) {
				$scope.products = data;
			});
		MumtypesService.sizes.get()
			.success(function(data) {
				$scope.sizes = data;
			});
		MumtypesService.backings.get()
			.success(function(data) {
				$scope.backings = data;
			});

		$scope.save = function() {
			var defered = $scope.tracker.createPromise();
			MumService.update($stateParams.mumId, {
				BackingId: $scope.selectedBacking.Id
			}).success(function(data) {
				$state.go('create.accentbow');
			}).error(function(data) {
				AlertsService.add('danger', 'An error occured. Please try again.');
				console.log(data);
			}).finally(function() {
				defered.resolve();
			});
		}
		$scope.next = function() {
			for (var key in forwards) {
				if ($state.current.name.indexOf(key) > -1) {
					$state.go(forwards[key]);
					return;
				}
			}
		}
		$scope.back = function() {
			for (var key in back) {
				if ($state.current.name.indexOf(key) > -1) {
					$state.go(back[key]);
					return;
				}
			}
		}
	});
