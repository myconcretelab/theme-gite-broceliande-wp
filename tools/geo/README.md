# Visibilité et intentions de séjour — 13 septembre 2026

Le module SEO passe de 1.0.8 à 1.0.11. Les données métier restent dans Contrats/Booked. Aucun changement des formulaires, prix ou conditions de réservation.

- Les cinq pages françaises conservent tous leurs blocs Booked, photos et galeries. Les nouveaux textes sont éditables dans WordPress.
- Accueil (273) : introduction, envies de séjour, FAQ et titre/description.
- Le Liberté (289) : retrouvailles, événements entre proches, accès routier sourcé, FAQ et description.
- Les trois petits gîtes (332, 103, 123) : courts textes nature/amoureux/petite famille et liens contextuels.
- Accommodation relié à LodgingBusiness ; occupancy provient de max_people et jamais de sleeping_capacity ; surface depuis Booked. Identifiants existants conservés.
- Correction du HTTP 404 des sitemaps natifs valides sans rendre valides les routes inexistantes.
- Aucun FAQPage/Event/avis artificiel. Les FAQ visibles ont une utilité éditoriale indépendante des résultats enrichis.

## Vérifier et publier

```sh
php -l integrations/gites-broceliande-seo/gites-broceliande-seo.php
php tests/geo.php
wp eval-file tools/geo/apply.php check
```

Les chemins du script de migration sont relatifs au dossier du thème ; lancer WP-CLI avec le chemin complet depuis la racine WordPress. `check` valide les empreintes des cinq pages et l’inventaire des médias/blocs Booked avant toute écriture. Il refuse les changements concurrents.

Après commit et push, `update booked` actualise les dépôts distants. Cette commande ne recopie pas le module SEO : sauvegarder puis recopier `integrations/gites-broceliande-seo/{gites-broceliande-seo.php,seo.css}` vers `wp-content/plugins/gites-broceliande-seo/`. Contrôler la syntaxe avant de remplacer le fichier PHP. Ne pas écraser une version distante différente de la source auditée.

Puis `wp eval-file .../tools/geo/apply.php apply`. Le script sauvegarde les contenus et métadonnées dans l’option non autoloadée `gbseo_editorial_20260913_backup`. Une seconde exécution ne modifie pas une livraison déjà appliquée. Aucun hook ne réapplique les textes à chaque chargement.

## Vérifier après publication

Contrôler les 15 URL FR/EN/ES : HTTP 200, canonical, un H1, métadonnées uniques, JSON-LD valide et hreflang. Les trois sitemaps de pages doivent contenir chacun les cinq pages de leur langue. Vérifier qu’un sitemap de sous-type inexistant, une pagination vide et une page absente restent 404. Regarder l’accueil et Le Liberté sur ordinateur et mobile ; vérifier que le calendrier est toujours affiché sans envoyer de demande.

## Retour arrière

`wp eval-file .../tools/geo/apply.php rollback` restaure les cinq pages si elles correspondent toujours à cette livraison. En cas d’application partielle ou de nouvelle modification éditoriale, utiliser la sauvegarde conservée et restaurer uniquement les champs concernés après comparaison. Remettre ensuite les deux fichiers sauvegardés du module SEO. Les contenus antérieurs sont aussi dans les révisions WordPress.

Les sources et limites sont dans `sources.md`. Les versions EN/ES conservent leur contenu éditorial antérieur ; la correction technique et le schéma dynamique sont communs.
