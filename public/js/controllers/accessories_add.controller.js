
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('accessoriesAdd.controller', [])
	.controller('accessoriesAddController', function($scope, $state, $modal, promiseTracker, AccessoriesService, AlertsService) {

		$scope.REGEX_PRICE = /^[0-9]*(\.[0-9]{1,2})?$/

		$scope.tracker = promiseTracker();

		$scope.invalid = {};
		$scope.accessory = {};
		$scope.categoryIds = {};
		$scope.categoryNames = {};

		$scope.updateCategories = function() {
			AccessoriesService.categories.get()
				.success(function(data) {
					$scope.categories = data;
				});
		}
		$scope.updateCategories();

		$scope.validate = function(field) {
			$scope.invalid[field] = $scope.accessoryForm[field].$invalid;
		};

		$scope.classSelected = function() {
			return $scope.accessory.Underclassman || $scope.accessory.Junior || $scope.accessory.Senior;
		}

		$scope.cancel = function() {
			$scope.student = {};
			$state.go('^.all');
		}

		$scope.save = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				AccessoriesService.create($scope.accessory)
					.success(function(data) {
						console.log(data);
						$scope.accessory = {};
						AlertsService.add('success', 'Successfully created accessory.');
						$state.go('^.all');
					}).error(function(data) {
						console.log(data);
						AlertsService.add('danger', 'An error occured while trying to create the accessory. Try again.');
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.addCategory = function() {
			$modal.open({
				templateUrl: 'categoryForm',
				controller: 'categoryController',
				size: 'lg',
			}).result.then(function() {
				$scope.updateCategories();
			});
		}

	})

	.controller('categoryController', function($scope, AccessoriesService, promiseTracker, $modalInstance) {
		$scope.tracker = promiseTracker();
		$scope.category = {};

		$scope.saveCategory = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				AccessoriesService.categories.create($scope.category)
					.success(function(data) {
						$modalInstance.close();
					}).error(function(data) {
						AlertsService.add('danger', 'An error occured. Please try again.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$modalInstance.dismiss();
		}
	});
