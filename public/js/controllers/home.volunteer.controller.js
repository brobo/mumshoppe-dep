angular.module('home.volunteer.controller', [])
	.controller('homeController', function($scope, $rootScope, $state, $cookieStore, AlertsService, VolunteerService) {

		$scope.login = function() {
			VolunteerService.login($scope.volunteer.Email, $scope.volunteer.Password)
				.success(function(data) {
					$cookieStore.put("volunteerToken", data);
					AlertsService.add('success', 'Successfully logged in.');
					$rootScope.updateHeader();
					$state.go('mums.all');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('warning', 'Incorrect username or password.');
				});
		}

		$scope.volunteer = {};
		$scope.invalid = {};

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		};

	});
