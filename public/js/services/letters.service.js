
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('letters.service', [])
	.factory('LettersService', function($http) {
		return {
			get: function() {
				return $http.get(getRoute('/api/letter'));
			},
			create: function(letterData) {
				return $http.post(getRoute('/api/letter'), angular.copy(letterData));
			},
			update: function(id, letterData) {
				return $http.put(getRoute('/api/letter/') + id, angular.copy(letterData));
			},
			delete: function(id) {
				return $http.delete(getRoute('/api/letter/') + id);
			},
			fetch: function(id) {
				return $http.get(getRoute('/api/letter/') + id);
			}
		}
	});
