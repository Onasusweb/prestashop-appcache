# Prestashop Appcache

  Generates a cache manifest file to make your Prestashop site faster

  Early development phase, nothing is ready!

## Installation

  Clone this repo in the `/modules` directory of your site. Then run `$ mv prestashop-appcache prestashopappcache`.

  Install the module in your site back office. You can then generate the manifest file in Advanced parameters > Application Cache. Sadly Prestashop does not provide a hook to trigger action after the performances settings are changed so you will have to generate it every time you change something here.

## Features

  * Makes your site faster by caching the CSS and JavaScript files, and the images.
  * Allows to add or ignore directories with extensions
  *	Allows the creation of a page to display to the user when they are offline
