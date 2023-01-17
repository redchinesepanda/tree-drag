function renderEdit(clientsObject) {
	console.log('clients-ajax renderEdit');
	let itemElement = document.getElementById(clientsObject.id);
	itemElement.querySelector('.item-name').innerText = clientsObject.name;
	itemElement.querySelector('p').innerText = clientsObject.description;
}
function renderAdd(event) {
	console.log('clients-ajax objectRender');
	let targetElementChildren = document.querySelector('div[class=children][data-id="' + event.detail.parentID + '"]');
	console.log('clients-ajax targetElementChildren: ' + targetElementChildren);
	/* Новый Обьект */
	let clientsObject = document.createElement('div');
	clientsObject.setAttribute('id', event.detail.childID);
	clientsObject.setAttribute('draggable', true);
	clientsObject.setAttribute('class', 'item');
	/* Обьект Данные */
	let clientsObjectData = document.createElement('div');
	clientsObjectData.setAttribute('class', 'data');
	/* Данные Заголовок */
	let clientsObjectDataH6 = document.createElement('h6');
	/* Данные Заголовок Номер */
	let clientsObjectDataSpan = document.createElement('span');
	clientsObjectDataSpan.setAttribute('class', 'item-id');
	clientsObjectDataSpan.innerText = '#' + event.detail.childID;
	clientsObjectDataH6.appendChild(clientsObjectDataSpan);
	clientsObjectDataH6.innerHTML += ' ';
	/* Данные Заголовок Название */
	clientsObjectDataSpan = document.createElement('span');
	clientsObjectDataSpan.setAttribute('class', 'item-name');
	clientsObjectDataSpan.innerText = event.detail.name;
	clientsObjectDataH6.appendChild(clientsObjectDataSpan);
	clientsObjectData.appendChild(clientsObjectDataH6);
	/* Данные Заголовок Название */
	clientsObjectDataSpan = document.createElement('p');
	clientsObjectDataSpan.innerText = event.detail.description;
	clientsObjectData.appendChild(clientsObjectDataSpan);
	clientsObject.appendChild(clientsObjectData);
	/* Обьект Потомки */
	let clientsObjectChildren = document.createElement('div');
	clientsObjectChildren.setAttribute('class', 'children');
	clientsObjectChildren.setAttribute('data-id', event.detail.childID);
	clientsObject.appendChild(clientsObjectChildren);
	targetElementChildren.appendChild(clientsObject);
	/* Обьект Инициализация Событий */
	initItem(clientsObject);
	initChildren(clientsObjectChildren);
}
function prepareRenderAdd(clientsObject) {
	console.log('clients-ajax prepareObjectRender');
	console.log('clients-ajax clientsObject: ' + JSON.stringify(clientsObject));
	let targetElementChildren = document.querySelector('div[class=children][data-id="' + clientsObject.parent_id + '"]');
	let objectAction = new CustomEvent('renderRequest', {
		detail: {
			childID: clientsObject.id,
			parentID: clientsObject.parent_id,
			name: clientsObject.name,
			description: clientsObject.description
		}
	});
	renderAdd(objectAction);
}