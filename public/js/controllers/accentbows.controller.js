
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('accentbows.controller', [])
	.controller('accentbowsController', function($scope, $modal, promiseTracker, AccentBowsService, AlertsService, ConfirmService, MumtypesService) {
		
		$scope.updateItems = function() {
			MumtypesService.grades.get().success(function(data) {		
				$scope.grades = data;
			});
			AccentBowsService.get().success(function(data) {
				$scope.bows = data;
				for (var i=0; i<$scope.bows.length; i++) {
					$scope.bows[i].image = getRoute('/api/accentbow/' + $scope.bows[i].Id + '/image');
				}
			});
		}

		$scope.updateItems();

		$scope.imageBow = function(bow) {
			$modal.open({
				templateUrl: 'imageForm',
				controller: 'imageAccentbowController',
				size: 'lg',
				resolve: {
					bow: function() {
						return bow;
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

	.controller('imageAccentbowController', function($scope, $modalInstance, AlertsService, AccentBowsService, promiseTracker, bow) {
		$scope.tracker = promiseTracker();
		$scope.bow = bow;
		$scope.cancel = function() {
			$modalInstance.dismiss();
		}

		$scope.uploadFile = function(files) {
			var defered = $scope.tracker.createPromise();
			AccentBowsService.image.upload(bow.Id, files)
				.success(function(data) {
					console.log(data);
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
