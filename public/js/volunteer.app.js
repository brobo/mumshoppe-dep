var app = angular.module('volunteer', [
	'ui.bootstrap',
	'ui.router']);

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
			template: 'Hello, garnishes!',
			controller: 'GarnishesAllController'
		});

	//PHP does not play nice with this feature. It's no big deal.
	//$locationProvider.html5Mode(true);

});