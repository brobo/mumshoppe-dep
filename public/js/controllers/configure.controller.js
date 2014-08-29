angular.module('configure.controller', [])
	.controller('configureController', function($scope, $state) {
		$scope.active = null;
		$scope.tabs = [
			{heading: "Accent Bows",	route:"configure.accentbows"},
			{heading: "Bears",			route:"configure.bears"},
			{heading: "Trinkets",		route:"configure.trinkets.all"},
			{heading: "Letters",		route:"configure.letters"},
			{heading: "Mum Types",		route:"configure.mumtypes.grade"},
			{heading: "Volunteers",		route:"configure.volunteers"}
		];

		$scope.isActive = function(tab) {
			return $state.includes(tab.route);
		}
	})

	.controller('configureVolunteerController', function($scope, $state, $modal, VolunteerService) {
		$scope.updateVolunteers = function() {
			VolunteerService.get()
				.success(function(data) {
					$scope.volunteers = data;
				});
		}
		$scope.updateVolunteers();

		$scope.register = function() {
			$modal.open({
				templateUrl: 'newVolunteerForm',
				controller: 'editVolunteerController',
				size: 'lg',
				resolve: {
					volunteer: function() {
						return {};
					},
					save: function() {
						return VolunteerService.register;
					}
				}
			}).result.then(function() {
				$scope.updateVolunteers();
			});
		}

		$scope.update = function(volunteer) {
			$modal.open({
				templateUrl: 'updateVolunteerForm',
				controller: 'editVolunteerController',
				size: 'lg',
				resolve: {
					volunteer: function() {
						return angular.copy(volunteer);
					},
					save: function() {
						return function(volunteer) {
							return VolunteerService.update(volunteer.Id, volunteer);
						};
					}
				}
			}).result.then(function() {
				$scope.updateVolunteers();
			});
		}
	})

	.controller('editVolunteerController', function($scope, $modalInstance, promiseTracker, save, volunteer, AlertsService, VolunteerService) {

		$scope.volunteer = volunteer;
		$scope.tracker = promiseTracker();

		$scope.invalid = {};
		$scope.confirmPassword = {};
		$scope.REGEX_PHONE = /^(\([0-9]{3}\) |[0-9]{3}[- ]?)[0-9]{3}[- ]?[0-9]{4}$/;

		$scope.register = function(form) {
			console.log('register');
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				save($scope.volunteer)
					.success(function(data) {
						$scope.volunteer = {};
						AlertsService.add('success', 'Successfully saved volunteer.');
						$modalInstance.close();
					}).error(function(data) {
						console.log('Error: ' + data);
						AlertsService.add('warning', 'An error occured while saving the volunteer.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$scope.volunteer = {};
			$modalInstance.dismiss();
		}

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		};

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