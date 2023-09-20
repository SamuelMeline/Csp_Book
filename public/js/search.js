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
						var tableDiv = $("<table>").addClass("item-table");
						var tableHead = $("<thead>");
						var tableBody = $("<tbody>");

						// Créez la ligne d'en-tête
						var tableHeadRow = $("<tr>");
						tableHeadRow.append(
							$("<th>").text("Items"),
							$("<th>").text("Nom"),
							$("<th>").text("Prix"),
						);
						tableHead.append(tableHeadRow);

						// Créez les lignes de données
						$.each(data, function (index, item) {
							var tableDataRow = $("<tr>");
							tableDataRow.append(
                                $("<td>").html("<img src='/images/" + item.image + "' alt='item image' class='item-image'>"),
                                $("<td>").text(item.name),
								$("<td>").html(item.price),
							);
							tableBody.append(tableDataRow);
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
