
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('confirm.controller', ['ajoslin.promise-tracker'])
	.controller('ConfirmController', function($scope, $modalInstance, promiseTracker, data, after) {
		$scope.tracker = promiseTracker();

		for (var attr in data) {
			$scope[attr] = data[attr];
		}

		$scope.close = function() {
			modalInstance.dismiss();
		}

		$scope.confirmed = function() {
			var defered = $scope.tracker.createPromise();
			var promise = after();
			if (promise.then) {
				promise.then(function() {
					defered.resolve();
					$modalInstance.close();
				}, function() {
					defered.resolve();
					$modalInstance.dismiss();
				});
			} else {
				$modalInstance.close();
			}
		};
	});
