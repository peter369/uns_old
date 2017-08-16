<?php

class PluginUnsUserLocalization extends CommonGLPI
{

    public static function getMenuName()
    {
        return __("Geolocalización", "uns");

    }

    public static function displayContent()
    {

        $entidad = $_SESSION['glpiactive_entity'];

        $sql_ec = "SELECT *
                  FROM glpi_plugin_uns_users T1
                  INNER JOIN glpi_users T2 ON T1.name = T2.name
                  WHERE T2.entities_id=2";

        $sql_pe = "SELECT *
                  FROM glpi_plugin_uns_users T1
                  INNER JOIN glpi_users T2 ON T1.name = T2.name
                  WHERE T2.entities_id=4";

        $center_ec = "-1.335851, -78.519051";
        $center_pe = "-9.877032, -75.019011";

        $z_ec = 7;
        $z_pe = 6;

        echo "<div class='center'>";
        echo "<table class='tab_cadre_fixe'>";
        echo "<tr><th colspan='2'>" . __("Ubicación de técnicos", "uns") . "</th></tr>";
        echo "<tr class='tab_bg_1'><td>";
        switch ($entidad) {
            case '2':
                self::showMap($sql_ec, $center_ec, $z_ec);
                break;
            case '4':
                self::showMap($sql_pe, $center_pe, $z_pe);
                break;
            default:
                echo "No existe mapa para la entidad seleccionada.";
                break;
        }

        echo "</td>";
        echo "</tr>";
        echo "</table>";
        echo "</div>";

    }

    public static function numberOfTickets()
    {

    }

    public static function showMap($sql, $center, $z)
    {

        global $DB;

        $result = $DB->query($sql);
        while ($row = $DB->fetch_assoc($result)) {
            $nombre[]    = $row['firstname'];
            $apellido[]  = $row['realname'];
            $ubicacion[] = $row['geo_ultima_ubicacion'];
            $fecha[]     = $row['fecha_ultima_ubicacion'];
            $user[]      = $row['name'];

        }

//necesito obtener la cantidad de tickets por tecnico para esto necesito saber el nombre  del tecnico

                  $sql2 = "SELECT COUNT(tickets_id)
          FROM glpi_tickets_users T1
          INNER JOIN glpi_tickets T2 ON T1.tickets_id=T2.id
          INNER JOIN glpi_users T3 ON T1.users_id=T3.id
          WHERE T3.name=$user[0] AND T1.type=2 AND T2.is_deleted=0";

                  //print_r($art_array);
                  echo "<html>
          <head>

            <title>Google Maps Multiple Markers</title>
            <script src='http://maps.google.com/maps/api/js?key=AIzaSyDsxqHfVk-F5-2pHE3c1AEBD3Mkr_8MzZg' type='text/javascript'></script>
          </head>
          <body>
            <div id='map' style='height: 500px; width: 980px; margin:auto;'>
          </div>
          <script type='text/javascript'>
              var locations = [
                ['<h3>Bondi Beach', -33.890542, 151.274856, 4],
                ['<h3>Coogee Beach', -33.923036, 151.259052, 5],
                ['<h3>$nombre[2] $apellido[2]</h3><b>$fecha[2]', $ubicacion[2], 3],
                ['<h3>$nombre[1] $apellido[1]</h3><b>$fecha[1]', $ubicacion[1], 2],
                ['<h3>$nombre[0] $apellido[0]</h3>Aqui va el numero de tickets<br><b>$fecha[0]', $ubicacion[0], 1]
              ];

              var map = new google.maps.Map(document.getElementById('map'), {
                zoom: $z,
                center: new google.maps.LatLng($center),
                mapTypeId: google.maps.MapTypeId.ROADMAP
              });

              var infowindow = new google.maps.InfoWindow();

              var marker, i;


              for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                  position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                  map: map
                });

                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                  return function() {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                  }
                })(marker, i));
              }
            </script>
          </body>
          </html>";

    }

}
