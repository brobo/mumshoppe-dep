angular.module('create.controller', [])
	.controller('createController', function($scope, $state, $cookieStore, $stateParams, promiseTracker, MumService) {

		$scope.updateMum = function() {
			return MumService.fetch($stateParams.mumId)
				.success(function(data) {
					$scope.mum = data;
					$scope.stagedCharges = [];
				});
		}

		$scope.getStarted = function() {
			$state.go('^.base.product');
		}

		$scope.customer = $cookieStore.get('customer');
		$scope.tracker = promiseTracker();

	})

	.controller('createReview', function($scope, $state, AlertsService, LettersService, MumService) {
		$scope.letters = {};
		$scope.bearTotal = 0;
		$scope.trinketTotal = 0;
		$scope.updateMum()
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
	})

	.controller('createTrinketsController', function($scope, $state, $stateParams, AlertsService, TrinketsService, MumService) {
		$scope.quantities = {};
		$scope.priceLookup = {};
		$scope.updateMum()
			.success(function() {
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
		$scope.updateMum()
			.success(function() {
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
			$scope.$parent.staged = $scope.totalPrice;
		}

		$scope.$parent.next = function() {
			var defered = $scope.tracker.createPromise();
			MumService.setTrinkets($stateParams.mumId, $scope.quantities)
				.success(function(data) {
					console.log(data);
					$state.go('^.review');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'An error occured. Please try again.');
				}).finally(function() {
					defered.resolve();
				});
		}

		$scope.$parent.back = function() {
			$state.go('^.bears');
		}
	})

	.controller('createBearsController', function($scope, $state, $stateParams, $filter, promiseTracker, AlertsService, BearsService, MumService) {
		$scope.updateMumWithBears = function() {
			return $scope.updateMum()
				.success(function() {
					var total = 0;
					for (var i=0; i<$scope.mum.Bears.length; i++) {
						total += parseFloat($scope.mum.Bears[i].Price);
					}
					$scope.totalPrice = total;

					$scope.predicate = $scope.mum.Grade.Name == 'Senior' ? {} : {Senior: false};
				});
		}

		$scope.updateMumWithBears();

		BearsService.get()
			.success(function(data) {
				$scope.bears = data;
			});

		$scope.$parent.next = function() {
			$state.go('^.trinkets');
		}

		$scope.$parent.back = function() {
			$state.go('^.nameribbons')
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
					$scope.updateMumWithBears();
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
					$scope.updateMumWithBears();
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'An error occured. Please try again.');
				}).finally(function() {
					defered.resolve();
				});
		}
	})

	.controller('createNameRibbonController', function($scope, $state, $stateParams, AlertsService, LettersService, MumService) {
		$scope.hasRibbonOne = true;
		$scope.REGEX_ALPHABETIC = /^[a-zA-Z ]*$/
		$scope.letterLookup = {};

		LettersService.get()
			.success(function(data) {
				$scope.letters = data;
				$scope.letterOneId = $scope.letterOneId || $scope.letters[0].Id;
				$scope.letterTwoId = $scope.letterTwoId || $scope.letters[0].Id;
				for (var i=0; i<$scope.letters.length; i++) {
					$scope.letterLookup[$scope.letters[i].Id] = $scope.letters[i];
				}
			});

		$scope.updateMum()
			.success(function() {
				$scope.letterOneId = $scope.mum.Mum.Letter1Id || $scope.letterOneId;
				$scope.nameOne = $scope.mum.Mum.NameRibbon1;
				$scope.letterTwoId = $scope.mum.Mum.Letter2Id || $scope.letterTwoId;
				$scope.nameTwo = $scope.mum.Mum.NameRibbon2;

				//Double bang for truthy to boolean conversion
				$scope.hasRibbonOne = !!($scope.letterOneId && $scope.nameOne);
				$scope.hasRibbonTwo = !!($scope.letterTwoId && $scope.nameTwo);
			})

		$scope.enforceNoRibbon =function() {
			if (!$scope.hasRibbonOne) {
				$scope.hasRibbonTwo = false;
			}
		}

		$scope.$parent.next = function() {
			var data = {};
			if ($scope.hasRibbonOne) {
				data.Letter1Id = $scope.letterOneId;
				data.NameRibbon1 = $scope.nameOne;
			} else {
				data.Letter1Id = 0;
				data.NameRibbon1 = "";
			}
			if ($scope.hasRibbonTwo) {
				data.Letter2Id = $scope.letterTwoId;
				data.NameRibbon2 = $scope.nameTwo;
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

		$scope.$parent.back = function() {
			$state.go('^.accentbow');
		}

	})

	.controller('createAccentBowController', function($scope, $state, $stateParams, MumService, AccentBowsService) {

		$scope.updateMum()
			.success(function() {
				$scope.accentBowId = $scope.mum.Mum.AccentBowId;
			});

		AccentBowsService.get()
			.success(function(data) {
				$scope.accentbows = data;
			});

		$scope.$parent.back = function() {
			$state.go('create.base.product');
		}

		$scope.$parent.next = function() {
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

	.controller('createProductController', function($scope, $state, $stateParams, MumService, MumtypesService) {
		var back = {
			'grade': '^.product',
			'size': '^.grade',
			'backing': '^.size'
		};
		var forwards = {
			'product': '^.grade',
			'grade': '^.size',
			'size': '^.backing'
		};

		$scope.selectedParent = {};

		$scope.updateMum()
			.success(function() {
				$scope.selectedProduct = $scope.mum.Product;
				$scope.selectedGrade = $scope.mum.Grade;
				$scope.selectedSize = $scope.mum.Size;
				$scope.selectedBacking = $scope.mum.Backing;
			});

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

		$scope.$parent.next = function() {
			for (var key in forwards) {
				if ($state.current.name.indexOf(key) > -1) {
					$state.go(forwards[key]);
					return;
				}
			}

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
		$scope.$parent.back = function() {
			for (var key in back) {
				if ($state.current.name.indexOf(key) > -1) {
					$state.go(back[key]);
					return;
				}
			}
		}
	});
