
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('create.controller', [])
	.controller('createController', function($scope, $state, $cookieStore, $stateParams, promiseTracker, MumService) {

		$scope.updateMum = function() {
			return MumService.fetch($stateParams.mumId)
				.success(function(data) {
					$scope.mum = data;
					$scope.priceType = ($scope.mum.Product.Name == 'Mum') ? 'MumPrice' : 'GarterPrice';
					$scope.stagedCharges = [];
				});
		}

		$scope.getStarted = function() {
			$state.go('^.base.product');
		}

		$scope.customer = $cookieStore.get('customer');
		$scope.tracker = promiseTracker();

	})

	.controller('createThankYouController', function($scope, $state) {
		$scope.$parent.next = function() {
			$state.go('mums.all');
		}
	})

	.controller('createFinalizeController', function($scope, $state, $stateParams, $location, promiseTracker, AlertsService, PayService) {
		$scope.tracker = promiseTracker();
		$scope.redirecting = false;
		$scope.finalize = function() {
			var defered = $scope.tracker.createPromise();
			PayService.deposite.finalize($stateParams.mumId, $location.search().PayerID)
				.success(function(data) {
					console.log(data);
					if (data.success)
						$state.go('^.thankyou');
					else
						AlertsService.add('warning', 'The payment was not approved. Try again or visit the Mum Shoppe.');
				}).error(function(data) {
					console.log(data);
					AlertsService.add('danger', 'An error occured. Please try again.');
				}).finally(function(data) {
					defered.resolve();
				});
		}

		$scope.$parent.back = function() {
			$state.go('^.deposit');
		}
	})

	.controller('createDepositController', function($scope, $state, $stateParams, $window, promiseTracker, AlertsService, PayService) {
		if (!$scope.mum) {
			$scope.updateMum();
		}
		$scope.tracker = promiseTracker();
		$scope.redirecting = false;
		$scope.startPayFlow = function() {
			var defered = $scope.tracker.createPromise();
			PayService.deposite.startPayFlow($stateParams.mumId)
				.success(function(data) {
					if (data.location) {
						$scope.redirecting = true;
						$scope.approvalUrl = data.location;
						$window.location.href = $scope.approvalUrl;
					}
				})
				.error(function() {
					AlertsService.add('danger', 'An error has occured. Please try again.');
				}).finally(function() {
					defered.resolve();
				});
		}

		$scope.$parent.back = function() {
			$state.go('^.review');
		}
	})

	.controller('createReviewController', function($scope, $state, AlertsService, LettersService, MumService, AccessoriesService) {
		$scope.letters = {};
		$scope.bearTotal = 0;
		$scope.accessoryTotal = 0;
		$scope.updateMum()
			.success(function() {
				for (var i=0; i<$scope.mum.Bears.length; i++) {
					$scope.bearTotal += parseFloat($scope.mum.Bears[i].Price);
				}
				for (var i=0; i<$scope.mum.Accessories.length; i++) {
					$scope.accessoryTotal += parseFloat($scope.mum.Accessories[i].Accessory[$scope.priceType] * $scope.mum.Accessories[i].Quantity);
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
			$state.go('^.deposit');
		}

		$scope.$parent.back = function() {
			$state.go('^.accessories')
		}
	})

	.controller('createAccessoriesController', function($scope, $state, $stateParams, AlertsService, AccessoriesService, MumService) {
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
		AccessoriesService.get()
			.success(function(data) {
				$scope.accessories = data;
				for (var i=0; i<$scope.accessories.length; i++) {
					$scope.priceLookup[$scope.accessories[i].Id] = {'MumPrice': $scope.accessories[i].MumPrice, 'GarterPrice': $scope.accessories[i].GarterPrice};
					$scope.accessories[i].image = getRoute('/api/accessory/' + $scope.accessories[i].Id + '/image');
				}
			});
		AccessoriesService.categories.get()
			.success(function(data) {
				$scope.categories = data;
				$scope.categorySelect = $scope.categories[0].Id;
			});
		$scope.updateMum()
			.success(function() {
				for (var i=0; i<$scope.mum.Accessories.length; i++) {
					$scope.quantities[$scope.mum.Accessories[i].AccessoryId] = $scope.mum.Accessories[i].Quantity;	
				}
			});
		$scope.decrement = function(accessory) {
			if ($scope.quantities[accessory.Id])
				$scope.quantities[accessory.Id]--;
			else
				$scope.quantities[accessory.Id] = 0;
			$scope.updateTotal();
		}
		$scope.increment = function(accessory) {
			if ($scope.quantities[accessory.Id])
				$scope.quantities[accessory.Id]++;
			else
				$scope.quantities[accessory.Id] = 1;
			$scope.updateTotal();
		}
		$scope.updateTotal = function() {
			var total = 0;
			for (var key in $scope.quantities) {
				total += $scope.quantities[key] * ($scope.priceLookup[key][$scope.priceType] || 0);
			}
			$scope.totalPrice = total;
			$scope.$parent.staged = $scope.totalPrice;
		}

		$scope.$parent.next = function() {
			var defered = $scope.tracker.createPromise();
			MumService.setAccessories($stateParams.mumId, $scope.quantities)
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

					$scope.predicate = {};
					$scope.predicate[$scope.mum.Grade.Name] = true;
				});
		}

		$scope.updateMumWithBears();

		BearsService.get()
			.success(function(data) {
				$scope.bears = data;
				for (var i=0; i<$scope.bears.length; i++) {
					$scope.bears[i].image = getRoute('/api/bear/' + $scope.bears[i].Id + '/image');
				}
			});

		$scope.$parent.next = function() {
			$state.go('^.accessories');
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
				for (var i=0; i<$scope.accentbows.length; i++) {
					$scope.accentbows[i].image = getRoute('/api/accentbow/' + $scope.accentbows[i].Id + '/image');
				}
			});

		$scope.$parent.back = function() {
			$state.go('create.recipient');
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

	.controller('createRecipientController', function($scope, $state, $stateParams, MumService) {
		$scope.updateMum();

		$scope.$parent.next = function() {
			var defered = $scope.tracker.createPromise();
			MumService.update($stateParams.mumId, {
				'RecipientName': $scope.mum.Mum.RecipientName
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
			$state.go('create.base.product');
		}
	})

	.controller('createProductController', function($scope, $state, $stateParams, MumService, MumtypesService) {
		var back = {
			'product': 'mums.all',
			'grade': '^.product',
			'size': '^.grade',
			'backing': '^.size'
		};
		var forwards = {
			'product': {page: '^.grade', req:'selectedProduct'},
			'grade': {page: '^.size', req:'selectedGrade'},
			'size': {page: '^.backing', req:'selectedSize'}
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
				for (var i=0; i<$scope.sizes.length; i++) {
					$scope.sizes[i].image = getRoute('/api/size/' + $scope.sizes[i].Id + '/image');
				}
			});
		MumtypesService.backings.get()
			.success(function(data) {
				$scope.backings = data;
				for (var i=0; i<$scope.backings.length; i++) {
					$scope.backings[i].image = getRoute('/api/backing/' + $scope.backings[i].Id + '/image');
				}
			});

		$scope.$parent.next = function() {
			for (var key in forwards) {
				if ($state.current.name.indexOf(key) > -1) {
					if (!$scope[forwards[key].req]) {
						return;
					}
					$state.go(forwards[key].page);
					return;
				}
			}

			var defered = $scope.tracker.createPromise();
			MumService.update($stateParams.mumId, {
				BackingId: $scope.selectedBacking.Id
			}).success(function(data) {
				$state.go('create.recipient');
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
