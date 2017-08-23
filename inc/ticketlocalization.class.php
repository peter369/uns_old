<?php

class PluginUnsTicketLocalization extends CommonGLPI
{
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        switch ($item::getType()) {
            case Ticket::getType():
                return __('Localización', 'uns');
                break;
        }
        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
       
        

        $coordenadas1 = "geo_preparacion_i";
        $coordenadas2 = "geo_preparacion_f";
        $coordenadas3 = "geo_viaje_ida_i";
        $coordenadas4 = "geo_viaje_ida_f";
        $coordenadas5 = "geo_trabajo_i";
        $coordenadas6 = "geo_trabajo_f";
        $coordenadas7 = "geo_viaje_retorno_i";
        $coordenadas8 = "geo_viaje_retorno_f";

        $preparacion1 = "preparacion_i";
        $preparacion2 = "preparacion_f";
        $viaje1       = "viaje_ida_i";
        $viaje2       = "viaje_ida_f";
        $trabajo1     = "trabajo_i";
        $trabajo2     = "trabajo_f";
        $viajere1     = "viaje_retorno_i";
        $viajere2     = "viaje_retorno_f";

        $pausaI = "pausa_i";
        $pausaF = "pausa_f";

        //funciones de geolocalizacion
        $geo_preparacion   = self::showMap($item, $coordenadas1, $coordenadas2);
        $geo_viaje_ida     = self::showMap($item, $coordenadas3, $coordenadas4);
        $geo_trabajo       = self::showMap($item, $coordenadas5, $coordenadas6);
        $geo_viaje_retorno = self::showMap($item, $coordenadas7, $coordenadas8);

        //funciones de tiempos
        $preparacion_i   = self::showTime($item, $preparacion1);
        $preparacion_f   = self::showTime($item, $preparacion2);
        $viaje_ida_i     = self::showTime($item, $viaje1);
        $viaje_ida_f     = self::showTime($item, $viaje2);
        $trabajo_i       = self::showTime($item, $trabajo1);
        $trabajo_f       = self::showTime($item, $trabajo2);
        $viaje_retorno_i = self::showTime($item, $viajere1);
        $viaje_retorno_f = self::showTime($item, $viajere2);

        //tiempos de pausas
        $pausas_preparacion = self::pauseTime($item, $preparacion1, $preparacion2);
        $pausas_viaje_ida   = self::pauseTime($item, $viaje1, $viaje2);
        $pausas_trabajo     = self::pauseTime($item, $trabajo1, $trabajo2);
        $pausas_retorno     = self::pauseTime($item, $viajere1, $viajere2);

        //Tiempo total
        $t_preparacion   = self::secondsToTime(self::diffTime($preparacion_i, $preparacion_f, $pausas_preparacion));
        $t_viaje_ida     = self::secondsToTime(self::diffTime($viaje_ida_i, $viaje_ida_f, $pausas_viaje_ida));
        $t_trabajo       = self::secondsToTime(self::diffTime($trabajo_i, $trabajo_f, $pausas_trabajo));
        $t_viaje_retorno = self::secondsToTime(self::diffTime($viaje_retorno_i, $viaje_retorno_f, $pausas_retorno));

        $pausas_1 = self::secondsToTime($pausas_preparacion);
        $pausas_2 = self::secondsToTime($pausas_viaje_ida);
        $pausas_3 = self::secondsToTime($pausas_trabajo);
        $pausas_4 = self::secondsToTime($pausas_retorno);

        $total_trabajado = self::diffTime($preparacion_i, $preparacion_f, $pausas_preparacion) + self::diffTime($viaje_ida_i, $viaje_ida_f, $pausas_viaje_ida) + self::diffTime($trabajo_i, $trabajo_f, $pausas_trabajo) + self::diffTime($viaje_retorno_i, $viaje_retorno_f, $pausas_retorno);

        $total_pausas = self::pauseTime($item, $preparacion1, $preparacion2) + self::pauseTime($item, $viaje1, $viaje2) + self::pauseTime($item, $trabajo1, $trabajo2) + self::pauseTime($item, $viajere1, $viajere2);

        $tiempo_actual = date('Y-m-d H:i:s');

