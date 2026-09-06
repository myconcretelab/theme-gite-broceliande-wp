# Site multilingue FR / EN / ES

Les cinq pages publiques françaises ont leurs traductions natives dans Polylang. Modifier les textes dans chaque page WordPress ; les données des gîtes restent dans Contrats. Les références aux gîtes, photos et rubriques sont conservées entre les versions.

`inc/multilingual.php` affiche le sélecteur de langue dans l’en-tête, sélectionne les menus et les pieds de page, et dirige les liens internes vers la traduction correspondante. Les pieds de page anglais et espagnol sont dans `parts/footer-en.html` et `parts/footer-es.html`, modifiables aussi via l’éditeur de site. Le français reste la langue par défaut, sans préfixe d’URL.

Le plugin Booked fournit les traductions du formulaire, du calendrier, des cartes et des galeries. Les messages de confirmation sont affichés dans la langue de la page. La logique de réservation et les payloads API restent communs.

Le plugin existant `gites-broceliande-seo` était installé directement sur le serveur, sans dépôt Git. Sa source maintenue est maintenant conservée dans `integrations/gites-broceliande-seo/`. Après mise à jour du thème et de Booked, recopier le contenu de ce dossier dans `wp-content/plugins/gites-broceliande-seo/`. Il localise les titres, métadonnées et données structurées, ainsi que les cartes et textes rendus sans JavaScript. Polylang génère les liens hreflang et les pages restent dans le sitemap WordPress.

Les scripts de ce dossier documentent la création initiale du 6 septembre 2026 ; ils ne sont pas exécutés automatiquement au chargement du thème :

```sh
wp eval-file wp-content/themes/theme-gite-broceliande-wp/tools/multilingual/create-drafts.php
wp eval-file wp-content/themes/theme-gite-broceliande-wp/tools/multilingual/publish.php
GB_PUBLISH_TRANSLATIONS=1 wp eval-file wp-content/themes/theme-gite-broceliande-wp/tools/multilingual/publish.php
```

La création refuse les traductions déjà présentes. La publication vérifie les versions sources, les langues et la structure des blocs avant toute écriture. La correspondance des identifiants est dans l’option `gb_multilingual_content`. Le fichier JSON contient les textes de l’import initial ; les modifications ultérieures se font dans WordPress.

Validation : syntaxe PHP, compilation CSS, contrôle des 15 pages publiques et du changement de langue, puis parcours de réservation dans les trois langues avec une API simulée (aucun message ou réservation de test envoyé).
