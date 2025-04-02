<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talents WoW</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #0e0e0e;
            color: white;
            font-family: Arial, sans-serif;
            overflow-x: auto;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 20px;
            overflow-x: auto;
            display: none;
        }
        .tree-columns {
            display: flex;
            gap: 40px;
        }
        .tree-container {
            position: relative;
            padding: 30px;
            border-radius: 12px;
            background-size: cover;
            background-position: center;
            background-color: rgba(0, 0, 0, 0.8);
            box-shadow: none;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(10, 64px);
            grid-auto-rows: 64px;
            gap: 10px;
            position: relative;
            z-index: 1;
        }
        .talent-box {
            width: 64px;
            height: 64px;
            background: #1c1c1c;
            border-radius: 6px;
            border: 2px solid #666;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
            position: relative;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        }
        .talent-box:hover {
            transform: scale(1.1);
            z-index: 2;
            box-shadow: 0 0 12px #ffcc00, 0 0 20px #ffaa00 inset;
        }
        .talent-box img {
            width: 100%;
            height: 100%;
            border-radius: 6px;
            border: 1px solid #000;
            object-fit: cover;
        }
        .talent-box::after {
            content: attr(data-name);
            position: absolute;
            bottom: -18px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 9px;
            color: white;
            white-space: nowrap;
        }
        .tooltip {
            display: block;
            visibility: hidden;
            opacity: 0;
            position: fixed;
            background: rgba(0, 0, 0, 0.95);
            color: white;
            padding: 12px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.7);
            z-index: 999;
            max-width: 300px;
            font-size: 13px;
            pointer-events: none;
            transition: opacity 0.2s, transform 0.2s;
        }
        svg.connections {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        svg line {
            stroke: #FFD700;
            stroke-width: 2;
        }
    </style>
</head>
<body>
    @include('navBar.navBar')

    <h1>Arbre de talents</h1>

    <h2>Choisissez une classe</h2>
    <select id="class-select" onchange="loadSpecs()">
        <option value="">-- Classe --</option>
        @foreach ($classes as $class)
            <option value="{{ $class['id'] }}">{{ $class['name'] }}</option>
        @endforeach
    </select>

    <h2>Choisissez une spécialisation</h2>
    <select id="specialization-select" style="display:none;">
        <option value="">-- Spécialisation --</option>
    </select>
    <button onclick="loadTalentTree()">Charger les talents</button>

    <div class="wrapper">
        <div class="tree-columns">
            <div class="tree-container" id="specTree">
                <svg class="connections"></svg>
                <div class="grid" id="talentGridSpec"></div>
            </div>
        </div>
    </div>

    <div class="tooltip" id="tooltip"></div>

    <script>
    async function loadSpecs() {
        const classId = document.getElementById("class-select").value;
        const specSelect = document.getElementById("specialization-select");
        specSelect.innerHTML = '<option value="">-- Spécialisation --</option>';

        if (!classId) return;

        try {
            const response = await fetch(`/specializations/${classId}`);
            const data = await response.json();

            if (!Array.isArray(data)) {
                console.error("Données inattendues reçues pour les spécialisations:", data);
                return;
            }

            data.forEach(spec => {
                specSelect.innerHTML += `<option value="${spec.id}" data-specname="${spec.name.toLowerCase().replace(/ /g, '-')}">${spec.name}</option>`;
            });

            specSelect.style.display = 'block';
        } catch (err) {
            console.error("Erreur lors du chargement des spécialisations:", err);
        }
    }

    async function loadTalentTree() {
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
                div.setAttribute("data-name", talent.name);
                div.innerHTML = `<img src="${talent.icon}" alt="${talent.name}">`;

                div.addEventListener('mouseenter', () => {
                    tooltip.innerHTML = `<strong>${talent.name}</strong><br>${talent.description}`;
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
            console.error("Erreur lors du chargement des talents:", error);
            grid.innerHTML = "Erreur lors de la récupération des talents.";
        }
    }
    </script>
</body>
</html>
