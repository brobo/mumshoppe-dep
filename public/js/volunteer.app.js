var app = angular.module('volunteer', [
	'ui.bootstrap',
	'ui.router',
	'garnishesAll.controller',
	'garnishes.service']);

app.config(function($stateProvider, $urlRouterProvider) {

	$urlRouterProvider.otherwise('/garnishes');

	$stateProvider
		.state('garnishes', {
			url: '/garnishes',
			template: '<ui-view />',
			abstract: true
		})
		.state('garnishes.all', {
			url: '',
			templateUrl: 'public/views/volunteer/garnishes/all.html',
			controller: 'GarnishesAllController'
		});

	//PHP does not play nice with this feature. It's no big deal.
	//$locationProvider.html5Mode(true);

});