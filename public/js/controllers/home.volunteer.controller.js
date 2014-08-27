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
		$scope.confirmPassword = {};
		$scope.REGEX_PHONE = /^(\([0-9]{3}\) |[0-9]{3}[- ]?)[0-9]{3}[- ]?[0-9]{4}$/;

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		};

		$scope.register = function(form) {
			if (form.$valid) {
				VolunteerService.register($scope.volunteer)
					.success(function(data) {
						AlertsService.add('success', 'Successfully registered! You may now log in.');
						$scope.volunteer = {};
					}).error(function(data) {
						AlertsService.add('danger', 'Something went wrong. Please try again.');
					});
			}
		}

		$scope.verifyEmail = function() {
			VolunteerService.verifyEmail($scope.volunteer.Email)
				.success(function(data) {
					if (!data.valid) {
						$scope.invalid.duplicateEmail = true;
					} else {
						$scope.invalid.duplicateEmail = false;
					}
				});
		}

		$scope.verifyPasswords = function() {
			$scope.invalid.mismatchPasswords = $scope.volunteer.Password != $scope.confirmPassword.value;
		}

	});
