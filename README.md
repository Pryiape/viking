# Viking Violet

Viking Violet est une application Laravel qui permet aux utilisateurs de créer, sauvegarder et explorer des builds de talents pour les classes de World of Warcraft via l’API officielle de Blizzard (Battle.net).

---

## 🚀 Fonctionnalités

- Authentification et gestion de comptes
- Création et gestion de builds de talents
- Connexion à l'API Battle.net pour récupérer classes, spécialisations et talents
- Interface moderne avec Bootstrap 5
- Sauvegarde des données en base MySQL

---

## 🛠️ Installation

1. **Cloner le dépôt :**

```bash
git clone https://github.com/Pryiape/viking.git
cd viking

Installer les dépendances PHP et JS :
composer install
npm install && npm run dev

Créer le fichier .env à partir de l’exemple :
cp .env.example .env
Générer la clé d’application :
php artisan key:generate
Configurer la base de données dans .env :
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=viking
DB_USERNAME=viking
DB_PASSWORD=viking_password

Lancer les migrations :
php artisan migrate

Démarrer le serveur local :
php artisan serve
L’application sera disponible sur http://localhost:8000

⚙️ Configuration .env
Voici les clés principales à configurer dans le fichier .env :
APP_NAME="Viking Violet"
APP_URL=http://localhost

# Informations de connexion à la base de données
DB_DATABASE=viking
DB_USERNAME=viking
DB_PASSWORD=viking_password

# Blizzard API
BLIZZARD_CLIENT_ID=ton_client_id
BLIZZARD_CLIENT_SECRET=ton_client_secret
BLIZZARD_API_URL=https://oauth.battle.net/token
BATTLENET_REGION=eu


🧙 Intégration avec l’API Blizzard (Battle.net)
Étapes pour obtenir un compte développeur :
Crée un compte Blizzard sur https://www.battle.net/

Connecte-toi au Blizzard Developer Portal

Crée une application pour obtenir :

Client ID

Client Secret

Ajoute ces identifiants dans ton fichier .env
BLIZZARD_CLIENT_ID=exemple123
BLIZZARD_CLIENT_SECRET=secretABC

L’URL d’authentification utilisée par l’app est :
https://oauth.battle.net/token
Tu peux aussi tester l’API manuellement avec Postman ou curl.

✅ À faire
Amélioration du système de recherche

Pagination et filtrage des builds

🧾 Licence
Ce projet est open-source et libre d’utilisation dans un cadre personnel ou éducatif.