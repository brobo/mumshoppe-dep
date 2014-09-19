
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

/**
  * Sets the prefix for all routes on the server - use for development purposes.
  * An empty route prefix is the recommended route for production builds.
**/
var ROUTE_PREFIX = "/mumshoppe";

// CONFIG RELATED FUNCTIONS FOLLOW

function getRoute(route) {
	return ROUTE_PREFIX + route;
}
