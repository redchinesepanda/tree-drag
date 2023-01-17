function actionForm(event) {
	console.log('actionForm');
	console.log('actionForm event.target: ' + event.target);
	let previousForm = document.querySelector('form');
	if (previousForm) {
		previousForm.remove();
	}
	let mode = 'add';
	let submitValue = 'Добавить';
	let formName = '';
	let formDescription = '';
	let dataID = 0;
	let addElement = event.target.closest('.add');
	if (addElement !== null) {
		dataID = addElement.getAttribute('data-id');
		console.log('actionForm dataID: ' + dataID);
	} else {
		let itemElement = event.target.closest('.item');
		console.log('actionForm itemElement: ' + itemElement);
		dataID = itemElement.id;
		mode = 'data';
		submitValue = 'Обновить';
		formName = itemElement.querySelector('.item-name').innerText;
		formDescription = itemElement.querySelector('p').innerText;
	}
	/* Форма */
	let actionForm = document.createElement('form');
	actionForm.classList.add('form');
	actionForm.setAttribute('name', 'form');
	actionForm.setAttribute('method', 'GET');
	actionForm.setAttribute('action', '/');
	/* Поле Название */
	let actionFormInput = document.createElement('input');
	actionFormInput.setAttribute('type', 'text');
	actionFormInput.setAttribute('name', 'form-name');
	actionFormInput.setAttribute('value', formName);
	let textEnd = actionFormInput.value.length;
	actionFormInput.setSelectionRange(textEnd, textEnd);
	actionForm.appendChild(actionFormInput);
	/* Поле Описание */
	actionFormInput = document.createElement('input');
	actionFormInput.setAttribute('type', 'text');
	actionFormInput.setAttribute('name', 'form-description');
	actionFormInput.setAttribute('value', formDescription);
	textEnd = actionFormInput.value.length;
	actionFormInput.setSelectionRange(textEnd, textEnd);
	actionForm.appendChild(actionFormInput);
	/* Поле ID родителя */
	actionFormInput = document.createElement('input');
	actionFormInput.setAttribute('type', 'hidden');
	actionFormInput.setAttribute('name', 'form-id');
	actionFormInput.setAttribute('value', dataID);
	actionForm.appendChild(actionFormInput);
	/* Кнопка отмены */
	actionFormInput = document.createElement('input');
	actionFormInput.setAttribute('type', 'button');
	actionFormInput.setAttribute('name', 'form-cancel');
	actionFormInput.setAttribute('value', 'Отмена');
	actionFormInput.addEventListener('click', function (e) {
		e.preventDefault();
		this.closest('form').remove();
	});
	actionForm.appendChild(actionFormInput);
	/* Кнопка отправки */
	actionFormInput = document.createElement('input');
	actionFormInput.setAttribute('type', 'submit');
	actionFormInput.setAttribute('name', 'form-submit');
	actionFormInput.setAttribute('value', submitValue);
	actionForm.appendChild(actionFormInput);
	
	actionForm.addEventListener('submit', function (e) {
		e.preventDefault();
		let objectAction = new CustomEvent('addRequest', {
			detail: {
				mode: mode,
				name: this.elements['form-name'].value,
				description: this.elements['form-description'].value,
				child_id: this.elements['form-id'].value,
				parent_id: this.elements['form-id'].value
			}
		});
		prepareAddRequest(objectAction);
		this.closest('form').remove();
	});
    //document.body.prepend(actionForm);
	event.target.closest('.item').prepend(actionForm);
}
function actionConfirm(event) {
	console.log('actionConfirm');
	let previousForm = document.querySelector('.confirm-container');
	if (previousForm) {
		previousForm.remove();
	}
	let confirmPart = document.createElement('div');
	confirmPart.classList.add('confirm-container', 'confirm-active');
	let confirmH5 = document.createElement('h5');
	confirmH5.innerText = 'Обьект и все его потомки будут удалены';
	confirmPart.appendChild(confirmH5);
	let confirmButton = document.createElement('a');
	confirmButton.href = '/#cancel';
	confirmButton.innerText = 'Отмена';
	confirmButton.addEventListener('click', function (e) {
		e.preventDefault();
		this.closest('.confirm-container').remove();
	})
	confirmPart.appendChild(confirmButton);
	confirmButton = document.createElement('a');
	confirmButton.href = '/#delete';
	confirmButton.innerText = 'Удалить';
	confirmButton.addEventListener('click', function (e) {
		e.preventDefault();
		this.closest('.confirm-container').remove();
		prepareDeleteRequest(event);
	});
	confirmPart.appendChild(confirmButton);
    //document.body.prepend(confirmPart);
    event.target.closest('.item').prepend(confirmPart);
}