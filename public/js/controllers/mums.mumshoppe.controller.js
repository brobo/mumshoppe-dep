
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('mums.mumshoppe.controller', [])
	.controller('mumsController', function($scope, $state, $http, $cookieStore, AlertsService, ConfirmService, MumService) {

		$scope.updateMums = function() {
			MumService.get()
				.success(function(data) {
					$scope.mums = data;
				});
		}

		$scope.updateMums();

		$scope.createMum = function() {
			MumService.create()
				.success(function(data) {
					if (data.success) {
						$state.go('create.start', {mumId: data.mum.Mum.Id});
					} else {
						if (!data.orders) {
							AlertsService.add('warning', 'The mum shoppe is not taking any more orders for this year.');
						} else {
							AlertsService.add('danger', 'Something went wrong - try again.');
						}
					}
					
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'Something went wrong - try again.');
				}).finally(function() {
					$scope.updateMums();
				});
		};

		$scope.deleteMum = function(mumId) {
			ConfirmService.confirm({
				head: "Delete Mum",
				body: "Are you sure you want to permanently delete this beautiful mum?"
			}, function() {
				return MumService.delete(mumId)
					.success(function(data) {
						console.log(data);
						AlertsService.add('success', 'Successfully deleted mum.');
					}).error(function(err) {
						console.log(err);
						AlertsService.add('danger', 'Error while deleting mum.');
					}).finally(function(data) {
						$scope.updateMums();
					});
			});
		}

		$scope.payMum = function(mumId) {
			$state.go('pay.start', {mumId: mumId});
		}

	})

	.controller('mumsViewController', function($scope, $state, $stateParams, MumService, LettersService, AccessoriesService) {
		var updateMum = function() {
			return MumService.fetch($stateParams.mumId)
				.success(function(data) {
					$scope.mum = data;
				});
		}

		$scope.letters = {};
		$scope.bearTotal = 0;
		$scope.accessoryTotal = 0;
		updateMum()
			.success(function() {
				for (var i=0; i<$scope.mum.Bears.length; i++) {
					$scope.bearTotal += parseFloat($scope.mum.Bears[i].Price);
				}
				for (var i=0; i<$scope.mum.Accessories.length; i++) {
					$scope.accessoryTotal += parseFloat($scope.mum.Accessories[i].Accessory.Price * $scope.mum.Accessories[i].Quantity);
				}
			});
		LettersService.get()
			.success(function(data) {
				for (var i=0; i<data.length; i++) {
					$scope.letters[data[i].Id] = data[i];
				}
			});
		AccessoriesService.categories.get()
			.success(function(data) {
				$scope.categories = data;
			});
		$scope.$parent.next = function() {
			AlertsService.add('info', 'There isn\'t actually a checkout page yet. Sorry.');
		}

		$scope.$parent.back = function() {
			$state.go('^.accessories')
		}
	});