        if (is_null($viaje_retorno_f) || empty($viaje_retorno_f)) {
            $total_total = self::diffTime($preparacion_i, $tiempo_actual);
        } else {

            $total_total = self::diffTime($preparacion_i, $viaje_retorno_f);
        }

        $t_total_trabajado = self::secondsToTime($total_trabajado);
        $t_total_pausas    = self::secondsToTime($total_pausas);
        $t_total_total     = self::secondsToTime($total_total);

        $pauseRpreparacion = self::pauseReasons($item, $preparacion1, $preparacion2);
        $pauseRviajeida    = self::pauseReasons($item, $viaje1, $viaje2);
        $pauseRejecucion   = self::pauseReasons($item, $trabajo1, $trabajo2);
        $pauseRretorno     = self::pauseReasons($item, $viajere1, $viajere2);

        //prueba de tecnicos

        //self::GetTechnician($item);
        $listaTecnicos = self::GetTechnician($item);

        $test1=self::RecibirDatos($tecnico);

        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' type='text/css' href='../plugins/uns/css/tabs.css' media='screen' />
        <title>Document</title>
        
        
        <script type=\'text/javascript\'>
        $(document).ready(function(){
            $('#select_tecnicos').change(function(){
                var tecnico = document.getElementById('select_tecnicos').value;
                //alert('Selected value is : ' + tecnico);
        
                
                $.ajax({
                    type: 'POST',
                    url: '../plugins/uns/front/ticketlocalization.form.php',
                    
                    data: {var_tecnico2: tecnico},
                    success: function(result)
                    {
                       console.log('Enviado satisfactoriamente');
                       console.log('$test1');
                    }
                       ,
                       error:function(exception){alert('Exeption:'+exception);}
                       
                    
                }); 
             
            });
          });

        
        
        </script>

        <script type=\'text/javascript\'>
        var tabs = $('#tabs-titles li'); //grab tabs
        var contents = $('#tabs-contents li'); //grab contents

        tabs.bind('click',function(){
        contents.hide(); //hide all contents
        tabs.removeClass('current'); //remove 'current' classes
        $(contents[$(this).index()]).show(); //show tab content that matches tab title index
        $(this).addClass('current'); //add current class on clicked tab title
        });
        </script>

        </head>

        <body>
        
        
        <div style='text-align:left; margin-left: 30px;margin-bottom: 10px;'>
        <label>Técnico: <select id='select_tecnicos'>$listaTecnicos</select></label>
        </div>

        <ul id='tabs-titles'>
        <li class='current'> <!-- default (on page load), first one is currently displayed -->
        Preparación
        </li>
        <li>
        Viaje de ida
        </li>
        <li>
        Ejecución de trabajo
        </li>
        <li>
        Viaje de retorno
        </li>
        <li>
        Tiempo total
        </li>

        </ul>
        <ul id='tabs-contents'>
        <li>
        <div class='content'>
        <table style='width:100%'>
        <tr>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Pausas: <select>  $pauseRpreparacion</select></th>
        <th>Tiempo empleado</th>
        </tr>
        <tr>
        <td>$preparacion_i</td>
        <td>$preparacion_f</td>
        <td>$pausas_1</td>
        <td>$t_preparacion</td>
        </tr>
        </table>
        <p>$geo_preparacion</p>
        </div></div>
        </li>
        <li>
        <div class='content'><table style='width:100%'>
        <tr>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Pausas: <select>  $pauseRviajeida</select></th>
        <th>Tiempo empleado</th>
        </tr>
        <tr>
        <td>$viaje_ida_i</td>
        <td>$viaje_ida_f</td>
        <td>$pausas_2</td>
        <td>$t_viaje_ida</td>
        </tr>
        </table>
        <p>$geo_viaje_ida</p>
        </div></div>
        </li>
        <li>
        <div class='content'>
        <table style='width:100%'>
        <tr>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Pausas: <select>  $pauseRejecucion</select></th>
        <th>Tiempo empleado</th>
        </tr>
        <tr>
        <td>$trabajo_i</td>
        <td>$trabajo_f</td>
        <td>$pausas_3</td>
        <td>$t_trabajo</td>
        </tr>
        </table>
        <p>$geo_trabajo</p>
        </div>
        </div>
        </li>
        <li>
        <div class='content'>
        <table style='width:100%'>
        <tr>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Pausas: <select>  $pauseRretorno</select></th>
        <th>Tiempo empleado</th>
        </tr>
        <tr>
        <td>$viaje_retorno_i</td>
        <td>$viaje_retorno_f</td>
        <td>$pausas_4</td>
        <td>$t_viaje_retorno</td>
        </tr>
        </table>
        <p>$geo_viaje_retorno</p>
        </div>
        </div>
        </li>

