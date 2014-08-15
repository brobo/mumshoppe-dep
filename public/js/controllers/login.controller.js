angular.module('login.controller', [])
	.controller('loginController', function($scope, $http, AlertsService, CustomerService) {

		$scope.login = function() {
			CustomerService.login($scope.email, $scope.password)
				.success(function(data) {
					console.log(data);
					AlertsService.add('success', 'Successfully logged in.');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('warning', 'Incorrect username or password.');
				});
		}

	});
	