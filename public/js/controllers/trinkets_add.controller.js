angular.module('trinketsAdd.controller', [])
	.controller('trinketsAddController', function($scope, $state, promiseTracker, TrinketsService, AlertsService) {

		$scope.REGEX_PRICE = /^[0-9]*(\.[0-9]{1,2})?$/

		$scope.tracker = promiseTracker();

		$scope.invalid = {};
		$scope.trinket = {};
		$scope.categoryIds = {};
		$scope.categoryNames = {};

		$scope.updateCategories = function() {
			TrinketsService.categories.get()
				.success(function(data) {
					$scope.categories = data;
				});
		}
		$scope.updateCategories();

		$scope.validate = function(field) {
			$scope.invalid[field] = $scope.trinketForm[field].$invalid;
		};

		$scope.classSelected = function() {
			return $scope.trinket.Underclassman || $scope.trinket.Junior || $scope.trinket.Senior;
		}

		$scope.cancel = function() {
			$scope.student = {};
			$state.go('^.all');
		}

		$scope.save = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				TrinketsService.create($scope.trinket)
					.success(function(data) {
						console.log(data);
						$scope.trinket = {};
						AlertsService.add('success', 'Successfully created trinket.');
						$state.go('^.all');
					}).error(function(data) {
						console.log(data);
						AlertsService.add('danger', 'An error occured while trying to create the trinket. Try again.');
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.addCategory = function() {
			$modal.open({
				templateUrl: 'categoryForm',
				controller: 'categoryController',
				size: 'lg',
			}).result.then(function() {
				$scope.updateCategories();
			});
		}

	})

	.controller('categoryController', function($scope, TrinketsService, promiseTracker, $modalInstance) {
		$scope.tracker = promiseTracker();
		$scope.category = {};

		$scope.saveCategory = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				TrinketsService.categories.create($scope.category)
					.success(function(data) {
						$modalInstance.close();
					}).error(function(data) {
						AlertsService.add('danger', 'An error occured. Please try again.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$modalInstance.dismiss();
		}
	});
