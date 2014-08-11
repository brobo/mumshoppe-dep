var app = angular.module('volunteer', [
	'ajoslin.promise-tracker',
	'ui.bootstrap',
	'ui.router',
	'alerts.controller',
	'confirm.controller',
	'trinketsAdd.controller',
	'trinketsAll.controller',
	'trinketsEdit.controller',
	'alerts.service',
	'confirm.service',
	'trinkets.service']);

app.config(function($stateProvider, $urlRouterProvider, $httpProvider) {

	$urlRouterProvider.otherwise('/trinkets');

	$stateProvider
		.state('trinkets', {
			url: '/trinkets',
			template: '<ui-view />',
			abstract: true
		})
		.state('trinkets.all', {
			url: '',
			templateUrl: 'public/views/volunteer/trinkets/all.html',
			controller: 'trinketsAllController'
		})
		.state('trinkets.add', {
			url: '/add',
			templateUrl: 'public/views/volunteer/trinkets/edit.html',
			controller: 'trinketsAddController'
		})
		.state('trinkets.edit', {
			url: '/edit/:trinketId',
			templateUrl: 'public/views/volunteer/trinkets/edit.html',
			controller: 'trinketsEditController'
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
