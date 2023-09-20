const deleteButtons = document.querySelectorAll(".delete-button");

deleteButtons.forEach((button) => {
	button.addEventListener("click", function (event) {
		event.preventDefault();

		const url = this.getAttribute("href");
		const confirmation = confirm(
			"Êtes-vous sûr de vouloir supprimer cet élément ?"
		);

		if (confirmation) {
			window.location.href = url;
		}
	});
});