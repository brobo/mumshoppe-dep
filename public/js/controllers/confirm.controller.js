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
			after().then(function() {
				defered.resolve();
				$modalInstance.close();
			}, function() {
				defered.resolve();
				$modalInstance.dismiss();
			});
		};
	});
