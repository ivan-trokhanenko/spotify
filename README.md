Spotify
---------------------

 * Introduction
 * Requirements
 * Recommended modules
 * Installation
 * Configuration
 * Maintainers


INTRODUCTION
------------

This module integrates Spotify with Drupal CMS.
It currently supports the Artists.

 * To submit bug reports and feature suggestions, or track changes:
   https://github.com/ivan-trokhanenko/spotify/issues

REQUIREMENTS
------------

Drupal core version 8.9 or higher.


RECOMMENDED MODULES
-------------------

 * Markdown filter (https://www.drupal.org/project/markdown):
   When enabled, display of the project's README.md help will be rendered
   with markdown.


INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/docs/extending-drupal/installing-modules
   for further information.


CONFIGURATION
-------------

 * Install the "Spotify" and "Spotify Artist" modules the go to the settings page "/admin/config/services/spotify" and add credentials.
 * If you don't have a client ID and secret key, go to your Spotify Dashboard and create an app.
 * The "Spotify Artist" module provides "Artist" content type and "Genre" taxonomy vocabulary.
 * Using the "Artist" content type you can save up to 20 Spotify artist ids.
 * A list of the names of these artists is displayed in a custom block "Spotify Artists", which can be placed in any region of the site.
 * The "Artist" content type contains the following fields: ID, Name, Genre, Image, and Link.
 * Creating the Artist you should have the ID, e.g., Dua Lipa's id is "6M2wZ9GZgrQXHCFfjv46we". All other fields will be filled in automatically after saving the node.
 * This module makes an API call and stores artist information locally in the Drupal database to avoid performance issues and timeout errors. We can easily create a Cron job if we need to keep up-to-date information about the artist.
 * This artist page is only visible to logged-in users.


MAINTAINERS
-----------

Ivan Trokhanenko - https://www.drupal.org/u/i-trokhanenko