        <li>
        <div class='content'>
        <table id='table-totales', style='width:100%'>
        <caption><center><h2>Informe de tiempo empleado</h2></caption>
            <tr>
                <th></th>
                <td><b>Inicio</td><td><b>Fin</td><td><b>Pausas</td><td><b>Total</td>
            </tr>
            <tr>
                <th><b>Preparación:</th>
                <td>$preparacion_i</td><td>$preparacion_f</td><td>$pausas_1</td><td>$t_preparacion</td>
            </tr>
            <tr>
                <th><b>Viaje de ida:</th>
                <td>$viaje_ida_i</td><td>$viaje_ida_f</td><td>$pausas_2</td><td>$t_viaje_ida</td>
            </tr>
            <tr>
                <th><b>Ejecución de trabajo:</th>
                <td>$trabajo_i</td><td>$trabajo_f</td><td>$pausas_3</td><td>$t_trabajo</td>
            </tr>
            <tr>
                <th><b>Viaje de retorno:</th>
                <td>$viaje_retorno_i</td><td>$viaje_retorno_f</td><td>$pausas_4</td><td>$t_viaje_retorno</td>
            </tr>




            <tr>
                <th><b>Tiempo total pausas:</th>
                <td></td><td></td><td><b>$t_total_pausas</td><td></td>
            </tr>
            <tr>
                <th><b>Tiempo real trabajado:</th>
                <td></td><td></td><td></td><td><b>$t_total_trabajado</td>
            </tr>
            <tr>
                <th><b>Tiempo total:</th>
                <td></td><td></td><td></td><td><b>$t_total_total</td>
            </tr>

        </table>

        </div>
        </div>
        </li>
        </ul>


