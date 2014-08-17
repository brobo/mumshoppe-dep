angular.module('login.controller', [])
	.controller('loginController', function($scope, $cookieStore, $state, AlertsService, CustomerService) {

		$scope.login = function() {
			CustomerService.login($scope.email, $scope.password)
				.success(function(data) {
					console.log(data);
					$cookieStore.put("customer", data);
					AlertsService.add('success', 'Successfully logged in.');
					$state.go('mums');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('warning', 'Incorrect username or password.');
				});
		}

	});
	