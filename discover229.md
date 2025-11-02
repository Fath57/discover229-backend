# 📘 Cahier des charges – Discover229  
### Annuaire intelligent des agences de tourisme au Bénin  

---

## 🧭 1. Présentation du projet

### 1.1. Contexte
Le secteur du tourisme au Bénin connaît une croissance notable, portée par la richesse culturelle, historique et naturelle du pays. Cependant, l’accès à l’information sur les agences de tourisme reste limité et dispersé.  
**Discover229** a pour objectif de centraliser ces informations en un seul point d’accès numérique fiable, moderne et intelligent.

### 1.2. Objectif global
Créer une **plateforme web et mobile** permettant à toute personne souhaitant visiter le Bénin :
- de consulter un annuaire des agences de tourisme locales,
- de découvrir leurs offres, circuits et services,
- et de **prendre contact directement** pour obtenir un devis ou un rendez-vous.

### 1.3. Vision
Faire de Discover229 la **porte d’entrée digitale du tourisme béninois**, en connectant visiteurs et acteurs du tourisme grâce à la technologie et à l’intelligence artificielle.

---

## 🧩 2. Public cible

| Segment | Description |
|----------|--------------|
| **Touristes étrangers** | Voyageurs souhaitant visiter le Bénin et planifier leur séjour à distance. |
| **Touristes locaux** | Béninois désirant découvrir leur propre pays. |
| **Agences de tourisme** | Entreprises et guides proposant des services touristiques. |
| **Hébergements / hôtels** | Structures souhaitant être visibles sur la plateforme. |
| **Institutions publiques** | Ministère du Tourisme, associations, offices régionaux. |

---

## 🧠 3. Objectifs spécifiques

1. Offrir un annuaire géolocalisé et actualisé des agences de tourisme.
2. Permettre la prise de contact directe entre visiteurs et agences.
3. Proposer une recherche avancée (type d’activités, régions, tarifs, langues parlées…).
4. Intégrer un **assistant IA** capable d’aider les utilisateurs à :
   - planifier un voyage,
   - recommander des agences adaptées,
   - proposer un itinéraire sur mesure.
5. Mettre à disposition un espace pour les agences afin de gérer leur profil, offres, et demandes reçues.

---

## 💻 4. Fonctionnalités principales (MVP)

### 4.1. Côté visiteur
- 🔍 Recherche d’agences par :
  - nom, ville, région, type d’activité (safari, tourisme culturel, balnéaire, etc.)
- 📍 Géolocalisation sur carte interactive (Google Maps ou OpenStreetMap)
- 📑 Fiche détaillée de chaque agence :
  - logo, description, services, contact, horaires, avis
- 💬 Formulaire de contact (avec envoi direct ou via chat IA)
- ⭐ Système d’avis et de notes (modéré)
- 🧭 Suggestion IA :
  - “Je veux visiter le Bénin pendant 5 jours” → proposition automatique d’agences pertinentes
  - Recommandation d’itinéraires touristiques personnalisés

### 4.2. Côté agence
- 🏢 Création de compte agence (validation manuelle par l’administrateur)
- ✏️ Gestion du profil (logo, description, localisation, contacts, services)
- 📦 Gestion des offres et circuits
- 💬 Consultation et réponse aux demandes de contact
- 📊 Tableau de bord simple (vues, messages reçus, statistiques de visite)

### 4.3. Côté administrateur
- 👤 Gestion des comptes utilisateurs et agences
- 🧾 Modération des contenus et des avis
- 📊 Tableau de bord global (statistiques, trafic, inscriptions)
- ⚙️ Paramétrage global (catégories, régions, filtres)

---

## 🤖 5. Module IA intégré

### 5.1. Objectif
Différencier Discover229 des annuaires classiques grâce à un **assistant conversationnel intelligent**.

### 5.2. Fonctionnalités IA
- **Chatbot touristique** :
  - Dialogue naturel avec l’utilisateur
  - Réponses contextualisées (lieux à visiter, météo, conseils culturels)
- **Recommandation intelligente** :
  - Moteur IA analysant les préférences (budget, durée, style de voyage)
  - Classement dynamique des agences
