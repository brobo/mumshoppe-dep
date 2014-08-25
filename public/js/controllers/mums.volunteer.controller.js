angular.module('mums.volunteer.controller', [])
	.controller('mumsController', function() {

	})

	.controller('mumsAllController', function($scope, $q, MumService, SearchService) {

		$scope.mums = SearchService.mums;
		$scope.criteria = SearchService.criteria;
		$scope.toggle = function() {
			SearchService.canvas.toggle()
				.then(function() {
					return SearchService.search();
				})
				.then(function(response) {
					$scope.mums = response.data;
				});
		}

		SearchService.search()
			.success(function(data) {
				$scope.mums = data;
			});

	})

	.controller('searchMenuController', function($scope, SearchService) {
		var year = new Date().getFullYear();

		$scope.criteria = SearchService.criteria;
		$scope.search = function() {
			return SearchService.canvas.toggle();
		}

		$scope.years = [{ label: "Unordered", value: "" }];
		for (var y=2014; y<=year; y++) {
			$scope.years.unshift({ label: y, value: '' + y });
		}
	})

	.factory('SearchService', function(cnOffCanvas, MumService) {
		var service;
		return service = {
			criteria: {
				Year: '2014',
			},
			search: function() {
				service.criteria.Ordered = !!service.criteria.Year;
				service.criteria.Unordered = !service.criteria.Year;
				return MumService.get(service.criteria);
			},
			canvas: cnOffCanvas({
				controller: 'searchMenuController',
				templateUrl: 'public/views/volunteer/mums/searchmenu.html'
			})
		};
	});