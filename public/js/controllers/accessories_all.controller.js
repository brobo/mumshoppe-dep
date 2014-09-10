
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('accessoriesAll.controller', [])
	.controller('accessoriesAllController', function($scope, $state, $http, $modal, AlertsService, ConfirmService, AccessoriesService) {
		
		function updateAccessoryList() {
			AccessoriesService.get().success(function(data) {
				$scope.accessories = data;
				for (var i=0; i<$scope.accessories.length; i++) {
					$scope.accessories[i].image = getRoute('/api/accessory/' + $scope.accessories[i].Id + '/image');
				}
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

		$scope.imageAccessory = function(image) {
			$modal.open({
				templateUrl: 'imageForm',
				controller: 'imageAccessoryController',
				size: 'lg',
				resolve: {
					image: function() {
						return image;
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

	.controller('imageAccessoryController', function($scope, $modalInstance, AlertsService, AccessoriesService, promiseTracker, image) {
		$scope.tracker = promiseTracker();
		$scope.image = image;
		$scope.cancel = function() {
			$modalInstance.dismiss();
		}

		$scope.uploadFile = function(files) {
			var defered = $scope.tracker.createPromise();
			AccessoriesService.image.upload(image.Id, files)
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
