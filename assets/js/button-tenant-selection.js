//after page ready onloaded
document.addEventListener('DOMContentLoaded', function () {
	//search mainmenu toolbar
	const mainmenuToolbar = searchMainmenuToolbar();
	addItemInMainmenuToolbar(mainmenuToolbar);
});

function searchMainmenuToolbar() {
	return document.querySelector('.mainmenu-toolbar');
}

function addItemInMainmenuToolbar(mainmenuToolbar) {
	const newLi = document.createElement('li');
	newLi.classList.add('mainmenu-quick-action');
	newLi.classList.add('with-tooltip');
	newLi.innerHTML = `<a href="javascript:void(0);"
		title="Change the tenant"
		data-original-title="Change the tenant"
		data-handler="onModalTenantSelection"
		data-control="popup">
		<i class=" icon-signal"></i>
		</a>`;
	mainmenuToolbar.children[0].after(newLi);
}
