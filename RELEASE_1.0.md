# Tentative de Release 1.0 - The Forge Backend

## Description
Ce projet est le backend de l'application "The Forge", une plateforme de gestion et de partage de collections d'épées historiques et fantastiques. Développé avec Laravel, il fournit une API robuste pour la gestion des utilisateurs, des épées, des collections et des interactions sociales.

## Fonctionnalités Principales (v1.0)

### 🔐 Authentification & Profils
- **Inscription et Connexion** : Système sécurisé via Laravel Sanctum.
- **Gestion du Profil** : Mise à jour des informations utilisateur et de l'avatar.
- **Déconnexion** : Gestion sécurisée des sessions.

### ⚔️ Gestion des Épées (Swords)
- **Catalogue complet** : Consultation de la liste des épées et détails individuels.
- **CRUD Épées** : Création, modification et suppression d'épées (pour les utilisateurs authentifiés).
- **Médias** : Upload et gestion d'images associées aux épées.

### 📁 Collections
- **Organisation** : Création et gestion de collections personnalisées.
- **Visibilité** : Consultation des collections publiques.

### 💬 Interactions Sociales
- **Système de Likes** : Possibilité d'aimer des épées.
- **Commentaires** : Système complet de commentaires sur les épées (Ajout, Modification, Suppression).
- **Abonnements (Follow)** : Suivre d'autres forgerons/utilisateurs pour voir leur activité.
- **Fils d'actualité (Feeds)** : Flux personnalisés pour les épées et les collections.

### 🏛️ Données de Référence
- **Époques (Eras)** : Gestion des contextes temporels.
- **Origines** : Gestion des provenances géographiques ou culturelles.
- **Critères** : Système de classification et de filtrage.

## Installation
1. Clonez le dépôt.
2. Installez les dépendances : `composer install` & `npm install`.
3. Configurez votre fichier `.env`.
4. Lancez les migrations et les seeders : `php artisan migrate --seed`.
5. Lancez le serveur : `php artisan serve`.

---
*Réalisé pour le projet SAE 401.*
