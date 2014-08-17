angular.module('create.controller', [])
	.controller('createController', function($scope, $state) {

		$scope.getStarted = function() {
			$state.go('^.base.product');
		}

	})

	.controller('createProductController', function($scope, $state, $stateParams, MumService, MumtypesService, promiseTracker) {

		var back = {
			'grade': '^.product',
			'size': '^.grade',
			'backing': '^.size'
		};

		$scope.tracker = promiseTracker();

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

		$scope.selectProduct = function(product) {
			$scope.product = product;
			$state.go('^.grade');
		}

		$scope.selectGrade = function(grade) {
			$scope.grade = grade;
			$state.go('^.size');
		}

		$scope.selectSize = function(size) {
			$scope.size = size;
			$state.go('^.backing');
		}

		$scope.selectBacking = function(backing) {
			var defered = $scope.tracker.createPromise();
			MumService.update($stateParams.mumId, {
				BackingId: backing.Id
			}).success(function(data) {
				console.log('Successfully edited mum.');
			}).error(function(data) {
				console.log(data);
			}).finally(function() {
				defered.resolve();
			});
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