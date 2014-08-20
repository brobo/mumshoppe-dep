angular.module('create.controller', [])
	.controller('createController', function($scope, $state, $stateParams, MumService) {

		$scope.updateMum = function() {
			MumService.fetch($stateParams.mumId)
				.success(function(data) {
					$scope.mum = data;
				});
		}

		$scope.getStarted = function() {
			$state.go('^.base.product');
		}

	})

	.controller('createReview', function($scope, $stateParams, AlertsService, LettersService, MumService) {
		$scope.letters = {};
		$scope.bearTotal = 0;
		$scope.trinketTotal = 0;
		MumService.fetch($stateParams.mumId)
			.success(function(data) {
				$scope.mum = data;
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
		$scope.next = function() {
			AlertsService.add('info', 'There isn\'t actually a checkout page yet. Sorry.');
		}
	})

	.controller('createTrinketsController', function($scope, $state, $stateParams, promiseTracker, AlertsService, TrinketsService, MumService) {
		$scope.quantities = {};
		$scope.priceLookup = {};
		$scope.tracker = promiseTracker();
		MumService.fetch($stateParams.mumId)
			.success(function(data) {
				$scope.mum = data;
				switch ($scope.mum.Grade.Name) {
					case "Underclassman":
						$scope.gradePredicate = {Underclassman: true};
						break;
					case "Junior":
						$scope.gradePredicate = {Junior: true};
						break;
					case "Senior":
						$scope.gradePredicate = {Senior: true};
						break;
				}
			});
		TrinketsService.get()
			.success(function(data) {
				$scope.trinkets = data;
				for (var i=0; i<$scope.trinkets.length; i++) {
					$scope.priceLookup[$scope.trinkets[i].Id] = $scope.trinkets[i].Price;
				}
			});
		TrinketsService.categories.get()
			.success(function(data) {
				$scope.categories = data;
				$scope.categorySelect = $scope.categories[0].Id;
			});
		MumService.fetch($stateParams.mumId)
			.success(function(data) {
				$scope.mum = data;
				for (var i=0; i<$scope.mum.Trinkets.length; i++) {
					$scope.quantities[$scope.mum.Trinkets[i].TrinketId] = $scope.mum.Trinkets[i].Quantity;	
				}
			});
		$scope.decrement = function(trinket) {
			if ($scope.quantities[trinket.Id])
				$scope.quantities[trinket.Id]--;
			else
				$scope.quantities[trinket.Id] = 0;
			$scope.updateTotal();
		}
		$scope.increment = function(trinket) {
			if ($scope.quantities[trinket.Id])
				$scope.quantities[trinket.Id]++;
			else
				$scope.quantities[trinket.Id] = 1;
			$scope.updateTotal();
		}
		$scope.updateTotal = function() {
			var total = 0;
			for (var key in $scope.quantities) {
				total += $scope.quantities[key] * ($scope.priceLookup[key] || 0);
			}
			$scope.totalPrice = total;
		}

		$scope.tracker = promiseTracker();

		$scope.next = function() {
			var defered = $scope.tracker.createPromise();
			MumService.setTrinkets($stateParams.mumId, $scope.quantities)
				.success(function(data) {
					console.log(data);
					AlertsService.add('success', 'Successfully saved mum information.');
					$state.go('^.review');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'An error occured. Please try again.');
				}).finally(function() {
					defered.resolve();
				});
		}
	})

	.controller('createBearsController', function($scope, $state, $stateParams, $filter, promiseTracker, AlertsService, BearsService, MumService) {
		$scope.updateMum = function() {
			MumService.fetch($stateParams.mumId)
				.success(function(data) {
					$scope.mum = data;

					var total = 0;
					for (var i=0; i<$scope.mum.Bears.length; i++) {
						total += parseFloat($scope.mum.Bears[i].Price);
					}
					$scope.totalPrice = total;

					$scope.predicate = $scope.mum.Grade.Name == 'Senior' ? {} : {Senior: false};
				});
		}
		$scope.updateMum();

		BearsService.get()
			.success(function(data) {
				$scope.bears = data;
			});

		$scope.next = function() {
			$state.go('^.trinkets');
		}

		$scope.hasBear = function(bear) {
			if (!$scope.mum) return false;
			for (var i=0; i<$scope.mum.Bears.length; i++) {
				if (bear.Id === $scope.mum.Bears[i].Id) return true;
			}
			return false;
		}

		$scope.addBear = function(bear) {
			if (!bear.tracker) bear.tracker = promiseTracker();
			var defered = bear.tracker.createPromise();
			MumService.addBear($stateParams.mumId, bear.Id)
				.success(function(data) {
					$scope.updateMum();
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'An error occured. Please try again.');
				}).finally(function() {
					defered.resolve();
				});
		}

		$scope.removeBear = function(bear) {
			if (!bear.tracker) bear.tracker = promiseTracker();
			var defered = bear.tracker.createPromise();
			MumService.removeBear($stateParams.mumId, bear.Id)
				.success(function(data) {
					$scope.updateMum();
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'An error occured. Please try again.');
				}).finally(function() {
					defered.resolve();
				});
		}
	})

	.controller('createNameRibbonController', function($scope, $state, $stateParams, promiseTracker, AlertsService, LettersService, MumService) {
		$scope.hasRibbonOne = true;
		$scope.REGEX_ALPHABETIC = /^[a-zA-Z ]*$/
		$scope.tracker = promiseTracker();

		LettersService.get()
			.success(function(data) {
				$scope.letters = data;
				$scope.letterOne = $scope.letters[0];
				$scope.letterTwo = $scope.letters[0];
			});

		$scope.enforceNoRibbon =function() {
			if (!$scope.hasRibbonOne) {
				$scope.hasRibbonTwo = false;
			}
		}

		$scope.next = function() {
			var data = {};
			if ($scope.hasRibbonOne) {
				data.Letter1Id = $scope.letterOne.Id;
				data.NameRibbon1 = $scope.nameOne;
			} else {
				data.Letter1Id = 0;
				data.NameRibbon1 = "";
			}
			if ($scope.hasRibbonTwo) {
				data.Letter2Id = $scope.letterTwo.Id;
				data.NameRibbon2 = $scope.NameTwo;
			} else {
				data.Letter2Id = 0;
				data.NameRibbon2 = "";
			}
			
			var defered = $scope.tracker.createPromise();

			MumService.update($stateParams.mumId, data)
				.success(function(data) {
					$state.go('^.bears');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'An error occured. Please try again.');
				}).finally(function(data) {
					defered.resolve();
				});
		}
	})

	.controller('createAccentBowController', function($scope, $state, $stateParams, MumService, AccentBowsService, promiseTracker) {
		$scope.tracker = promiseTracker();
		$scope.updateMum();

		AccentBowsService.get()
			.success(function(data) {
				$scope.accentbows = data;
			});

		$scope.back = function() {
			$state.go('create.base.product');
		}

		$scope.next = function() {
			var defered = $scope.tracker.createPromise();
			MumService.update($stateParams.mumId, {
				AccentBowId: $scope.accentBowId
			}).success(function(data) {
				$state.go('create.nameribbons');
			}).error(function(data) {
				AlertsService.add('danger', 'An error occured. Please try again.');
				console.log(data);
			}).finally(function() {
				defered.resolve();
			});
		}
	})

	.controller('createProductController', function($scope, $state, $stateParams, MumService, MumtypesService, promiseTracker) {
		var back = {
			'grade': '^.product',
			'size': '^.grade',
			'backing': '^.size'
		};
		var forwards = {
			'product': '^.grade',
			'grade': '^.size',
			'size': '^.backing',
			'backing': 'create.accentbow'
		};
		$scope.tracker = promiseTracker();

		$scope.selectedParent = {};

		MumtypesService.grades.get()
			.success(function(data) {
				$scope.grades = data;
			});

		MumtypesService.products.get()
			.success(function(data) {
				$scope.products = data;
			});
		MumtypesService.sizes.get()
			.success(function(data) {
				$scope.sizes = data;
			});
		MumtypesService.backings.get()
			.success(function(data) {
				$scope.backings = data;
			});

		$scope.save = function() {
			var defered = $scope.tracker.createPromise();
			MumService.update($stateParams.mumId, {
				BackingId: $scope.selectedBacking.Id
			}).success(function(data) {
				$state.go('create.accentbow');
			}).error(function(data) {
				AlertsService.add('danger', 'An error occured. Please try again.');
				console.log(data);
			}).finally(function() {
				defered.resolve();
			});
		}
		$scope.next = function() {
			for (var key in forwards) {
				if ($state.current.name.indexOf(key) > -1) {
					$state.go(forwards[key]);
					return;
				}
			}
		}
		$scope.back = function() {
			for (var key in back) {
				if ($state.current.name.indexOf(key) > -1) {
					$state.go(back[key]);
					return;
				}
			}
		}
	});
