<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 
$parsedown_articulos = new Parsedown();
$parsedown_articulos->setSafeMode(true);
$parsedown_articulos->setBreaksEnabled(true);

$txt_title = 'Articulos ';

$txt_tab['/empresas/articulos/'.$_GET[1]] = _('Artículos');

$result = mysql_query_old("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time,
(SELECT nombre FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_nom,
(SELECT url FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_url,
(SELECT nick FROM users WHERE ID = empresas.user_ID LIMIT 1) AS nick
FROM empresas
WHERE pais = '".PAIS."' AND ID = '".$_GET[1]."' 
AND user_ID = '".$pol['user_ID']."'
LIMIT 1", $link);
if($r = mysqli_fetch_array($result)) {
    if ($r['user_ID'] == $pol['user_ID']) {  
        $txt_tab['/empresas/suscriptores/'.$r['ID']] = _('Suscriptores');
    }
    $txt_title = _('Empresa').': ' . $r['nombre'] . ' - '._('Sector').': ' . $r['cat_nom'];
    $txt_nav = array('/empresas'=>_('Empresas'), '/empresas/'.$r['url']=>$r['cat_nom'], $r['nombre'], _('Artículos'));
}

if ($_GET[2] == "ver") {

    

    $comprobacion_suscriptor = mysql_query_old("SELECT ID
    FROM empresas_suscriptores
    WHERE id_empresa = '".$_GET[1]."' AND ID_usuario = '".$pol['user_ID']."'
    ORDER BY ID DESC", $link);

    $suscriptor = "false";
    if ($result_suscriptor = mysqli_fetch_array($comprobacion_suscriptor)){
        $suscriptor = "true";
    }

    $comprobacion_comprado = mysql_query_old("SELECT ID
    FROM articulo_usuario
    WHERE id_articulo = '".$_GET[3]."' AND ID_usuario = '".$pol['user_ID']."'
    ORDER BY ID DESC", $link);

    $comprado = "false";
    if ($result_comprado = mysqli_fetch_array($comprobacion_comprado)){
        $comprado = "true";
    } 

    $articulos = mysql_query_old("SELECT ID, publico, ID_usuario, ID_empresa, contenido, titulo, adelanto, precio_suscriptores, precio, valoracion, valoracion_n_votos, fecha_creacion
    FROM articulo
    WHERE ID = '".$_GET[3]."'
    ORDER BY ID DESC", $link);
    while($articulo = mysqli_fetch_array($articulos)) {
        $precio = $articulo['precio'];
        if ($suscriptor == "true"){
            $precio = $articulo['precio_suscriptores'];
        }

        if ($comprado == "true" OR $articulo["ID_usuario"] == $pol['user_ID']){
            echo '<h1>'.$articulo['titulo'].'</h1>
            <br /><div class="amarillo">'.$parsedown_articulos->text($articulo['contenido']).'</div>';
            if ($articulo["ID_usuario"] == $pol['user_ID']){
                echo '<br /> Use el siguiente código en el foro para compartir el artículo: [articulo]/empresas/articulos/'.$_GET[1].'/ver/'.$_GET[3].'[/articulo]';
             }
 
        }else{
            echo '<h1>Debes comprar el artículo para verlo al completo</h1> 
            <form action="/accion/empresa/comprar-articulo?ID='.$articulo['ID'].'" name="comprar-articulo" method="POST">
            <input type="hidden" value="'.$_GET[1].'" name="ID_empresa">
        '. boton('Comprar', false, false, '', $precio, false, 'large').
        '</form>
            <h1>'.$articulo['titulo'].'</h1>
            <br /><div class="amarillo">'.$parsedown_articulos->text($articulo['adelanto']).'</div>';

        }
     }

     if ($_GET['embed'] == 'true')
        exit;

}elseif ($_GET[2] == "nuevo-articulo"){
    echo '<form action="/accion/empresa/nuevo-articulo?ID_empresa='.$_GET[1].'" name="nuevo_articulo" id="nuevo_articulo" method="POST">
            <script>
                function validarTitulo(){
                    var titulo = $("#title").val();
                    if (titulo.includes("<") || titulo.includes(">") || titulo.includes("/") ){
                        $("#title")[0].setCustomValidity("El campo titulo contiene caracteres inválidos (<, > o /), por favor reviselo y pulse enviar");
                        $("#title")[0].checkValidity();
                        $("#title")[0].reportValidity();
                        return false;
                    }else{
                        $("#title")[0].setCustomValidity("");
                        return true;
                    }

                    
                }
            </script>
            <ul style="list-style: none;">
                <input type="hidden" id="html_doc" name="html_doc">
                <input type="hidden" id="adelanto_doc" name="adelanto_doc">
                <li><span>Titulo: </span><input type="text" name="titulo" id="title" value="" size="40" maxlength="50" style="font-size:22px;" /> &nbsp; 
                <span>Precio: </span><input type="text" name="precio" id="precio" value="" size="8" maxlength="50" style="font-size:22px;" /> &nbsp; 
                <span>Precio con suscripcion: </span><input type="text" name="precio_suscripcion" id="precio" value="" size="8" maxlength="50" style="font-size:22px;" /> &nbsp; </li>
                <span>Notificar suscriptores: </span><input type="checkbox" name="notificar" id="notificar"/> &nbsp; </li>
                 <li><span>Adelanto: </span>
                 <textarea id="adelanto_body"></textarea>
                 <script type="text/javascript" src="/img/lib/jquery-1.7.1.min.js"></script>
		
                 <script src="/img/easymde.min.js"></script>
                 <link rel="stylesheet" href="/img/easymde.min.css">
                          <script>
                     var easyMDE = new EasyMDE({element: document.getElementById(\'adelanto_body\'),
                                               spellChecker: false,
                                               autosave: {
                                                 enabled: false
                                                },
                                               minHeight: "250px"});
                    easyMDE.value($(\'#adelanto_doc\').val());
                    easyMDE.codemirror.on("change", function(){
                        $(\'#adelanto_doc\').val(easyMDE.value());
                    });
                 </script>

                </li>
                <li><span>Contenido: </span><iframe style="width:100%;height:850px;scrolling: none; border: none" id="contenido_doc" src="/doc/editor_markdown.php">
                    </iframe></li>
                <li><button onclick="return validarTitulo();" class="large blue">Publicar</button></li>
            </ul>
            </form>
        ';
    }elseif ($_GET[2] == "editar"){

        $articulos = mysql_query_old("SELECT ID, publico, ID_usuario, ID_empresa, contenido, titulo, adelanto, precio_suscriptores, precio, valoracion, valoracion_n_votos, fecha_creacion
        FROM articulo
        WHERE ID='".$_GET[3]."' LIMIT 1", $link);

        if($articulo = mysqli_fetch_array($articulos)) {

            echo '<form action="/accion/empresa/editar-articulo?ID_empresa='.$_GET[1].'" name="nuevo_articulo" id="nuevo_articulo" method="POST">
                    <script>
                        function validarTitulo(){
                            var titulo = $("#title").val();
                            if (titulo.includes("<") || titulo.includes(">") || titulo.includes("/") ){
                                $("#title")[0].setCustomValidity("El campo titulo contiene caracteres inválidos (<, > o /), por favor reviselo y pulse enviar");
                                $("#title")[0].checkValidity();
                                $("#title")[0].reportValidity();
                                return false;
                            }else{
                                $("#title")[0].setCustomValidity("");
                                return true;
                            }
        
                            
                        }
                    </script>
                    <ul style="list-style: none;">
                    <input type="hidden" id="ID" name="ID" value="'.$_GET[3].'">
                    <input type="hidden" id="html_doc" name="html_doc" value="'.$articulo['contenido'].'">
                    <input type="hidden" id="adelanto_doc" name="adelanto_doc"  value="'.$articulo['adelanto'].'">
                        <li><span>Titulo: </span><input type="text" name="titulo" id="title" value="'.$articulo['titulo'].'" size="40" maxlength="50" style="font-size:22px;" /> &nbsp; 
                        <span>Precio: </span><input type="text" name="precio" id="precio" value="'.$articulo['precio'].'" size="8" maxlength="50" style="font-size:22px;" /> &nbsp; 
                        <span>Precio con suscripcion: </span><input type="text" name="precio_suscripcion" id="precio_suscriptores" value="'.$articulo['precio_suscriptores'].'" size="8" maxlength="50" style="font-size:22px;" /> &nbsp; </li>
                        <span>Notificar suscriptores: </span><input type="checkbox" name="notificar" id="notificar"/> &nbsp; </li>
                        <li><span>Adelanto: </span>
                        <textarea id="adelanto_body"></textarea>
                        <script type="text/javascript" src="/img/lib/jquery-1.7.1.min.js"></script>
                
                        <script src="/img/easymde.min.js"></script>
                        <link rel="stylesheet" href="/img/easymde.min.css">
                                <script>
                            var easyMDE = new EasyMDE({element: document.getElementById(\'adelanto_body\'),
                                                    spellChecker: false,
                                                    autosave: {
                                                        enabled: false
                                                        },
                                                    minHeight: "250px"});
                            easyMDE.value($(\'#adelanto_doc\').val());
                            easyMDE.codemirror.on("change", function(){
                                $(\'#adelanto_doc\').val(easyMDE.value());
                            });
                        </script>
        
                        </li>
                        <li><span>Contenido: </span><iframe style="width:100%;height:850px;scrolling: none; border: none" id="contenido_doc" src="/doc/editor_markdown.php">
                            </iframe></li>
                        <li><button onclick="return validarTitulo();" class="large blue">Publicar</button></li>
                    </ul>
                    </form>
                ';
            }
                
}else{
    $comprobacion_suscriptor = mysql_query_old("SELECT ID
    FROM empresas_suscriptores
    WHERE id_empresa = '".$_GET[1]."' AND ID_usuario = '".$pol['user_ID']."'
    ORDER BY ID DESC", $link);

    $suscriptor = false;
    if ($result_suscriptor = mysqli_fetch_array($comprobacion_suscriptor)){
        $suscriptor = true;
    }

    $articulos = mysql_query_old("SELECT ID, publico, ID_usuario, ID_empresa, contenido, titulo, adelanto, precio_suscriptores, precio, valoracion, valoracion_n_votos, fecha_creacion
    FROM articulo
    WHERE id_empresa = '".$_GET[1]."'
    ORDER BY ID DESC", $link);

    while($articulo = mysqli_fetch_array($articulos)) {

        $comprobacion_comprado = mysql_query_old("SELECT ID
        FROM articulo_usuario
        WHERE id_articulo = '".$articulo['ID']."' AND ID_usuario = '".$pol['user_ID']."'
        ORDER BY ID DESC", $link);
    
        $comprado = "false";
        if ($result_comprado = mysqli_fetch_array($comprobacion_comprado)){
            $comprado = "true";
        }        

        $precio = $articulo['precio'];
        if ($suscriptor == "true"){
            $precio = $articulo['precio_suscriptores'];
        }

        $txt_table .= '<tr class="amarillo">
        <td colspan="3"><h2><a href="/empresas/articulos/'.$_GET[1].'/ver/'.$articulo['ID'].'" style="font-size:22px;margin-left:8px;"><b>'.$articulo['titulo'].'</b></a></h2></td>

        <td width="20%">';
        if ($r['user_ID'] == $pol['user_ID']){
            $txt_table .= boton('Editar artículo', '/empresas/articulos/'.$_GET[1].'/editar/'.$articulo['ID'], false, '', false, false, 'large');
        }else{
            $txt_table .= '<form action="/accion/empresa/comprar-articulo?ID='.$articulo['ID'].'" name="comprar-articulo" method="POST">
            <input type="hidden" value="'.$_GET[1].'" name="ID_empresa">
            ';
            if ($comprado == "true"){
                $txt_table .= boton('Comprar de nuevo', false,  '&iquest;Seguro que quieres comprar de nuevo este artículo?', false, $precio, false, 'large');
                $txt_table .= boton('Ver artículo', '/empresas/articulos/'.$_GET[1].'/ver/'.$articulo['ID'], false, '', '', false, 'blue');
            }else{
                $txt_table .= boton('Comprar', false, false, '', $precio, false, 'large');
            }
            '</form>';
        }

        $txt_table .= '</td> </tr>';
            

        $txt_table .= '<tr><td colspan="4"><span>'.$parsedown_articulos->text($articulo['adelanto']).'</span></td></tr>';

        $txt_table .= '<tr><td colspan="4">&nbsp;</td></tr>';

    }
    if ($r['user_ID'] == $pol['user_ID']){
        echo '
        <div style="float: right"><button onclick="window.location.href=\''.$_GET[1].'/nuevo-articulo\'" class="large blue">Nuevo artículo</button></div>
        <br />';
    }
    echo '<table border="0" cellpadding="1" cellspacing="0">

    '.$txt_table.'

    </table>';
}
