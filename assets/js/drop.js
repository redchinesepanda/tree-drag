function onDragStart(event) {
	console.log('clients-drop onDragStart:', event.target);
	event.dataTransfer.setData('text/plain', event.target.id);
	event.target.setAttribute('parent', event.target.closest('.children').getAttribute('data-id'));
}
function onDragOver(event) {
	console.log('clients-drop onDragOver:', event.target);
	event.preventDefault();
}
function onDrop(event) {
	console.log('clients-drop onDrop');
	/* Получаем id двигаемого элемента переданный из onDragStart в параметре event.dataTransfer.text */
	let id = event.dataTransfer.getData('text');
	/* Получаем двигаемый элемент по id */
	let draggableElement = document.getElementById(id);
	/* Получаем родителя двигаемого элемента из атрибута parent */
	let draggableElementParentID = draggableElement.getAttribute('parent');
	/* Получаем родителя целевого элемента из атрибута data-id */
	let targetElementChildrenDataID = event.target.closest('.dropzone').getAttribute('data-id');
	/* Получаем целевой элемент по классу children и атрибуту data-id */
	let targetElementChildren = document.querySelector('div[class=children][data-id="' + targetElementChildrenDataID + '"]');
	/* Можно двигать, если целевой элемент не является наследником двигаемого и если целевой элемент уже не находится в целевом контейнере */
	if (!draggableElement.contains(targetElementChildren)
		&& draggableElementParentID !== targetElementChildrenDataID
		) {
		let objectAction = new CustomEvent('dropRequest', {
			detail: {
				childID: id,
				parentID: targetElementChildrenDataID,
				currentParentID: draggableElementParentID
			}
		});
		targetElementChildren.dispatchEvent(objectAction);
		targetElementChildren.appendChild(draggableElement);
		let targetElementChildrenElements = [...targetElementChildren.children];
		targetElementChildrenElements.sort((a, b) => a.id - b.id);
		targetElementChildrenElements.forEach(child => targetElementChildren.appendChild(child));
	}
}
function initItem(item) {
	if (item.id != 0) {
		item.setAttribute('draggable', true);
		item.addEventListener('dragstart', onDragStart);
		let itemButton = document.createElement('div');
		itemButton.innerHTML = '<i class="fa-solid fa-xmark"></i>';
		itemButton.classList.add('delete');
		itemButton.setAttribute('data-id', item.getAttribute('id'));
		itemButton.addEventListener('click', actionConfirm);
		//item.prepend(itemButton);
		item.querySelector('.data').prepend(itemButton);
		item.addEventListener('deleteRequest', prepareSendRequest);
		item.querySelector('h6').addEventListener('dblclick', actionForm);
	}
}
function initChildren(item) {
	let itemButton = document.createElement('div');
	itemButton.innerHTML = '<i class="fa-solid fa-plus"></i>';
	itemButton.classList.add('add');
	itemButton.setAttribute('data-id', item.getAttribute('data-id'));
	itemButton.addEventListener('click', actionForm);
	item.addEventListener('addRequest', prepareSendRequest);
	item.addEventListener('dropRequest', prepareDropRequest);
	let dropzone = document.createElement('div');
	dropzone.innerHTML = '<i class="fa-solid fa-inbox"></i>';
	dropzone.classList.add('dropzone');
	dropzone.setAttribute('data-id', item.getAttribute('data-id'));
	dropzone.setAttribute('dropzone', true);
	//item.prepend(dropzone, itemButton);
	item.closest('.item').querySelector('.data').prepend(dropzone, itemButton);
	dropzone.addEventListener('dragover', onDragOver);
	dropzone.addEventListener('drop', onDrop);
}
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.item').forEach(initItem);
	document.querySelectorAll('.children').forEach(initChildren);
});