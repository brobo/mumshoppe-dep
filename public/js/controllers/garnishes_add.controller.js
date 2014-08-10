angular.module('garnishesAdd.controller', [])
	.controller('GarnishesAddController', function($scope, $state, promiseTracker, GarnishesService, AlertsService) {

		$scope.REGEX_PRICE = /^[0-9]*(\.[0-9]{1,2})?$/

		$scope.tracker = promiseTracker();

		$scope.invalid = {};
		$scope.garnish = {};

		$scope.validate = function(field) {
			$scope.invalid[field] = $scope.garnishForm[field].$invalid;
		};

		$scope.classSelected = function() {
			return $scope.garnish.Underclassman || $scope.garnish.Junior || $scope.garnish.Senior;
		}

		$scope.cancel = function() {
			$scope.student = {};
			$state.go('^.all');
		}

		$scope.add = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				GarnishesService.create($scope.garnish)
					.success(function(data) {
						$scope.garnish = {};
						AlertsService.add('success', 'Successfully created garnish.');
						$state.go('^.all');
					}).error(function(data) {
						console.log(data);
						AlertsService.add('danger', 'An error occured while trying to create the garnish. Try again.');
					}).finally(function() {
						defered.resolve();
					});
			}
		}

	});
