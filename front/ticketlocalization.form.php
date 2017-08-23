

<?php

include "../../../inc/includes.php";

$datoTecnico = new PluginUnsTicketLocalization;

if(isset($_POST['var_tecnico']))
{
    $uid = $_POST['var_tecnico'];
    
    PluginUnsTicketLocalization::RecibirDatos($_POST['var_tecnico']);
    Html::back();
}
Html::displayErrorAndDie('Lost!');