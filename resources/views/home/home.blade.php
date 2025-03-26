<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talents WoW</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

                allClasses = data.playable_classes;
                classSpecializations = data.classSpecializations;

                console.log(" Classes chargées :", allClasses);
                console.log(" Correspondance Classe -> Spécialisations :", classSpecializations);

                const classSelect = document.getElementById("class-select");
                classSelect.innerHTML = `<option value="">Sélectionnez une classe</option>`;

                allClasses.forEach(cls => {
                    classSelect.innerHTML += `<option value="${cls.id}">${cls.name}</option>`;
                });

            } catch (error) {
                console.error(" Erreur lors du chargement des données :", error);
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
                specSelect.innerHTML += `<option value="${spec.id}">${spec.name}</option>`;
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

    <button id="getTalents">Obtenir les talents</button>

    <div id="talentTree">
        <h2>Arbre de talents :</h2>
        <div id="talentContainer"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#getTalents').click(function() {
                var specializationId = $('#specialization-select').val();
                
                if (!specializationId) {
                    alert("Veuillez sélectionner une spécialisation !");
                    return;
                }

                $.ajax({
                    url: '/get-talent-tree/' + specializationId,
                    method: 'GET',
                    success: function(data) {
                        let talentHTML = `<h2>Arbre de talents :</h2><div class="talent-grid">`;
                        if (data.talent_nodes) {
                            data.talent_nodes.forEach(talent => {
                                talentHTML += `
                                    <div class="talent">
                                        <img src="${talent.talent.icon_url ?? 'https://via.placeholder.com/50'}" 
                                             alt="${talent.talent.name ?? 'Talent inconnu'}">
                                        <p class="talent-name" data-desc="${talent.talent.description ?? ''}">
                                            ${talent.talent.name ?? 'Talent inconnu'}
                                        </p>
                                    </div>`;
                            });
                        } else {
                            talentHTML += `<p>Aucun talent trouvé pour cette spécialisation.</p>`;
                        }
                        talentHTML += `</div>`;
                        $('#talentContainer').html(talentHTML);
                    },
                    error: function() {
                        $('#talentContainer').html('Erreur lors de la récupération des talents.');
                    }
                });
            });
        });
    </script>

    <style>
        .talent-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        .talent {
            text-align: center;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 8px;
            background-color: #222;
            color: white;
        }
        .talent img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
        }
    </style>
    <h2>Builds publics</h2>

@foreach($publicBuilds as $build)
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $build->sujet }}</h5>
            <p>{{ $build->description }}</p>
            <small>Créé par {{ $build->user->name }}</small>
        </div>
    </div>
@endforeach

</body> 
</html>
