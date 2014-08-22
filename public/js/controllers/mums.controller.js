angular.module('mums.controller', [])
	.controller('mumsController', function($scope, $state, $http, $cookieStore, AlertsService, ConfirmService, MumService) {

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
					$state.go('create.start', {mumId: data.Mum.Id});
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

	})

	.controller('mumsViewController', function($scope, $state, $stateParams, MumService, LettersService) {
		var updateMum = function() {
			return MumService.fetch($stateParams.mumId)
				.success(function(data) {
					$scope.mum = data;
				});
		}

		$scope.letters = {};
		$scope.bearTotal = 0;
		$scope.trinketTotal = 0;
		updateMum()
			.success(function() {
				for (var i=0; i<$scope.mum.Bears.length; i++) {
					$scope.bearTotal += parseFloat($scope.mum.Bears[i].Price);
				}
				for (var i=0; i<$scope.mum.Trinkets.length; i++) {
					$scope.trinketTotal += parseFloat($scope.mum.Trinkets[i].Trinket.Price * $scope.mum.Trinkets[i].Quantity);
				}
			});
		LettersService.get()
			.success(function(data) {
				for (var i=0; i<data.length; i++) {
					$scope.letters[data[i].Id] = data[i];
				}
			});
		$scope.$parent.next = function() {
			AlertsService.add('info', 'There isn\'t actually a checkout page yet. Sorry.');
		}

		$scope.$parent.back = function() {
			$state.go('^.trinkets')
		}
	});
