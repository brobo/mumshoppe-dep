angular.module('garnishesEdit.controller', [])
	.controller('GarnishesEditController', function($scope, $state, $stateParams, promiseTracker, GarnishesService, AlertsService) {

		$scope.REGEX_PRICE = /^[0-9]*(\.[0-9]{1,2})?$/

		$scope.tracker = promiseTracker();

		$scope.invalid = {};
		

		GarnishesService.fetch($stateParams.garnishId)
			.success(function(data) {
				$scope.garnish = data;
			});

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

		$scope.save = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				GarnishesService.update($stateParams.garnishId, $scope.garnish)
					.success(function(data) {
						$scope.garnish = {};
						AlertsService.add('success', 'Successfully saved garnish.');
						$state.go('^.all');
					}).error(function(data) {
						console.log(data);
						AlertsService.add('danger', 'An error occured while trying to save the garnish. Try again.');
					}).finally(function() {
						defered.resolve();
					});
			}
		}

	});
