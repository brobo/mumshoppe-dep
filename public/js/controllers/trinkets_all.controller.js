angular.module('trinketsAll.controller', [])
	.controller('trinketsAllController', function($scope, $state, $http, $modal, AlertsService, ConfirmService, TrinketsService) {
		
		function updateTrinketList() {
			TrinketsService.get().success(function(data) {
				$scope.trinkets = data;
			});
		}
		
		updateTrinketList();

		$scope.addTrinket = function() {
			$state.go('^.add');
		}

		$scope.editTrinket = function(id) {
			$state.go('^.edit', { trinketId: id});
		}

		$scope.imageTrinket = function(id) {
			$modal.open({
				templateUrl: 'imageForm',
				controller: 'imageTrinketController',
				size: 'lg',
				resolve: {
					id: function() {
						return id;
					}
				}
			});
		}

		$scope.deleteTrinket = function(trinket) {
			ConfirmService.confirm({
				head: "Delete trinket",
				body: "Are you sure you want to permanetly delete " + trinket.Name + "!?"
			}, function() {
				return TrinketsService.delete(trinket.Id)
					.success(function() {
						AlertsService.add('success', 'Successfully deleted trinket.');

						updateTrinketList();

					})
					.error(function(err) {
						console.log(err);
						AlertsService.add('danger', 'Error while deleting trinket. Try again.');
					})
			});
		}

	})

	.controller('imageTrinketController', function($scope, $modalInstance, AlertsService, TrinketsService, promiseTracker, id) {
		$scope.tracker = promiseTracker();
		$scope.id = id;
		$scope.cancel = function() {
			$modalInstance.dismiss();
		}

		$scope.uploadFile = function(files) {
			var defered = $scope.tracker.createPromise();
			TrinketsService.image.upload(id, files)
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
