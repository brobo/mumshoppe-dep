angular.module('trinketsAll.controller', [])
	.controller('trinketsAllController', function($scope, $state, $http, AlertsService, ConfirmService, TrinketsService) {
		
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

	});
