
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('confirm.service', ['ui.bootstrap', 'ajoslin.promise-tracker'])
	.factory('ConfirmService', ['$rootScope', '$modal', function($rootScope, $modal, promiseTracker) {
		var defaults = {
			head: "Please confirm",
			body: "Are you sure?",
			yes: "OK",
			no: "Cancel"
		};
		return {
			confirm: function(data, after) {
				for (attr in defaults) {
					data[attr] = data[attr] || defaults[attr];
				}

				return modalInstance = $modal.open({
					templateUrl: 'public/views/res/confirm.html',
					controller: 'ConfirmController',
					resolve: {
						data: function() {
							return data;
						},
						after: function() {
							return after;
						}
					}
				});
			}
		}
	}]);
