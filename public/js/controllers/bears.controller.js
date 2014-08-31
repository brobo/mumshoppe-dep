angular.module('bears.controller', [])
	.controller('bearsController', function($scope, $modal, promiseTracker, BearsService, AlertsService, ConfirmService) {
		
		$scope.updateBears = function() {
			BearsService.get().success(function(data) {		
				$scope.bears = data;
			});
		}

		$scope.updateBears();

		$scope.imageBear = function(id) {
			$modal.open({
				templateUrl: 'imageForm',
				controller: 'imageBearController',
				size: 'lg',
				resolve: {
					id: function() {
						return id;
					}
				}
			});
		}

		$scope.addBear = function(grade) {
			$modal.open({
				templateUrl: 'bearForm',
				controller: 'editBearController',
				size: 'lg',
				resolve: {
					bear: function() {
						return {};
					},
					save: function() {
						return BearsService.create;
					}
				}
			}).result.then(function() {
				$scope.updateBears();
			});
		}

		$scope.editBear = function(bear) {
			$modal.open({
				templateUrl: 'bearForm',
				controller: 'editBearController',
				size: 'lg',
				resolve: {
					bear: function() {
						return angular.copy(bear);
					},
					save: function() {
						return function(bear) {
							return BearsService.update(bear.Id, bear);
						};
					}
				}
			}).result.then(function() {
				$scope.updateBears();
			});
		}

		$scope.deleteBear = function(bear) {
			ConfirmService.confirm({
				head: "Delete Bear",
				body: "Are you sure you want to permanently delete " + bear.Name + "?"
			}, function() {
				return BearsService.delete(bear.Id)
					.success(function() {
						AlertsService.add('success', 'Successfully deleted bear.');
					}).error(function(err) {
						console.log(err);
						AlertsService.add('danger', 'Error while deleting bear.');
					}).finally(function() {
						$scope.updateBears();
					});
			});
		}
	})

	.controller('imageBearController', function($scope, $modalInstance, AlertsService, BearsService, promiseTracker, id) {
		$scope.tracker = promiseTracker();
		$scope.id = id;
		$scope.cancel = function() {
			$modalInstance.dismiss();
		}

		$scope.uploadFile = function(files) {
			var defered = $scope.tracker.createPromise();
			BearsService.image.upload(id, files)
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

	.controller('editBearController', function($scope, $modalInstance, promiseTracker, bear, save, AlertsService) {
		$scope.REGEX_PRICE = /^[0-9]*(\.[0-9]{1,2})?$/
		$scope.bear = bear;
		$scope.invalid = {};
		$scope.tracker = promiseTracker();

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		};

		$scope.save = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				save($scope.bear)
					.success(function(data) {
						$scope.bear = {};
						AlertsService.add('success', 'Successfully saved bear.');
						$modalInstance.close();
					}).error(function(data) {
						console.log('Error: ' + data);
						AlertsService.add('warning', 'An error occured while saving the bear.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$scope.bear = {};
			$modalInstance.dismiss();
		}
	});
