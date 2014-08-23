angular.module('mums.volunteer.controller', [])
	.controller('mumsController', function() {

	})

	.controller('mumsAllController', function($scope, MumService) {
		MumService.get()
			.success(function(data) {
				$scope.mums = data;
			})
	});