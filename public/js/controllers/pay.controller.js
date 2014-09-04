
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('pay.controller', [])
	.controller('payIndexController', function($scope, $stateParams, $window, promiseTracker, AlertsService, PayService, MumService) {
		$scope.tracker = promiseTracker();
		$scope.redirecting = false;

		MumService.fetch($stateParams.mumId)
			.success(function(data) {
				$scope.mum = data;
			});


		$scope.startPayFlow = function() {
			var defered = $scope.tracker.createPromise();
			PayService.full.startPayFlow($stateParams.mumId)
				.success(function(data) {
					if (data.location) {
						$scope.redirecting = true;
						$scope.approvalUrl = data.location;
						$window.location.href = $scope.approvalUrl;
					}
				})
				.error(function() {
					AlertsService.add('danger', 'An error has occured. Please try again.');
				}).finally(function() {
					defered.resolve();
				});
		}
	})

	.controller('payFinalizeController', function($scope, $stateParams, $location, promiseTracker, AlertsService, PayService) {
		$scope.tracker = promiseTracker();
		$scope.redirecting = false;
		$scope.finalize = function() {
			var defered = $scope.tracker.createPromise();
			PayService.full.finalize($stateParams.mumId, $location.search().PayerID)
				.success(function(data) {
					console.log(data);
					if (data.success)
						$state.go('^.thankyou');
					else
						AlertsService.add('warning', 'The payment was not approved. Try again or visit the Mum Shoppe.');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'An error occured. Please try again.');
				}).finally(function(data) {
					defered.resolve();
				});
		}
	});