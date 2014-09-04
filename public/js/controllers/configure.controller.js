angular.module('configure.controller', [])
	.controller('configureController', function($scope, $state) {
		$scope.active = null;
		$scope.tabs = [
			{heading: "Accent Bows",	route:"configure.accentbows"},
			{heading: "Bears",			route:"configure.bears"},
			{heading: "Accessories",		route:"configure.accessories.all"},
			{heading: "Letters",		route:"configure.letters"},
			{heading: "Mum Types",		route:"configure.mumtypes.grade"},
			{heading: "Volunteers",		route:"configure.volunteers"},
			{heading: "Yearly",			route:"configure.yearly"}
		];

		$scope.isActive = function(tab) {
			return $state.includes(tab.route);
		}
	})

	.controller('truncateController', function($scope, $modalInstance, AlertsService, MumService, promiseTracker) {
		$scope.password = {};
		$scope.tracker = promiseTracker();
		$scope.truncate = function() {
			var deferred = $scope.tracker.createPromise();
			MumService.yearly.truncate($scope.password.value)
				.success(function(data) {
					if (data.success) {
						AlertsService.add('success', 'The mums have been truncated!');
					} else {
						AlertsService.add('danger', 'An error occured; please try again.');
					}
				}).error(function(data) {
					AlertsService.add('warning', 'Something went wrong; are you sure you have permission to do that?');
				}).finally(function() {
					$modalInstance.close();
					deferred.resolve();
				});
		}
		$scope.cancel = function() {
			AlertsService.add('info', 'A catastrophe avoided! You canceled the truncation.');
			$modalInstance.dismiss();
		}
	})

	.controller('configureYearlyController', function($scope, $state, $modal, AlertsService, ConfirmService, MumService, promiseTracker) {
		$scope.orderTracker = promiseTracker();
		MumService.yearly.canOrder()
			.success(function(data) {
				$scope.canOrder = data.AllowOrders;
			});

		$scope.startOrders = function() {
			var deferred = $scope.orderTracker.createPromise();
			MumService.yearly.startOrder()
				.success(function(data) {
					AlertsService.add('success', 'Successfully opened up orders!');
				}).error(function(data) {
					AlertsService.add('danger', 'Are you sure you have permission to do that?');
				}).finally(function() {
					deferred.resolve();
				});
				$scope.canOrder = true;
		};

		$scope.stopOrders = function() {
			var deferred = $scope.orderTracker.createPromise();
			MumService.yearly.stopOrder()
				.success(function(data) {
					AlertsService.add('success', 'Successfully closed orders!');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'Are you sure you have permission to do that?');
				}).finally(function() {
					deferred.resolve();
				});
				$scope.canOrder = false;
		};

		$scope.truncateOrders = function() {
			ConfirmService.confirm({
				head: "Truncate Mums?!?",
				body: "WOAH! This is a VERY DANGEROUS THING TO DO! This will delete all orders since the beginning of time! Are you absolutely, 100% positive, completely certain, no-doubt about it sure you really want to do this?"
			}, function() {
				return ConfirmService.confirm({
					head: "Wait, seriously?!?",
					body: "No, seriously, think about this. All mums, unpaid and potentialy paid mums, will be deleted. There is no way to recover this data once it's gone! Are you definately, unquestionably, irrefutably ready to delete all mums?"
				}, function() {
					return $modal.open({
						templateUrl: 'public/views/volunteer/configure/confirmTruncation.html',
						controller: 'truncateController',
						size: 'lg'
					});
				});
			});
		};
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

		$scope.editPermissions = function(volunteer) {
			$modal.open({
				templateUrl: 'public/views/volunteer/configure/permissions.html',
				controller: 'editPermissionsController',
				size: 'lg',
				resolve: {
					volunteer: function() {
						return angular.copy(volunteer);
					}
				}
			}).result.then(function() {
				$scope.updateVolunteers();
			});
		};
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
	})

	.controller('editPermissionsController', function($scope, $modalInstance, volunteer, AlertsService, VolunteerService, promiseTracker) {
		$scope.tracker = promiseTracker();
		$scope.permissions = [
			{Name: "Configure Items", Value: 0},
			{Name: "View Mums", Value: 1},
			{Name: "Mark Mums Paid", Value: 2},
			{Name: "Delete Mums", Value: 3},
			{Name: "Truncate Mums", Value: 4},
			{Name: "Change Volunteer Permissions", Value: 5},
			{Name: "Delete Volunteer", Value: 6},
			{Name: "Create Volunteer", Value: 7},
			{Name: "Toggle Orders", Value: 8}
		];

		$scope.volunteerPermissions = {};

		for (var i=0; i<$scope.permissions.length; i++) {
			$scope.volunteerPermissions[i] = !!(volunteer.Rights & (1 << $scope.permissions[i].Value));
		}

		$scope.cancel = function() {
			$modalInstance.dismiss();
		}

		$scope.save = function() {
			var deferred = $scope.tracker.createPromise();

			var rights = 0;
			for (var i=0; i<$scope.permissions.length; i++)
				if ($scope.volunteerPermissions[i])
					rights |= (1 << i);

			VolunteerService.rights(volunteer.Id, rights)
				.success(function(data) {
					if (data.success) {
						AlertsService.add('success', 'Successfully changed permissions!');
						$modalInstance.close();
					} else {
						AlertsService.add('danger', 'An error occured while changing permissions. Please try again.');
						$modalInstance.dismiss();
					}
				}).error(function(data) {
					console.log(data);
					AlertsService.add('warning', 'Unable to change permissions. Do you have the rights to do that?');
					$modalInstance.dismiss();
				}).finally(function() {
					deferred.resolve();
				})
		}
	});
