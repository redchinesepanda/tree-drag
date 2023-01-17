document.addEventListener('DOMContentLoaded', function() {
	let itemButton = document.querySelector('.logout-button');
	if (itemButton !== null) {
		itemButton.addEventListener('click', function (e) {
			e.preventDefault();
			let objectAction = new CustomEvent('logoutRequest', {
				detail: {
					mode: 'logout'
				}
			});
			prepareSendRequest(objectAction);
		});
	}
	itemButton = document.querySelector('.login-button');
	if (itemButton !== null) {
		itemButton.addEventListener('click', function (e) {
			e.preventDefault();
			actionFormLogin(e);
		});
	}
});