
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('home.controller', [])
	.controller('homeController', function($scope, $rootScope, $state, $cookieStore, AlertsService, CustomerService) {

		$scope.login = function() {
			CustomerService.login($scope.customer.Email, $scope.customer.Password)
				.success(function(data) {
					$cookieStore.put("customerToken", data);
					AlertsService.add('success', 'Successfully logged in.');
					$rootScope.updateHeader();
					$state.go('mums.all');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('warning', 'Incorrect username or password.');
				});
		}

		$scope.customer = {};
		$scope.invalid = {};
		$scope.confirmPassword = {};
		$scope.REGEX_PHONE = /^(\([0-9]{3}\) |[0-9]{3}[- ]?)[0-9]{3}[- ]?[0-9]{4}$/;

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		};

		$scope.register = function(form) {
			if (form.$valid) {
				CustomerService.register($scope.customer)
					.success(function(data) {
						AlertsService.add('success', 'Successfully registered! You may now log in.');
						$state.go('home.login');
					}).error(function(data) {
						AlertsService.add('danger', 'Something went wrong. Please try again.');
					});
			}
		}

		$scope.verifyEmail = function() {
			CustomerService.verifyEmail($scope.customer.Email)
				.success(function(data) {
					if (!data.valid) {
						$scope.invalid.duplicateEmail = true;
					} else {
						$scope.invalid.duplicateEmail = false;
					}
				});
		}

		$scope.verifyPasswords = function() {
			$scope.invalid.mismatchPasswords = $scope.customer.Password != $scope.confirmPassword.value;
		}

	})

	.controller('lostPasswordController', function($scope, $state, promiseTracker, AlertsService, LostPasswordService) {
		$scope.tracker = promiseTracker();

		$scope.recover = function(email) {
			var defered = $scope.tracker.createPromise();
			LostPasswordService.sendRecoveryEmail(email)
				.success(function(data) {
					if (data.success) {
						AlertsService.add('success', 'An email has been sent to you. Please follow the directions there!');
						$scope.go('home.index');
					} else {
						AlertsService.add('warning', 'Unable to send the email. Please double check the email address you entered.');
					}
				}).error(function(data) {
					AlertsService.add('danger', 'An error occured. Please try again latter.');
				}).finally(function() {
					defered.resolve();
				});
		}
	})

	.controller('recoverPasswordController', function($scope, $state, $stateParams, promiseTracker, AlertsService, LostPasswordService) {
		$scope.tracker = promiseTracker();

		$scope.invalid = {};
		$scope.confirmPassword = {};

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		};

		$scope.verifyPasswords = function() {
			$scope.invalid.mismatchPasswords = $scope.Password != $scope.confirmPassword.value;
		}

		$scope.recover = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				LostPasswordService.recoverPassword($stateParams.key, $scope.Password)
					.success(function(data) {
						if (data.success) {
							AlertsService.add('success', 'Successfully changed password. You may now log in.');
							$state.go('home.index');
						} else {
							AlertsService.add('warning', 'Unable to change password: ' + data.reason);
						}
					}).error(function(data) {
						AlertsService.add('danger', 'An error occured. Please try again latter.');
					}).finally(function() {
						defered.resolve();
					});
			}
		}
	});
