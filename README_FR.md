
# Propositions de Projets Portfolio : Feuille de Route de Faisabilité & Scalabilité

**Préparé par :** Lead Engineer Senior
**Pour :** Équipe d'Ingénierie (2ème Année)
**Contexte :** Analyse de Faisabilité, Scalabilité et Unicité sur le Marché

---

## 📂 Projet 1 : Gestionnaire de Tâches Collaboratif (Augmenté par IA)

**Concept :** Un outil de productivité qui évolue d'un simple tableau Kanban vers un assistant de projet alimenté par l'IA.
**Focus :** Productivité, Algorithmes, Gestion d'Équipe.

### La Problématique

Les outils traditionnels de gestion de projet souffrent de frictions importantes ; les utilisateurs perdent un temps considérable à rédiger manuellement des rapports de bugs détaillés, des critères d'acceptation et à organiser les tags, ce qui conduit souvent à des tickets vagues et à un désalignement de l'équipe.

### La Solution

Une plateforme de productivité intelligente qui élimine la surcharge administrative en utilisant l'IA Générative pour rédiger automatiquement des descriptions techniques à partir de simples prompts (consignes) d'une ligne, et une logique algorithmique pour prioriser les tâches en fonction des délais et du risque d'épuisement (burnout) de l'équipe.

### Phase 1 : Le MVP ("L'Indispensable")

* **Objectif :** Assurer la note de passage avec des fondamentaux solides.
* **Frontend (React) :** Colonnes en glisser-déposer (À faire, En cours, Fait).
* **Backend (PHP/Node) :** API REST pour le CRUD (Créer, Lire, Mettre à jour, Supprimer) des tâches.
* **Indexation :** Recherche de tâches par titre ou assignataire via des requêtes SQL `LIKE` simples.
* **Logique (Simple) : Automatisation de la Matrice d'Eisenhower.**
* L'utilisateur saisit "Date limite" et "Importance".
* Le système étiquette automatiquement les tâches comme "À faire en premier", "À planifier" ou "À déléguer" selon un algorithme `si-alors` standard.



### Phase 2 : Le Scale ("Repousser les Limites")

* **Objectif :** Visers la note maximale en résolvant le problème de la "saisie de données ennuyeuse" grâce à l'IA.

**Fonctionnalité A : Le Générateur de Description par IA**

* *Le Problème :* Les utilisateurs détestent rédiger des rapports de bugs détaillés.
* *La Solution :* L'utilisateur tape un prompt : *"Corriger le bug de connexion sur la page d'accueil."*
* *La Tech :* Le système appelle une API LLM (OpenAI/Anthropic) pour générer une description technique complète en Markdown, des critères d'acceptation et des suggestions de tags.

**Fonctionnalité B : Requêtes en Langage Naturel**

* Au lieu de filtres complexes, permettre des requêtes comme : *"Montre-moi tous les bugs prioritaires de Sarah la semaine dernière."*
* Le backend convertit ce langage naturel en une requête SQL/NoSQL.

**Fonctionnalité C : Priorisation Intelligente**

* Visualiser le "Risque de Burnout" avec un graphique rouge pour les membres de l'équipe surchargés, basé sur la complexité estimée des tâches.

### ⚠️ Évaluation de l'Ingénieur Senior

