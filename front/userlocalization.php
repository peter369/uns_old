<?php

include "../../../inc/includes.php";

Html::header(
    __('Geolocalización', 'uns'),
    $_SERVER["PHP_SELF"],
    'tools',
    "PluginUnsUserLocalization");

//Search::show('PluginUnsUserLocalization');

PluginUnsUserLocalization::displayContent();

Html::footer();
