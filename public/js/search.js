$(document).ready(function () {
	$("#search-user").on("input", function () {
		var utilisateur = $(this).val();

		// Vérifiez si l'input utilisateur est vide
		if (utilisateur.trim() !== "") {
			$.ajax({
				type: "GET",
				url: "/search-users",
				data: { user: utilisateur },
				success: function (data) {
					$("#search-results").html(""); // Effacez le contenu précédent

					if (data.length > 0) {
						$.each(data, function (index, user) {
							// Créez un élément div pour chaque utilisateur
							var userDiv = $("<div>").text(user.full_name);
							$("#search-results").append(userDiv);
						});
					} else {
						$("#search-results").html(
							"<div>Aucun utilisateur</div>"
						);
					}
				},
			});
		} else {
			$("#search-results").html(""); // Effacez le contenu s'il n'y a pas de texte de recherche
		}
	});
});

$(document).ready(function () {
	$("#search-item").on("input", function () {
		var item = $(this).val();

		// Vérifiez si l'input item est vide
		if (item.trim() !== "") {
			$.ajax({
				type: "GET",
				url: "/search-items",
				data: { item: item },
				success: function (data) {
					$("#search-results-items").html(""); // Effacez le contenu précédent

					if (data.length > 0) {
						var tableDiv = $("<table>").addClass("searchTable"); // Utilisez la classe searchTable
						var tableHead = $("<thead>");
						var tableBody = $("<tbody>");

						// Créez la ligne d'en-tête avec 3 colonnes
						var tableHeadRow = $("<tr>");
						tableHeadRow.append(
							$("<th>").text("Items"),
							$("<th>").text("Nom"),
							$("<th>").text("Prix")
						);
						tableHead.append(tableHeadRow);

						// Créez un objet pour stocker les éléments déjà affichés
						var itemsAffiches = {};

						// Parcourez les données et ajoutez les éléments au tableau
						$.each(data, function (index, item) {
							// Vérifiez si le nom de l'élément n'est pas déjà dans itemsAffiches
							if (!itemsAffiches[item.name]) {
								var tableDataRow = $("<tr>");
								tableDataRow.append(
									$("<td>").html(
										"<img src='/images/items/" +
											item.image +
											"' alt='item image' class='item-image'>"
									),
									$("<td>").text(item.name),
									$("<td>").html(
										item.price +
											' <img src="/images/gold.png" alt="Or" class="gold-image">'
									)
								);
								tableBody.append(tableDataRow);

								// Marquez l'élément comme déjà affiché
								itemsAffiches[item.name] = true;
							}
						});

						tableDiv.append(tableHead, tableBody);
						$("#search-results-items").append(tableDiv);
					} else {
						$("#search-results-items").html(
							"<div>Aucun item trouvé</div>"
						);
					}
				},
			});
		} else {
			$("#search-results-items").html(""); // Effacez le contenu s'il n'y a pas de texte de recherche
		}
	});
});
