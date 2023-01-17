function actionFormLogin(event) {
	console.log('actionFormLogin');
	//console.log('actionFormLogin event.target: ' + event.target);
	let previousForm = document.querySelector('form');
	if (previousForm) {
		previousForm.remove();
	}
	let mode = 'auth';
	let submitValue = 'Войти';
	let formName = '';
	let formPassword = '';
	/* Форма */
	let actionForm = document.createElement('form');
	actionForm.classList.add('form');
	actionForm.setAttribute('name', 'form');
	actionForm.setAttribute('method', 'GET');
	actionForm.setAttribute('action', '/');
	/* Поле Название */
	let actionFormInput = document.createElement('input');
	actionFormInput.setAttribute('type', 'text');
	actionFormInput.setAttribute('name', 'form-login');
	actionFormInput.setAttribute('value', formName);
	actionForm.appendChild(actionFormInput);
	/* Поле Описание */
	actionFormInput = document.createElement('input');
	actionFormInput.setAttribute('type', 'password');
	actionFormInput.setAttribute('name', 'form-password');
	actionFormInput.setAttribute('value', formPassword);
	actionForm.appendChild(actionFormInput);
	/* Кнопка отмены */
	actionFormInput = document.createElement('input');
	actionFormInput.setAttribute('type', 'submit');
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
		let objectAction = new CustomEvent('loginRequest', {
			detail: {
				mode: mode,
				user_name: this.elements['form-login'].value,
				user_password: MD5(this.elements['form-password'].value)
			}
		});
		prepareSendRequest(objectAction);
		this.closest('form').remove();
	});
	event.target.closest('.login').prepend(actionForm);
}