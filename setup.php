<?php

define('UNS_VERSION', '1.2.10');

function plugin_init_uns()
{
    global $PLUGIN_HOOKS;

    //required!
    $PLUGIN_HOOKS['csrf_compliant']['uns'] = true;

    //some code here, like call to Plugin::registerClass(), populating PLUGIN_HOOKS, ...

    //Plugin::registerClass('PluginLocalization', array('addtabon' => array('Computer')));
    $PLUGIN_HOOKS['menu_toadd']['Mapa'] = array('tools' => 'PluginUnsUserLocalization',
    );
    //$PLUGIN_HOOKS['config_page']['Mapa'] = 'front/alert.php';

    Plugin::registerClass(
        'PluginUnsOtCost', [
            'addtabon' => [
                'Ticket',
            ],
        ]
    );

    Plugin::registerClass(
        'PluginUnsTicketLocalization', [
            'addtabon' => [
                'Ticket',
            ],
        ]
    );

}

/**
 * Get the name and the version of the plugin - Needed
 */
function plugin_version_uns()
{
    return array('name' => "UNS Plugin",
        'version'           => '1.0.0',
        'author'            => 'Peter Cabrera',
        'license'           => 'GPLv2+',
        'homepage'          => '',
        'minGlpiVersion'    => '0.85');
}

/**
 * Check configuration process for plugin : need to return true if succeeded
 * Can display a message only if failure and $verbose is true
 *
 * @param boolean $verbose Enable verbosity. Default to false
 *
 * @return boolean
 */
function plugin_uns_check_config($verbose = false)
{
    if (true) {
        // Your configuration check
        return true;
    }

    if ($verbose) {
        echo "Installed, but not configured";
    }
    return false;
}

/**
 * Optional : check prerequisites before install : may print errors or add to message after redirect
 *
 * @return boolean
 */
function plugin_uns_check_prerequisites()
{
    // Version check
    if (version_compare(GLPI_VERSION, '9.1', 'lt') || version_compare(GLPI_VERSION, '9.2', 'ge')) {
        if (method_exists('Plugin', 'messageIncompatible')) {
            //since GLPI 9.2
            Plugin::messageIncompatible('core', 9.1, 9.2);
        } else {
            echo "This plugin requires GLPI >= 9.1 and < 9.2";
        }
        return false;
    }
    return true;
}
