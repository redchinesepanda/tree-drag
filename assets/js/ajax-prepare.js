function prepareDropRequest(event) {
	console.log('clients-ajax prepareDropRequest');
	let targetElementChildren = document.querySelector('div[class=children][data-id="' + event.detail.parentID + '"]');
	console.log('clients-ajax event.detail: ' + JSON.stringify(event.detail));
	let mode = 'parent';
	/* Если старый родитель 0, то установление связи */
	if (event.detail.currentParentID == 0) {
		mode = 'child';
	}
	/* Если новый родитель 0, то перемещение в корень */
	if (event.detail.parentID == 0) {
		mode = 'root';
	}
	let objectAction = new CustomEvent('dropRequest', {
		detail: {
			mode: mode,
			child_id: event.detail.childID,
			parent_id: event.detail.parentID
		}
	});
	prepareSendRequest(objectAction);
}
function prepareAddRequest(event) {
	console.log('clients-ajax prepareAddRequest');
	//console.log('clients-ajax prepareAddRequest event.target:', event.target);
	/*let id = event.target.getAttribute('data-id');
	console.log('clients-ajax prepareAddRequest id:', id);
	let targetElementChildren = document.querySelector('div[class=children][data-id="' + id + '"]');
	let objectAction = new CustomEvent('addRequest', {
		detail: {
			mode: 'add',
			name: 'test',
			description: 'text',
			parent_id: id
		}
	});*/
	prepareSendRequest(event);
}
function prepareDeleteRequest(event) {
	console.log('clients-ajax prepareDeleteRequest');
	console.log('clients-ajax prepareDeleteRequest event.target:', event.target);
	let id = event.target.closest('.delete').getAttribute('data-id');
	console.log('clients-ajax prepareDeleteRequest id:', id);
	let deletableElement = document.getElementById(id);
	deletableElement.remove();
	console.log('clients-ajax prepareDeleteRequest deletableElement: ', deletableElement);
	let objectAction = new CustomEvent('deleteRequest', {
		detail: {
			mode: 'delete',
			child_id: id,
			parent_id: 0
		}
	});
	prepareSendRequest(objectAction);
}