        </body>
        </html>";

    }
    
    public static function RecibirDatos($tecnico)
    {
        
        return $tecnico;
    }

    public static function showMap(CommonGLPI $item, $coordenadasi, $coordenadasf)
    {
        global $DB;
        $tabla      = "glpi_plugin_uns_productiontickets";
        $ticket     = $item->getID();
        $sql1       = "SELECT $coordenadasi FROM $tabla WHERE tickets_id=$ticket";
        $ubicacioni = $DB->query($sql1);

        while ($row1 = $ubicacioni->fetch_assoc()) {
            $u1 = $row1[$coordenadasi];
        }

        $sql2       = "SELECT $coordenadasf FROM $tabla WHERE tickets_id=$ticket";
        $ubicacionf = $DB->query($sql2);

        while ($row2 = $ubicacionf->fetch_assoc()) {
            $u2 = $row2[$coordenadasf];
        }

        $mapa = "<iframe width='600' height='450' frameborder='0' style='border:0' src='https://www.google.com/maps/embed/v1/directions?key=AIzaSyDsxqHfVk-F5-2pHE3c1AEBD3Mkr_8MzZg&origin=$u1&destination=$u2'> </iframe>";

        $nodata = "<h3>No hay datos de localización</h3> <br><br><br>";

        if (empty($u1) || is_null($u1) || empty($u2) || is_null($u2)) {

            return $nodata;

        } else {

            return $mapa;
        }

    }
    public static function showTime(CommonGLPI $item, $tiempo)
    {
        global $DB;
        $tabla = "glpi_plugin_uns_productiontickets";
        //sacar ID del ticket
        $ticket = $item->getID();
        //Consulta SQL
        $sql1 = "SELECT $tiempo FROM $tabla WHERE tickets_id=$ticket";
        $quer = $DB->query($sql1);

        while ($row1 = $quer->fetch_assoc()) {
            $t = $row1[$tiempo];
        }
        return $t; //en formato de tiempo
    }
    public static function diffTime($time1, $time2, $pausas)
    {

        if (is_null($time1) || empty($time1)) {
            $total = 0;
            return $total; //botas segundos

        } elseif (is_null($time1) || empty($time1)) {
            $time2 = date('Y-m-d H:i:s');
            $diff  = strtotime($time2) - strtotime($time1);
            $total = $diff - $pausas;
            return $total; //botas segundos

        } else {
            $diff  = strtotime($time2) - strtotime($time1);
            $total = $diff - $pausas;
            return $total; //botas segundos
            echo "no estoy en null";
        }
    }

    public static function secondsToTime($seconds)
    {

        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a días %h horas %i min %s sec');

    }

    public static function pauseTime(CommonGLPI $item, $tiempo_i, $tiempo_f)
    {

        global $DB;
        $ticket = $item->getID();
        $sql    = "SELECT pausa_i, pausa_f
                FROM glpi_plugin_uns_pausetickets T1
                INNER JOIN glpi_plugin_uns_productiontickets T2 ON T1.tickets_id = T2.tickets_id
                WHERE T1.tickets_id = $ticket AND (pausa_i BETWEEN T2.$tiempo_i AND T2.$tiempo_f)"; //tener en cuenta la validación se esta haciendo unicamente con la pausa inicial

        $result = $DB->query($sql);
        while ($row = $DB->fetch_assoc($result)) {
            $pausa_i[] = $row['pausa_i'];
            $pausa_f[] = $row['pausa_f'];

        }

        $long = count($pausa_i);

        if (is_null($pausa_i) || empty($pausa_i) || is_null($pausa_f) || empty($pausa_f)) {
            $suma = 0;
            return $suma;

        } else {

            $suma = 0;
            for ($i = 0; $i < $long; $i++) {
                $diff = strtotime($pausa_f[$i]) - strtotime($pausa_i[$i]);
                $suma += $diff;

            }
            return $suma;

        }
    }

    public static function pauseReasons(CommonGLPI $item, $tiempo_i, $tiempo_f)
    {
        global $DB;
        $ticket = $item->getID();
        $sql    = "SELECT motivo
                FROM glpi_plugin_uns_pausetickets T1
                INNER JOIN glpi_plugin_uns_productiontickets T2 ON T1.tickets_id = T2.tickets_id
                WHERE T1.tickets_id = $ticket AND (pausa_i BETWEEN T2.$tiempo_i AND T2.$tiempo_f)"; //tener en cuenta la validación se esta haciendo unicamente con la pausa inicial

        $result = $DB->query($sql);
        while ($row = $DB->fetch_assoc($result)) {
            $motivo[] = $row['motivo'];

        }

        //echo "<select>";
        for ($i = 0; $i < count($motivo); $i++) {
            $str .= "<option value='$motivo[$i]'>$motivo[$i]</option>";
            //echo "<option value='$motivo[$i]'>$motivo[$i]</option>";
        }
        //echo "</select>";

        return $str;

    }
    public static function GetTechnician(CommonGLPI $item)
    {
        global $DB;
        $ticket = $item->getID();

        $sql="SELECT *
        FROM glpi_plugin_uns_productiontickets T1
        INNER JOIN glpi_users T2 ON T1.user_id = t2.id
        WHERE tickets_id=$ticket";
        
                $result = $DB->query($sql);
                while ($row = $DB->fetch_assoc($result)) {
                    $nombre[]    = $row['firstname'];
                    $apellido[]  = $row['realname'];
                }

       // return $nombre[] + $apellido[];

       //for para recorrer el array de tecnicos y mostrarlo como opciones de un select

       for ($i = 0; $i < count($nombre); $i++) {
        $str .= "<option value='$nombre[$i] $apellido[$i]'>$nombre[$i] $apellido[$i]</option>";
        //echo "<option value='$motivo[$i]'>$motivo[$i]</option>";
        }
        return $str;
    //    echo "$nombre[1] $apellido[1]";
    //    echo "$ticket";
    }

}
