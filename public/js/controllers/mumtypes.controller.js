angular.module('mumtypes.controller', [])
	.controller('mumtypesController', function($scope, $state) {
		
	})

	.controller('mumtypesItemsController', function($scope, $state, $modal, itemDetails, AlertsService, ConfirmService) {
		$scope.updateItems = function() {
			itemDetails.service.get()
				.success(function(data) {
					$scope.items = data;
				});
		}
		$scope.updateItems();

		for (var i=0; i<itemDetails.fetch.length; i++) {
			itemDetails.fetch[i]($scope);
		}

		$scope.addItem = function(meta) {
			$modal.open({
				templateUrl: 'itemForm',
				controller: itemDetails.formController,
				size: 'lg',
				resolve: {
					item: function() {
						return {};
					},
					save: function() {
						return function(data) {
							return itemDetails.service.create(data);
						}
					},
					meta: function() {
						return meta;
					}
				}
			}).result.then(function() {
				$scope.updateItems();
			});
		}

		$scope.editItem = function(item, meta) {
			$modal.open({
				templateUrl: 'itemForm',
				controller: itemDetails.formController,
				size: 'lg',
				resolve: {
					item: function() {
						return angular.copy(item);
					},
					save: function() {
						return function(data) {
							return itemDetails.service.update(item.Id, data);
						}
					},
					meta: function() {
						return meta;
					}
				}
			}).result.then(function() {
				$scope.updateItems();
			});
		}

		$scope.deleteItem = function(item) {
			ConfirmService.confirm({
				head: "Delete Item",
				body: "Are you sure you want to permanently delete " + item.Name + "?"
			}, function() {
				return itemDetails.service.delete(item.Id)
					.success(function() {
						AlertsService.add('success', 'Deleted successfully.');
					}).error(function(err) {
						console.log(err);
						AlertsService.add('danger', 'Error while deleting.');
					}).finally(function() {
						$scope.updateItems();
					});
			});
		}
	})

	.controller('mumtypesEditGradeController', function($scope, $state, $modalInstance, promiseTracker, AlertsService, MumtypesService, item, save) {
		$scope.grade = item;
		$scope.tracker = promiseTracker();
		$scope.invalid = {};

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		}

		$scope.create = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				save($scope.grade)
					.success(function(data) {
						$scope.grade = {};
						AlertsService.add('success', 'Successfully saved grade.');
						$modalInstance.close();
					}).error(function(data) {
						console.log('Error: ' + data);
						AlertsService.add('warning', 'An error occured while saving the grade.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$scope.grade = {};
			$modalInstance.dismiss();
		}
	})

	.controller('mumtypesEditProductController', function($scope, $state, $modalInstance, promiseTracker, AlertsService, MumtypesService, item, save) {
		$scope.product = item;
		$scope.tracker = promiseTracker();
		$scope.invalid = {};

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		}

		$scope.create = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				save($scope.product)
					.success(function(data) {
						$scope.grade = {};
						AlertsService.add('success', 'Successfully saved product.');
						$modalInstance.close();
					}).error(function(data) {
						console.log('Error: ' + data);
						AlertsService.add('warning', 'An error occured while saving the product.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$scope.product = {};
			$modalInstance.dismiss();
		}
	})

	.controller('mumtypesEditSizeController', function($scope, $state, $modalInstance, promiseTracker, AlertsService, MumtypesService, meta, item, save) {
		$scope.size = item;
		$scope.size.ProductId = meta.ProductId;
		$scope.tracker = promiseTracker();
		$scope.invalid = {};

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		}

		$scope.imageSize = function(id) {
			$modal.open({
				templateUrl: 'imageForm',
				controller: 'imageSizeController',
				size: 'lg',
				resolve: {
					id: function() {
						return id;
					}
				}
			});
		}

		$scope.create = function(form) {
			if (form.$valid) {
				console.log($scope.size);
				var defered = $scope.tracker.createPromise();
				save($scope.size)
					.success(function(data) {
						$scope.size = {};
						AlertsService.add('success', 'Successfully saved size.');
						$modalInstance.close();
					}).error(function(data) {
						console.log('Error: ' + data);
						AlertsService.add('warning', 'An error occured while saving the size.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$scope.size = {};
			$modalInstance.dismiss();
		}
	})

	.controller('mumtypesEditBackingController', function($scope, $state, $modalInstance, promiseTracker, AlertsService, MumtypesService, meta, item, save) {
		$scope.REGEX_PRICE = /^[0-9]*(\.[0-9]{1,2})?$/
		$scope.backing = item;
		$scope.backing.SizeId = meta.SizeId;
		$scope.backing.GradeId = meta.GradeId;
		$scope.tracker = promiseTracker();
		$scope.invalid = {};

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		}

		$scope.imageBacking = function(id) {
			$modal.open({
				templateUrl: 'imageForm',
				controller: 'imageBackingController',
				size: 'lg',
				resolve: {
					id: function() {
						return id;
					}
				}
			});
		}

		$scope.create = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				save($scope.backing)
					.success(function(data) {
						$scope.backing = {};
						AlertsService.add('success', 'Successfully saved backing.');
						$modalInstance.close();
					}).error(function(data) {
						console.log('Error: ' + data);
						AlertsService.add('warning', 'An error occured while saving the backing.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$scope.backing = {};
			$modalInstance.dismiss();
		}
	})

	.controller('imageSizeController', function($scope, $modalInstance, AlertsService, MumtypesService, promiseTracker, id) {
		$scope.tracker = promiseTracker();
		$scope.id = id;
		$scope.cancel = function() {
			$modalInstance.dismiss();
		}

		$scope.uploadFile = function(files) {
			var defered = $scope.tracker.createPromise();
			MumtypesService.images.image.upload(id, files)
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
	})

	.controller('imageBackingController', function($scope, $modalInstance, AlertsService, MumtypesService, promiseTracker, id) {
		$scope.tracker = promiseTracker();
		$scope.id = id;
		$scope.cancel = function() {
			$modalInstance.dismiss();
		}

		$scope.uploadFile = function(files) {
			var defered = $scope.tracker.createPromise();
			MumtypesService.backings.image.upload(id, files)
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
