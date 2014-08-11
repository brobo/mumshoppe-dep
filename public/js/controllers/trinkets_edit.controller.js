angular.module('trinketsEdit.controller', [])
	.controller('trinketsEditController', function($scope, $state, $stateParams, promiseTracker, TrinketsService, AlertsService) {

		$scope.REGEX_PRICE = /^[0-9]*(\.[0-9]{1,2})?$/

		$scope.tracker = promiseTracker();

		$scope.invalid = {};
		

		TrinketsService.fetch($stateParams.trinketId)
			.success(function(data) {
				$scope.trinket = data;
			});

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
				TrinketsService.update($stateParams.trinketId, $scope.trinket)
					.success(function(data) {
						$scope.trinket = {};
						AlertsService.add('success', 'Successfully saved trinket.');
						$state.go('^.all');
					}).error(function(data) {
						console.log(data);
						AlertsService.add('danger', 'An error occured while trying to save the trinket. Try again.');
					}).finally(function() {
						defered.resolve();
					});
			}
		}

	});
