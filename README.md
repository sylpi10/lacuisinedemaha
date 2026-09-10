# La cuisine de Maha — Symfony

Cette application remplace progressivement le site Eleventy situé à la racine
du dépôt. Elle vit volontairement dans `symfony/` pour permettre une migration
sans interruption du site actuel.

## Démarrage local

```bash
cd symfony
composer install
php -S 127.0.0.1:8000 -t public
```

Puis ouvrir `http://127.0.0.1:8000`.

## Base MySQL

Ne modifie pas `.env` : crée `symfony/.env.local` avec les identifiants créés
dans cPanel > Bases de données MySQL :

```dotenv
DATABASE_URL="mysql://UTILISATEUR:MOT_DE_PASSE@localhost:3306/NOM_BASE?serverVersion=8.0"
```

Quand les entités seront ajoutées, créer et appliquer la migration :

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## Déploiement o2switch

Le document root du domaine devra pointer vers `symfony/public`, jamais vers la
racine de l'application. Sur le serveur :

```bash
composer install --no-dev --optimize-autoloader
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
```

Dans cPanel, sélectionner PHP 8.2 ou 8.3 et activer les extensions `pdo` et
`pdo_mysql`.

## Étapes fonctionnelles restantes

- Entités et EasyAdmin : `User`, `Menu`, `Dish`, `Review`, `ContactMessage`.
- Formulaire Symfony qui enregistre les demandes et envoie une notification à
  `contact@lacuisinedemaha.fr` via `MAILER_DSN`.
- Commande de création du premier administrateur.
