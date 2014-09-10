
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('accentbows.service', [])
	.factory('AccentBowsService', function($http, $cookieStore) {
		return {
			get: function() {
				return $http.get(getRoute('/api/accentbow'));
			},
			create: function(bowData) {
				return $http.post(getRoute('/api/accentbow'), angular.copy(bowData));
			},
			update: function(id, bowData) {
				return $http.put(getRoute('/api/accentbow/') + id, angular.copy(bowData));
			},
			delete: function(id) {
				return $http.delete(getRoute('/api/accentbow/') + id);
			},
			fetch: function(id) {
				return $http.get(getRoute('/api/accentbow/') + id);
			},
			image: {
				upload: function(id, images) {
					var fd = new FormData();
					fd.append("image", images[0]);

					return $http.post(getRoute('/api/accentbow/' + id + '/image'), fd, {
						withCredentials: true,
						headers: {
							'Content-Type': undefined,
							'Authentication': $cookieStore.get('volunteerToken').jwt
						},
						transformRequest: [angular.identity]
					});
				}
			}
		}
	});