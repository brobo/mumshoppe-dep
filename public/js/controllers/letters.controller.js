angular.module('letters.controller', [])
	.controller('lettersController', function($scope, $modal, promiseTracker, LettersService, AlertsService, ConfirmService) {
		
		$scope.updateLetters = function() {
			LettersService.get().success(function(data) {		
				$scope.letters = data;
			});
		}

		$scope.updateLetters();

		$scope.addLetter = function(grade) {
			$modal.open({
				templateUrl: 'letterForm',
				controller: 'editLetterController',
				size: 'lg',
				resolve: {
					letter: function() {
						return {};
					},
					save: function() {
						return LettersService.create;
					}
				}
			}).result.then(function() {
				$scope.updateLetters();
			});
		}

		$scope.editLetter = function(letter) {
			$modal.open({
				templateUrl: 'letterForm',
				controller: 'editLetterController',
				size: 'lg',
				resolve: {
					letter: function() {
						return angular.copy(letter);
					},
					save: function() {
						return function(letter) {
							return LettersService.update(letter.Id, letter);
						};
					}
				}
			}).result.then(function() {
				$scope.updateLetters();
			});
		}

		$scope.deleteLetter = function(letter) {
			ConfirmService.confirm({
				head: "Delete Letter",
				body: "Are you sure you want to permanently delete " + letter.Name + "?"
			}, function() {
				return LettersService.delete(letter.Id)
					.success(function() {
						AlertsService.add('success', 'Successfully deleted letter.');
					}).error(function(err) {
						console.log(err);
						AlertsService.add('danger', 'Error while deleting letter.');
					}).finally(function() {
						$scope.updateLetters();
					});
			});
		}
	})

	.controller('editLetterController', function($scope, $modalInstance, promiseTracker, letter, save, AlertsService) {
		$scope.REGEX_PRICE = /^[0-9]*(\.[0-9]{1,2})?$/
		$scope.letter = letter;
		$scope.invalid = {};
		$scope.tracker = promiseTracker();

		$scope.validate = function(form, field) {
			$scope.invalid[field] = form[field].$invalid;
		};

		$scope.save = function(form) {
			if (form.$valid) {
				var defered = $scope.tracker.createPromise();
				save($scope.letter)
					.success(function(data) {
						$scope.letter = {};
						AlertsService.add('success', 'Successfully saved letter.');
						$modalInstance.close();
					}).error(function(data) {
						console.log('Error: ' + data);
						AlertsService.add('warning', 'An error occured while saving the letter.');
						$modalInstance.dismiss();
					}).finally(function() {
						defered.resolve();
					});
			}
		}

		$scope.cancel = function() {
			$scope.letter = {};
			$modalInstance.dismiss();
		}
	});
