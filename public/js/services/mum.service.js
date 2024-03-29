
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('mum.service', [])
	.factory('MumService', function($http) {
		return {
			create: function() {
				return $http.post(getRoute('/api/mum'));
			},
			get: function(criteria) {
				return $http.get(getRoute('/api/mum'), {params: criteria});
			},
			update: function(mumId, mumData) {
				return $http.put(getRoute('/api/mum/') + mumId, mumData);
			},
			fetch: function(mumId) {
				return $http.get(getRoute('/api/mum/') + mumId);
			},
			delete: function(id) {
				return $http.delete(getRoute('/api/mum/') + id);
			},
			setStatus: function(mumId, statusId) {
				return $http.put(getRoute('/api/mum/' + mumId + '/status'), {StatusId: statusId});
			},
			addBear: function(mumId, bearId) {
				return $http.put(getRoute('/api/mum/' + mumId + '/bear/') + bearId);
			},
			removeBear: function(mumId, bearId) {
				return $http.delete(getRoute('/api/mum/' + mumId + '/bear/') + bearId);
			},
			setAccessories: function(mumId, accessoryData) {
				return $http.post(getRoute('/api/mum/' + mumId + '/accessory'), accessoryData);
			},
			yearly: {
				canOrder: function() {
					return $http.get(getRoute('/api/mumcontrol/can-order'));
				},
				startOrder: function() {
					return $http.post(getRoute('/api/mumcontrol/start-orders'));
				},
				stopOrder: function() {
					return $http.post(getRoute('/api/mumcontrol/stop-orders'));
				},
				truncate: function(password) {
					return $http.post(getRoute('/api/mumcontrol/truncate'), {Password: password});
				}
			}
		};
	});