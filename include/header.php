<?php
/*function getHeader($currentCategory) {
    $categories = array(
        'ceramiche' => array('page' => 'ceramiche.php\'', 'name' => 'Ceramiche'),
        'depositi' => array('page' => 'depositi.php\'', 'name' => 'Depositi'),
        'raggruppati' => array('page' => 'raggruppati.php\'', 'name' => 'Raggruppati'),
        'marazzi' => array('page' => 'marazzi.php\'', 'name' => 'Marazzi'),
        'cestino' => array('page' => 'cestino.php\'', 'name' => 'Cestino'),
        'archivio' => array('page' => "archivio.php", 'name' => 'Archivio'),
        'gestione' => array('page' => 'gestione.php\'', 'name' => 'Gestione')
    );

    $width = intval(100 / count($categories));

    $header = "<table class='header' width=\"100%\"  border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#333333\">
    <tr valign=\"middle\">";


    foreach ($categories as $key => $category) {
        if(strcmp($key, $currentCategory) === 0) {
            $header .= "<td class='selected'";
        } else {
            $header .= "<td";
        }

        $header .= " width='" . $width . "%'>";
        $header .= "<a href='" . $category['page'] . "'>" . $category['name'] . "</a></td>";
    }

    $header .= "</tr></table>";

    return $header;
}*/

function getHeader($currentCategory) {
    $categories = array(
        'ceramiche' => array('page' => 'ceramiche.php', 'name' => 'Ceramiche', 'type' => 'link'),
        'depositi' => array('page' => 'depositi.php', 'name' => 'Depositi', 'type' => 'link'),
        'raggruppati' => array('page' => 'raggruppati.php', 'name' => 'Raggruppati', 'type' => 'link'),
        'marazzi' => array('page' => 'marazzi.php', 'name' => 'Marazzi', 'type' => 'link'),
        'cestino' => array('page' => 'cestino.php', 'name' => 'Cestino', 'type' => 'menu',
            'items' => array(
                array('page' => 'cestinoceramiche.php', 'name' => 'Ceramiche'),
                array('page' => 'cestinodepositi.php', 'name' => 'Depositi'),
                array('page' => 'cestinoraggruppati.php', 'name' => 'Raggruppati'),
                array('page' => 'cestinomarazzi.php', 'name' => 'Marazzi'),
            )
        ),
        'archivio' => array('page' => '#', 'name' => 'Archivio', 'type' => 'menu',
            'items' => array(
                array('page' => 'archivio.php?page=ceramiche', 'name' => 'Ceramiche'),
                array('page' => 'archivio.php?page=depositi', 'name' => 'Depositi'),
                array('page' => 'archivio.php?page=raggruppati', 'name' => 'Raggruppati'),
                array('page' => 'archivio.php?page=marazzi', 'name' => 'Marazzi'),
            )
        ),
        'gestione' => array('page' => 'gestione.php', 'name' => 'Gestione', 'type' => 'menu',
            'items' => array(
                array('page' => 'gestioneceramiche.php', 'name' => 'Ceramiche'),
                array('page' => 'gestionedepositi.php', 'name' => 'Depositi'),
                array('page' => 'gestionemarazzi.php', 'name' => 'Marazzi'),
                array('page' => 'gestioneclienti.php', 'name' => 'Clienti'),

            )
        )
    );

    $header = "<ul class='header'>";


    foreach ($categories as $key => $category) {
        if(strcmp($category['type'], 'link') == 0) {
            $header .= "<li";

            if(strcmp($key, $currentCategory) == 0) {
                $header .= " class=\"selected\"";
            }

            $header .= ">";
            $header .= a($category['page'], $category['name']);

        } else if(strcmp($category['type'], 'menu') == 0) {
            $header .= "<li class=\"dropdown";

            if(strcmp($key, $currentCategory) == 0) {
                $header .= " selected";
            }

            $header .= "\">";
            $header .= a($category['page'], $category['name']);
            $header .= "<div class=\"dropdown-content\">";

            foreach($category['items'] as $item) {
                $header .= a($item['page'], $item['name']);
            }

            $header .= "</div></li>";
        }
    }

    $header .= "</ul>";

    return $header;
}

function a($page, $name) {
    return "<a href=\"" . $page . "\">" . $name . "</a>";
}