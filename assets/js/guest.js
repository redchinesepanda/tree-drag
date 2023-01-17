function expand(event) {
	event.target.closest('.item').querySelector('.children').classList.toggle('active');
}
function initItem(item) {
	console.log('initItem item.id:' + item.id);
	item.querySelector('h6').addEventListener('click', function (e) {
		this.closest('.item').querySelector('p').classList.toggle('active');
	});
	if (item.id == 0) {
		item.querySelector('.children').classList.toggle('active');
	}
	if (item.querySelector('.children').children.length) {
		let itemButton = document.createElement('div');
		itemButton.innerHTML = '<i class="fa-solid fa-caret-down"></i>';
		itemButton.classList.add('expand');
		itemButton.setAttribute('data-id', item.id);
		itemButton.addEventListener('click', expand);
		item.querySelector('.data').prepend(itemButton);
	}
}
document.addEventListener('DOMContentLoaded', function(){
	document.querySelectorAll('.item').forEach(initItem);
});