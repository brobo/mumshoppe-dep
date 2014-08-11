angular.module('trinketsAdd.controller', [])
	.controller('trinketsAddController', function($scope, $state, promiseTracker, TrinketsService, AlertsService) {

		$scope.REGEX_PRICE = /^[0-9]*(\.[0-9]{1,2})?$/

		$scope.tracker = promiseTracker();

		$scope.invalid = {};
		$scope.trinket = {};

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

	});