- **Analyse de sentiment** sur les avis clients pour mesurer la satisfaction.
- **Traduction automatique** des fiches (français ↔ anglais).

### 5.3. Technologies IA proposées
- OpenAI API (GPT) ou HuggingFace pour le traitement du langage
- Algorithme de recommandation basé sur similarité et scoring
- Moteur de recherche sémantique (ElasticSearch ou Meilisearch + NLP)

---

## 🏗️ 6. Architecture technique

### 6.1. Stack technique proposée
| Composant | Technologie |
|------------|-------------|
| Frontend Web | Vue.js 3 / Nuxt 3 ou React (Next.js) |
| Mobile | Application hybride (Ionic / React Native) |
| Backend | Laravel 11 / NestJS |
| Base de données | PostgreSQL ou MySQL |
| Hébergement | VPS / AWS EC2 / Dokku |
| Stockage fichiers | AWS S3 ou Cloudflare R2 |
| Authentification | JWT / OAuth 2.0 |
| Cartographie | OpenStreetMap ou Google Maps API |
| IA | OpenAI API / HuggingFace / LangChain |

---

## 🧱 7. Modèle de données simplifié

**Tables principales :**
- `users` (id, name, email, role, password_hash)
- `agencies` (id, name, description, city, region, contact, logo_url, owner_id)
- `offers` (id, agency_id, title, description, price_range, duration)
- `reviews` (id, user_id, agency_id, rating, comment)
- `messages` (id, from_user, to_agency, content, created_at)
- `ai_sessions` (id, user_id, conversation_context, preferences_json)

---

## 🔐 8. Sécurité & conformité
- HTTPS obligatoire  
- Validation manuelle des agences  
- Protection contre spam (reCAPTCHA / hCaptcha)  
- RGPD : consentement pour les cookies et gestion des données personnelles  
- Sauvegarde quotidienne de la base de données  

---

## 📅 9. Roadmap (MVP → V2)

| Phase | Durée | Objectifs |
|-------|--------|------------|
| **Phase 1 – Analyse & conception** | 2 semaines | Cahier des charges, maquettes, architecture |
| **Phase 2 – Développement MVP** | 2 mois | Backend, interface visiteur, fiches agences |
| **Phase 3 – Intégration IA** | 1 mois | Chatbot, recommandation basique |
| **Phase 4 – Tests & lancement** | 3 semaines | QA, sécurité, déploiement |
| **Phase 5 – V2 évolutive** | +3 mois | Application mobile, IA avancée, avis, itinéraires dynamiques |

---

## 💰 10. Modèle économique

- **Freemium pour les agences** :
  - Profil gratuit limité
  - Version premium (statistiques, IA, mise en avant)
- **Publicité ciblée** (hébergements, transporteurs)
- **Partenariats** avec le ministère du tourisme et offices régionaux
- **Commissions** sur demandes de devis (optionnel)

---

## 🧭 11. Indicateurs de succès (KPI)

- Nombre d’agences inscrites  
- Nombre de visiteurs mensuels  
- Taux de conversion (contact/agence)  
- Engagement avec le chatbot IA  
- Taux de satisfaction (avis utilisateurs)  

---

## ⚙️ 12. Évolutions futures (V2+)

- Application mobile native (Android / iOS)
- Réservation en ligne et paiement intégré
- Classement dynamique des meilleures agences
- Blog touristique avec contenus IA
- Module “Découvrir le Bénin” avec itinéraires thématiques
- Traduction automatique (multi-langues)

---

## 🧾 13. Conclusion

Discover229 ambitionne de devenir **la référence du tourisme digital au Bénin**, en connectant les voyageurs aux agences locales de manière fluide, fiable et intelligente.  
En misant sur la **simplicité d’usage**, la **valorisation du patrimoine** et la **puissance de l’IA**, la plateforme contribuera à renforcer la visibilité internationale du tourisme béninois.

---

**Auteur :** Arafath ATTA YAYA  
**Date :** Octobre 2025  
**Version :** 1.0  
