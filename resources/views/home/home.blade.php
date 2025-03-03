<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talents WoW</title>
    <script>
        let allClasses = [];
        let classSpecializations = {};

        async function loadData() {
            try {
                const response = await fetch('/specializations');
                const data = await response.json();

                if (!data.playable_classes || !data.classSpecializations) {
                    throw new Error("Réponse invalide des spécialisations.");
                }

                allClasses = data.playable_classes; // Correction ici
                classSpecializations = data.classSpecializations;

                console.log("✅ Classes chargées :", allClasses);
                console.log("✅ Correspondance Classe -> Spécialisations :", classSpecializations);

                const classSelect = document.getElementById("class-select");
                classSelect.innerHTML = `<option value="">Sélectionnez une classe</option>`;

                allClasses.forEach(cls => {
                    classSelect.innerHTML += `<option value="${cls.id}">${cls.name}</option>`;
                });

            } catch (error) {
                console.error("❌ Erreur lors du chargement des données :", error);
            }
        }

        async function filterSpecializations() {
            const selectedClass = document.getElementById("class-select").value;
            const specSelect = document.getElementById("specialization-select");

            specSelect.style.display = "block"; 

            if (!selectedClass) {
                specSelect.innerHTML = `<option value="">Aucune spécialisation disponible</option>`;
                return;
            }

            try {
                const response = await fetch(`/specializations/${selectedClass}`);
                const specializations = await response.json();

                if (response.ok) {
                    specSelect.innerHTML = `<option value="">Sélectionnez une spécialisation</option>`;
                    specializations.forEach(spec => {
                        specSelect.innerHTML += `<option value="${spec}">${spec}</option>`;
                    });
                } else {
                    specSelect.innerHTML = `<option value="">Aucune spécialisation disponible</option>`;
                }
            } catch (error) {
                console.error("Erreur lors de la récupération des spécialisations :", error);
                specSelect.innerHTML = `<option value="">Erreur de chargement</option>`;
            }
        }

        document.addEventListener("DOMContentLoaded", async function () {
            await loadData();
        });
    </script>
</head>
<body>
    @include('navBar.navBar')

    <h1>Choisissez une classe pour voir ses talents</h1>
    <select id="class-select" onchange="filterSpecializations()">
        <option value="">Sélectionnez une classe</option>
        @foreach ($classes as $class)
            <option value="{{ $class['id'] }}">{{ $class['name'] }}</option>
        @endforeach
    </select>

    <h1>Choisissez une spécialisation</h1>
    <select id="specialization-select" style="display:none;">
        <option value="">Sélectionnez une spécialisation</option>
    </select>

    <div id="talents"></div>
</body>
</html>