* **Banalité/Saturation : Élevée.** (Les recruteurs voient des milliers d'applis "To-Do").
* **Stratégie d'Unicité : Faible** à moins que les fonctionnalités IA ne fonctionnent parfaitement.
* **Conseil :** Ne mettez pas en avant le bouton "Créer une Tâche" dans votre démo. Mettez en avant le bouton **"Générer la Description"**. C'est votre "Effet Waouh".

---

## 📂 Projet 2 : Moteur de Recommandation "Netflix" (Films & Séries)

**Concept :** Une architecture de streaming qui comprend les *scénarios de contenu* (recherche sémantique) et les *dynamiques sociales* (suggestions inter-profils).
**Focus :** Big Data, Recherche Vectorielle (Vector Search), Expérience Utilisateur.

### La Problématique

Les plateformes de streaming standard s'appuient sur des métadonnées rigides (Genre, Année, Acteur), rendant impossible pour les utilisateurs la recherche de scénarios spécifiques (ex: "un film sur un robot triste dans l'espace") ou la réception de recommandations prenant en compte l'influence sociale entre les membres d'un même foyer.

### La Solution

Une architecture de streaming "Sémantique d'abord" qui utilise les **Embeddings Gemini** et **ChromaDB** pour comprendre la nuance des résumés d'intrigue via la recherche vectorielle, combinée à un algorithme unique inter-profils qui pondère les recommandations en fonction de l'historique de visionnage des membres de la famille de confiance.

### Phase 1 : Le MVP ("L'Indispensable")

* **Objectif :** Une interface propre type "Netflix-lite" pour parcourir les films.
* **Frontend (React) :** Grille de défilement infini des affiches de films.
* **Backend (PHP/Python) :** Servir les données de films depuis une base MySQL locale (importée de TMDB).
* **Indexation :** Filtrer par Année, Genre et Note.
* **Logique (Simple) : Filtrage Collaboratif Lite.**
* "Les utilisateurs qui ont aimé ceci ont aussi aimé..." (Requête SQL pure trouvant les points communs dans les favoris des utilisateurs).



### Phase 2 : Le Scale ("Repousser les Limites")

* **Objectif :** Passer de la "Correspondance de Métadonnées" à la "Compréhension Sémantique" via les Bases de Données Vectorielles.

**Mise à jour de la Stack Technique :**

* **Embeddings :** Modèle d'Embedding Gemini (Google) ou un modèle local (ex: `all-MiniLM-L6-v2`) pour l'efficacité des coûts.
* **Base Vectorielle : ChromaDB** (Open source, facile à faire tourner localement) pour stocker et requêter les vecteurs.

**Fonctionnalité A : "Smart Search" (Découverte Sémantique)**

* *La Logique :* Nous transformons tous les résumés de films en vecteurs dans ChromaDB.
* *Action Utilisateur :* L'utilisateur tape une description vague : *"Films sur un robot qui apprend à aimer dans un futur dystopique."*
* *Le Processus :* Le système convertit cette requête en vecteur via le modèle Gemini et interroge ChromaDB pour les "Plus Proches Voisins" (Cosine Similarity).
* *Résultat :* Il trouve *Wall-E* ou *A.I. Intelligence Artificielle*, même si le mot "Robot" n'était pas dans le titre.

**Fonctionnalité B : Recommandation Personnalisée "Basée sur le Contenu"**

* *La Logique :* Quand un utilisateur regarde un film, nous prenons le vecteur de ce film et cherchons des vecteurs similaires dans ChromaDB.
* *Résultat :* "Parce que vous avez regardé *John Wick* (Vecteur Action/Vengeance), nous recommandons *Nobody* (Vecteur Vengeance similaire)."
* *Couche Hybride :* Nous combinons cette recommandation IA avec des filtres de métadonnées standards (plus simples, sans IA) comme **Même Genre** ou **Même Acteur** pour affiner la liste.

**Fonctionnalité C : Pondération "Sociale" Multi-Profils (Logique Inter-Profils)**

* *Architecture :* 1 Compte = 4 Profils.
* *L'Algorithme :*
1. Le système analyse ce que le Profil 1 aime.
2. Le système recommande un film que le Profil 1 a aimé au Profil 2.
3. **Fonction de Récompense :** Si le Profil 2 clique/regarde  On augmente le "Score de Compatibilité des Goûts" entre P1 et P2.
4. **Fonction de Pénalité :** Si le Profil 2 ignore  On baisse le poids des suggestions du Profil 1 pour ce genre spécifique.



### ⚠️ Évaluation de l'Ingénieur Senior

* **Banalité/Saturation : Moyenne.** (Les applis de films sont courantes, mais celles basées sur les **Vecteurs** utilisant ChromaDB/Gemini sont à la pointe pour des étudiants).
* **Stratégie d'Unicité : Très Élevée.**
* **Conseil :** La combinaison de la Recherche Sémantique (Profondeur Technique) + Pondération Inter-Profils (Profondeur System Design) en fait un projet gagnant pour un portfolio.

---

## 📂 Projet 3 : Gardien de Santé Animale

**Concept :** Un outil de triage pour les propriétaires d'animaux afin de déterminer l'urgence médicale via des Arbres de Décision.
**Focus :** Logique, Données Médicales, Mobile-First.

### La Problématique

Les propriétaires d'animaux manquent souvent des connaissances médicales pour distinguer un problème mineur d'une urgence vitale, menant soit à des visites de panique inutiles chez le vétérinaire, soit à des retards de traitement dangereux.

### La Solution

Un système de triage numérique qui fournit une aide à la décision immédiate utilisant un **Classifieur par Arbre de Décision**, analysant des symptômes spécifiques pour catégoriser l'urgence médicale (Surveiller vs Urgence) tout en maintenant un carnet de santé et de vaccination numérique centralisé.

### Phase 1 : Le MVP ("L'Indispensable")

* **Objectif :** Un carnet de santé numérique.
* **Frontend (React) :** Tableau de bord montrant le profil de l'animal, courbe de poids et dates de vaccins.
* **Backend (PHP) :** Stocker les visites et envoyer des rappels email (PHP Mailer) avant les vaccins.
* **Logique (Simple) : Alertes Basées sur des Règles.**
* `Si le Poids chute de >10% en 1 mois -> Déclencher "Alerte Santé".`



### Phase 2 : Le Scale ("Repousser les Limites")

* **Objectif :** Le "Vérificateur de Symptômes" (IA de Triage).

**Fonctionnalité A : Le Classifieur par Arbre de Décision**

* *Le Modèle :* Utiliser un **Arbre de Décision** (Machine Learning) pour prédire l'urgence : "Aller chez le Véto Immédiatement" vs "Surveiller 24h".
* *Pourquoi :* Les arbres de décision sont interprétables. Vous pouvez montrer au jury exactement *pourquoi* l'IA a donné ce conseil.

**Fonctionnalité B : Gérer la Rareté des Données (Les 2 Scénarios)**

* *Scénario 1 (Vous avez les données) :* Entraîner un modèle sur un dataset liant symptômes (Vomissements, Léthargie) aux résultats.
* *Scénario 2 (Pas de données) :*
1. **Système Expert :** Coder en dur la logique basée sur des journaux vétérinaires (`SI gencives == pâles ET respiration == rapide ALORS urgence = HAUTE`).
2. **Données Synthétiques :** Utiliser GPT-4 pour générer 5 000 cas "fictifs" réalistes de symptômes canins, puis entraîner votre modèle sur ces données (Doit être divulgué lors de la présentation).



### ⚠️ Évaluation de l'Ingénieur Senior

* **Banalité/Saturation : Faible.** (Très peu de développeurs juniors font de la VetTech).
* **Stratégie d'Unicité : Élevée (Attrait de Niche).**
* **Conseil :** Ce projet a une forte valeur émotionnelle ("Sauver des animaux"). Cependant, c'est risqué si vous ne pouvez pas démontrer que "l'IA Médicale" fonctionne de manière fiable.

---

## 📊 La Matrice de Décision Finale

J'ai fusionné les scores de faisabilité précédents avec la nouvelle note **"Unicité/Saturation"** pour vous donner une réponse mathématique définitive.

| Critères | **Projet 1 : Tâches** | **Projet 2 : Netflix** | **Projet 3 : Santé Animale** |
| --- | --- | --- | --- |
| **Disponibilité des Données** | 2/10 (À créer manuellement) | **10/10** (TMDB est instantané) | 4/10 (Difficile à trouver) |
| **Facteur Visuel "Wow"** | 5/10 (Ressemble à Excel) | **9/10** (Affiches/Trailers) | 7/10 (Animaux mignons) |
| **Adéquation Tech Stack** | 10/10 (CRUD Standard) | 9/10 (Parfait pour la Recherche) | 8/10 (CRUD Standard) |
| **Unicité (Saturation)** | **Faible** (Marché Générique) | **Élevée** (Tech Vectorielle) | **Moyenne-Haute** (Niche) |
| **Faisabilité IA** | 7/10 (Intégration LLM) | **9/10** (RecSys est standard) | 4/10 (IA Médicale risquée) |
| **Complexité pour Scaler** | Difficile (Besoin de données user) | **Facile** (Données existent) | Difficile (Logique médicale) |
| **SCORE TOTAL** | **28 / 60** | **47 / 60** | **33 / 60** |

---

## 🏆 Verdict Final

### 🥇 Gagnant : Projet 2 (Netflix)

**Pourquoi :** Il atteint "l'Équilibre Parfait".

1. **Données :** Vous pouvez télécharger les données ce soir. Pas besoin de taper des données fictives.
2. **Tech :** Vous utilisez **ChromaDB** et les **Embeddings Gemini**, qui sont des mots-clés CV de premier plan actuellement.
3. **Scaling :** La "Logique Netflix" (Profil 1 recommande au Profil 2) + la Recherche Sémantique est une manière brillante de montrer que vous comprenez le System Design complexe.

### 🥈 Deuxième Place : Projet 3 (Santé Animale)

**Pourquoi :** Il a du cœur.
Si vous êtes passionnés par les animaux, faites-le. Mais soyez prévenus : le **Scénario B (Rareté des Données)** est bien réel. Vous passerez probablement plus de temps à générer des fausses données qu'à coder l'application.

### 🥉 Dernière Place : Projet 1 (Gestionnaire de Tâches)

**Pourquoi :** C'est trop risqué d'être ennuyeux.
À moins que votre **Générateur de Description par IA** ne soit absolument époustouflant, le jury le regardera et dira : "Oh, encore un clone de Trello." C'est celui qui demande le plus d'efforts pour paraître unique.
