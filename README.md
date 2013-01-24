# Prestashop Appcache

  Generates a cache manifest file to make your Prestashop site faster

  Early development phase, nothing is ready!

## Installation

  Clone this repo in the `/modules` directory of your site. Then run `mv prestashop-appcache prestashopappcache`.

  Install the module in your site back office. You can then generate the manifest file in Advanced parameters > Application Cache. Sadly Prestashop does not provide a hook to trigger action after the performances settings are changed so you will have to generate it every time you change something here.

## Features

  * Generates the manifest file to make the browser cache the CSS and Javascript files and images.
  * Automatically add the manifest attribute to the html tag and the necessary mime type to the htaccess
