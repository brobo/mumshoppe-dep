angular.module('accentbows.controller', [])
	.controller('accentbowsController', function($scope, $modal, promiseTracker, AccentBowsService, AlertsService, ConfirmService, MumtypesService) {
		
		$scope.updateItems = function() {
			MumtypesService.grades.get().success(function(data) {		
				$scope.grades = data;
			});
			AccentBowsService.get().success(function(data) {
				$scope.bows = data;
			});
		}

		$scope.updateItems();

		$scope.imageBow = function(id) {
			$modal.open({
				templateUrl: 'imageForm',
				controller: 'imageAccentbowController',
				size: 'lg',
				resolve: {
					id: function() {
						return id;
					}
				}
			});
		}

		$scope.addBow = function(grade) {
			$modal.open({
				templateUrl: 'bowForm',
				controller: 'editBowController',
				size: 'lg',
				resolve: {
					bow: function() {
						return {
							GradeId: grade.Id
						};
					},
					save: function() {
						return AccentBowsService.create;
					}
				}
			}).result.then(function() {
				$scope.updateItems();
			});
		}

		$scope.editBow = function(bow) {
			$modal.open({
				templateUrl: 'bowForm',
				controller: 'editBowController',
				size: 'lg',
				resolve: {
					bow: function() {
						return angular.copy(bow);
					},
					save: function() {
						return function(bow) {
							return AccentBowsService.update(bow.Id, bow);
						};
					}
				}
			}).result.then(function() {
				$scope.updateItems();
			});
		}

		$scope.deleteBow = function(bow) {
			ConfirmService.confirm({
				head: "Delete Bow",
				body: "Are you sure you want to permanently delete " + bow.Name + "?"
			}, function() {
				return AccentBowsService.delete(bow.Id)
					.success(function() {
						AlertsService.add('success', 'Successfully deleted accent bow.');
					}).error(function(err) {
						console.log(err);
						AlertsService.add('danger', 'Error while deleting accent bow.');
					}).finally(function() {
						$scope.updateItems();
					});
			});
		}
	})

	.controller('imageAccentbowController', function($scope, $modalInstance, AlertsService, AccentBowsService, promiseTracker, id) {
		$scope.tracker = promiseTracker();
		$scope.id = id;
		$scope.cancel = function() {
			$modalInstance.dismiss();
		}

		$scope.uploadFile = function(files) {
			var defered = $scope.tracker.createPromise();
			AccentBowsService.image.upload(id, files)
				.success(function(data) {
					AlertsService.add('success', 'Successfully added image.');
				}).error(function(data) {
					AlertsService.add('danger', 'Something went wrong. Please try again.');
					console.log(data);
				}).finally(function() {
					$modalInstance.close();
					defered.resolve();
				});
		}
	})

	.controller('editBowController', function($scope, $modalInstance, promiseTracker, bow, save, AlertsService) {
		$scope.bow = bow;
		$scope.invalid = {};
		$scope.tracker = promiseTracker();

		$scope.save = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				save($scope.bow)
					.success(function(data) {
						$scope.bow = {};
						AlertsService.add('success', 'Successfully saved accent bowbow.');
						$modalInstance.close();
					}).error(function(data) {
						console.log('Error: ' + data);
						AlertsService.add('warning', 'An error occured while saving the accent bow.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$scope.bow = {};
			$modalInstance.dismiss();
		}
	});
