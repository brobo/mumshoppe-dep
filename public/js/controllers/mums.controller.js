angular.module('mums.controller', [])
	.controller('mumsController', function($scope, $http, $cookieStore, AlertsService, ConfirmService, MumService) {

		$scope.updateMums = function() {
			MumService.get()
				.success(function(data) {
					$scope.mums = data;
				});
		}

		$scope.updateMums();

		$scope.createMum = function() {
			MumService.create($cookieStore.get('customer').Id)
				.success(function(data) {
					//$scope.go('^.create', data.Id);
				}).error(function(data) {
					console.log(data);
					AlertsService.alert('danger', 'Something went wrong - try again.');
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

	});