function requestHahdler(Request) {
	console.log('clients-ajax requestHahdler');
	let responseObject = JSON.parse(Request.responseText);
	console.log('clients-ajax responseObject: ' + JSON.stringify(responseObject));
	if (responseObject.hasOwnProperty('clientsObject')) {
		if (responseObject.messageCode == 1) {
			prepareRenderAdd(responseObject.clientsObject);
		}
		if (responseObject.messageCode == 2) {
			renderEdit(responseObject.clientsObject);
		}
	}
	if (responseObject.messageCode == 3) {
		window.location.href = '/objectsTree.php/?mode=login';
	}
	if (responseObject.messageCode == 4) {
		window.location.href = '/?mode=logout';
	}
}
function prepareSendRequest(event) {
	console.log('clients-ajax prepareSendRequest');
	console.log('clients-ajax event: ' + event);
	let url = '/manageObjects.php';
	let args = [];
	Object.keys(event.detail).forEach(detailName => {
		args.push(detailName + '=' + event.detail[detailName]);
	});
	console.log('clients-ajax args: ' + args);
	sendRequest('GET', url, args.join('&'), requestHahdler);
}
function sendRequest(requestMethod, requestPath, requestArgs, requestHandler) {
	console.log('clients-ajax sendRequest');
	let Request = CreateRequest();
	if (Request) {
		Request.onreadystatechange = function() {
			if (Request.readyState == 4) {
				requestHandler(Request);
			}
		}
	}
	if (requestMethod.toLowerCase() == "get" && requestArgs.length > 0) {
		requestPath += "?" + requestArgs;
	}
	Request.open(requestMethod, encodeURI(requestPath), true);
	Request.send(null);
}

function CreateRequest() {
	console.log('clients-ajax CreateRequest');
	let httpRequest = false;
	if (window.XMLHttpRequest) {
		httpRequest = new XMLHttpRequest();
		if (httpRequest.overrideMimeType) {
			httpRequest.overrideMimeType('application/json');
		}
	} else if (window.ActiveXObject) {
		try {
			httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!httpRequest) {
		console.log('clients-ajax Невозможно создать XMLHttpRequest');
	}
	return httpRequest;
}