@extends('welcome')

@section('title', 'Créer un Build')

@section('content')
<div class="container">
    <h1 class="text-center">Créer un Build</h1>
    <form action="{{ route('builds.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="sujet">Sujet *</label>
            <input type="text" name="sujet" id="sujet" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1">
            <label class="form-check-label" for="is_public">Rendre ce build public</label>
        </div>

        <!-- Talent Tree Selection -->
        <h2>Choisissez une classe</h2>
        <select id="class-select" class="form-control mb-3" onchange="loadSpecs()">
            <option value="">-- Classe --</option>
            @foreach ($classes as $class)
                <option value="{{ $class['id'] }}">{{ $class['name'] }}</option>
            @endforeach
        </select>

        <h2>Choisissez une spécialisation</h2>
        <select id="specialization-select" class="form-control mb-3" style="display:none;">
            <option value="">-- Spécialisation --</option>
        </select>
        <button type="button" class="btn btn-secondary mb-3" onclick="loadTalentTree()">Charger les talents</button>

        <div class="wrapper mb-3">
            <div class="tree-columns">
                <div class="tree-container" id="specTree">
                    <svg class="connections"></svg>
                    <div class="grid" id="talentGridSpec"></div>
                </div>
            </div>
        </div>

        <input type="hidden" name="talent_tree" id="talent_tree">

        <button type="submit" class="btn btn-primary" onclick="saveTalentTree()">Créer</button>
    </form>
</div>

<script>
let selectedTalents = [];
let columnPoints = {}; // Track points per column
const MAX_TALENT_POINTS = 31;

async function loadSpecs() {
    const classId = document.getElementById("class-select").value;
    const specSelect = document.getElementById("specialization-select");
    specSelect.innerHTML = '<option value="">-- Spécialisation --</option>';

    if (!classId) return;

    try {
        const response = await fetch(`/specializations/${classId}`);
        if (!response.ok) {
            throw new Error('Erreur lors de la récupération des spécialisations');
        }
        const data = await response.json();

        if (!Array.isArray(data)) return;

        data.forEach(spec => {
            specSelect.innerHTML += `<option value="${spec.id}" data-specname="${spec.name.toLowerCase().replace(/ /g, '-')}">${spec.name}</option>`;
        });

        specSelect.style.display = 'block';
    } catch (error) {
        console.error(error);
        alert('Erreur lors de la récupération des spécialisations');
    }
}

async function loadTalentTree() {
    selectedTalents = [];
    columnPoints = {}; // Reset column points
    const specSelect = document.getElementById("specialization-select");
    const specId = specSelect.value;
    const specName = specSelect.options[specSelect.selectedIndex].dataset.specname;
    const treeContainer = document.getElementById('specTree');
    const grid = document.getElementById("talentGridSpec");
    const svg = document.querySelector("#specTree svg.connections");
    const tooltip = document.getElementById("tooltip");
    grid.innerHTML = "Chargement...";
    svg.innerHTML = "";

    const backgroundImageUrl = `https://images.wowhead.com/images/talent-backgrounds/${specName}.jpg`;
    treeContainer.style.backgroundImage = `url('${backgroundImageUrl}')`;

    try {
        const response = await fetch(`/api/talent-tree/${specId}`);
        if (!response.ok) {
            throw new Error('Erreur lors de la récupération des talents');
        }
        const talents = await response.json();

        if (!Array.isArray(talents)) {
            grid.innerHTML = "Erreur : données talents invalides.";
            return;
        }

        grid.innerHTML = "";
        const nodeMap = {};

        talents.forEach(talent => {
            const div = document.createElement("div");
            div.className = "talent-box";
            div.style.gridColumnStart = talent.column + 1;
            div.style.gridRowStart = talent.row + 1;
            const displayName = talent.choices?.[0]?.name || talent.name;
            div.setAttribute("data-name", displayName);
            div.setAttribute("data-column", talent.column);
            if (talent.choices && talent.choices.length > 1) {
                div.innerHTML = talent.choices.slice(0, 2).map(choice => `<img src="${choice.icon}" alt="${choice.name}">`).join('');
            } else {
                div.innerHTML = `<img src="${talent.icon}" alt="${talent.name}">`;
            }
            div.addEventListener('mouseenter', (e) => {
                if (talent.choices && talent.choices.length > 1) {
                    tooltip.innerHTML = talent.choices.map(choice => `<strong style='color:#00ff00;'>${choice.name}</strong><br><span>${choice.description}</span><br><br>`).join('');
                } else {
                    tooltip.innerHTML = `<strong>${talent.name}</strong><br>${talent.description}`;
                }
                tooltip.style.visibility = 'visible';
                tooltip.style.opacity = 1;
            });
            div.addEventListener('mousemove', (e) => {
                tooltip.style.left = (e.pageX + 12) + 'px';
                tooltip.style.top = (e.pageY + 12) + 'px';
            });
            div.addEventListener('mouseleave', () => {
                tooltip.style.visibility = 'hidden';
                tooltip.style.opacity = 0;
            });

            div.addEventListener('click', () => {
                toggleTalentSelection(talent.id, div);
            });

            grid.appendChild(div);
            nodeMap[talent.id] = div;
        });

        talents.forEach(talent => {
            if (!talent.requires || !talent.requires.length) return;
            const from = nodeMap[talent.id];
            const fromX = from.offsetLeft + from.offsetWidth / 2;
            const fromY = from.offsetTop + from.offsetHeight / 2;

            talent.requires.forEach(reqId => {
                const to = nodeMap[reqId];
                if (!to) return;
                const toX = to.offsetLeft + to.offsetWidth / 2;
                const toY = to.offsetTop + to.offsetHeight / 2;

                const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
                line.setAttribute("x1", toX);
                line.setAttribute("y1", toY);
                line.setAttribute("x2", fromX);
                line.setAttribute("y2", fromY);
                svg.appendChild(line);
            });
        });

        document.querySelector('.wrapper').style.display = 'flex';
    } catch (error) {
        console.error(error);
        grid.innerHTML = "Erreur lors de la récupération des talents.";
    }
}

function toggleTalentSelection(talentId, element) {
    const column = element.getAttribute('data-column');
    if (!columnPoints[column]) {
        columnPoints[column] = 0;
    }

    if (element.classList.contains('selected')) {
        element.classList.remove('selected');
        selectedTalents = selectedTalents.filter(id => id !== talentId);
        columnPoints[column]--;
    } else if (columnPoints[column] < MAX_TALENT_POINTS) {
        element.classList.add('selected');
        selectedTalents.push(talentId);
        columnPoints[column]++;
    } else {
        alert(`Vous ne pouvez pas dépenser plus de ${MAX_TALENT_POINTS} points dans cette colonne.`);
    }
}

function saveTalentTree() {
    const json = JSON.stringify(selectedTalents);
    document.getElementById('talent_tree').value = json;
}
</script>
@endsection