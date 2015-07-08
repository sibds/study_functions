var page = require('webpage').create(), 
	system = require('system'),
	address;

if (system.args.length === 1) {
    console.log('Usage: checkStatusCodes.js <some URL>');
    phantom.exit(1);
} else {
	address = system.args[1];
	// page.onResourceReceived = function(response) {
	// 	//if (response.status != 200)
	// 		// console.log('Status '+ response.status + ' on '+ response.url);
	// };

	page.onConsoleMessage = function(msg) {
		console.log(msg);
    	// system.stderr.writeLine('console: ' + msg);
	};

	page.open(address, function (status) {
	    if (status !== 'success') {
	        console.log('Unable to load the address!');
	        phantom.exit();
	    } else {
	        window.setTimeout(function () {
	            phantom.exit();
	        }, 1000); // Change timeout as required to allow sufficient time 
	    }
	});
}



