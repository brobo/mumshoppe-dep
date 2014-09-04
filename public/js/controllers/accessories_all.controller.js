angular.module('accessoriesAll.controller', [])
	.controller('accessoriesAllController', function($scope, $state, $http, $modal, AlertsService, ConfirmService, AccessoriesService) {
		
		function updateAccessoryList() {
			AccessoriesService.get().success(function(data) {
				$scope.accessories = data;
			});
		}

		AccessoriesService.categories.get()
			.success(function(data) {
				$scope.categories = data;
				$scope.categorySelect = $scope.categories[0].Id;
			});
		
		updateAccessoryList();

		$scope.addAccessory = function() {
			$state.go('^.add');
		}

		$scope.editAccessory = function(id) {
			$state.go('^.edit', { accessoryId: id});
		}

		$scope.imageAccessory = function(id) {
			$modal.open({
				templateUrl: 'imageForm',
				controller: 'imageAccessoryController',
				size: 'lg',
				resolve: {
					id: function() {
						return id;
					}
				}
			});
		}

		$scope.deleteAccessory = function(accessory) {
			ConfirmService.confirm({
				head: "Delete accessory",
				body: "Are you sure you want to permanetly delete " + accessory.Name + "!?"
			}, function() {
				return AccessoriesService.delete(accessory.Id)
					.success(function() {
						AlertsService.add('success', 'Successfully deleted accessory.');

						updateAccessoryList();

					})
					.error(function(err) {
						console.log(err);
						AlertsService.add('danger', 'Error while deleting accessory. Try again.');
					})
			});
		}

	})

	.controller('imageAccessoryController', function($scope, $modalInstance, AlertsService, AccessoriesService, promiseTracker, id) {
		$scope.tracker = promiseTracker();
		$scope.id = id;
		$scope.cancel = function() {
			$modalInstance.dismiss();
		}

		$scope.uploadFile = function(files) {
			var defered = $scope.tracker.createPromise();
			AccessoriesService.image.upload(id, files)
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
	});
