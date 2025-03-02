<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talents WoW</title>
    <script>
        async function loadClasses() {
            try {
                const response = await fetch('/classes'); // Appelle la route Laravel qui récupère les classes
                const data = await response.json();
                const select = document.getElementById("class-select");

                data.forEach(cls => {
                    select.innerHTML += `<option value="${cls.id}">${cls.name}</option>`;
                });
            } catch (error) {
                console.error("Erreur de chargement des classes :", error);
            }
        }

        async function fetchTalents() {
            const selectedClass = document.getElementById("class-select").value;
            if (!selectedClass) return;

            try {
                const response = await fetch(`/talents/${selectedClass}`);
                const data = await response.json();

                let talentsDiv = document.getElementById("talents");
                talentsDiv.innerHTML = "";

                if (data.error) {
                    talentsDiv.innerHTML = `<p>Erreur: ${data.error}</p>`;
                    return;
                }

                data.talent_tree.nodes.forEach(node => {
                    talentsDiv.innerHTML += `<p><strong>${node.name}</strong>: ${node.description}</p>`;
                });
            } catch (error) {
                console.error("Erreur de récupération des talents :", error);
            }
        }

        document.addEventListener("DOMContentLoaded", loadClasses);
    </script>
</head>
<body>
    @include('navBar.navBar') <!-- Inclusion de la barre de navigation -->
    <h1>Choisissez une classe pour voir ses talents</h1>
    <select id="class-select" onchange="fetchTalents()">
        <option value="">Sélectionnez une classe</option>
    </select>

    <div id="talents"></div>
</body>
</html>
