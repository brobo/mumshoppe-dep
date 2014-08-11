var app = angular.module('volunteer', [
	'ajoslin.promise-tracker',
	'ui.bootstrap',
	'ui.router',
	'alerts.controller',
	'garnishesAdd.controller',
	'garnishesAll.controller',
	'garnishesEdit.controller',
	'alerts.service',
	'garnishes.service']);

app.config(function($stateProvider, $urlRouterProvider, $httpProvider) {

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
		})
		.state('garnishes.add', {
			url: '/add',
			templateUrl: 'public/views/volunteer/garnishes/edit.html',
			controller: 'GarnishesAddController'
		})
		.state('garnishes.edit', {
			url: '/edit/:garnishId',
			templateUrl: 'public/views/volunteer/garnishes/edit.html',
			controller: 'GarnishesEditController'
		});

    $httpProvider.defaults.transformRequest = function(data) {
        if (data === undefined) {
            return data;
        }

        // If this is not an object, defer to native stringification.
	    if ( ! angular.isObject( data ) ) { 
	        return( ( data == null ) ? "" : data.toString() ); 
	    }

	    var buffer = [];

	    // Serialize each key in the object.
	    for ( var name in data ) { 
	        if ( ! data.hasOwnProperty( name ) ) { 
	            continue; 
	        }

	        var value = data[ name ];

	        buffer.push(
	            encodeURIComponent( name ) + "=" + encodeURIComponent( ( value == null ) ? "" : value )
	        ); 
	    }

	    // Serialize the buffer and clean it up for transportation.
	    var source = buffer.join( "&" ).replace( /%20/g, "+" ); 
	    return( source );
    }

    $httpProvider.defaults.headers.post = {'Content-Type': 'application/x-www-form-urlencoded'};
    $httpProvider.defaults.headers.put = {'Content-Type': 'application/x-www-form-urlencoded'};

	//PHP does not play nice with this feature. It's no big deal.
	//$locationProvider.html5Mode(true);

});
