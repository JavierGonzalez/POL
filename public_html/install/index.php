<?php
if(!isset($_GET['step'])){ header("Location: ?step=0"); }
include("common.php");

$theme->putfile("header");

switch($_GET['step']){
	case 0:
		if(isset($_POST['send']))
		{
			$_SESSION['i_domain']=$_POST['domain'];
			$_SESSION['i_ctmail']=$_POST['ctmail'];
			$_SESSION['i_dbname']=$_POST['dbname'];
			$_SESSION['i_dbuser']=$_POST['dbuser'];
			$_SESSION['i_dbhost']=$_POST['dbhost'];

			if(!isset($_SESSION['i_dbpass']) 
			|| ($_SESSION['i_dbpass'] != $_POST['dbpass'] 
			&& strlen($_POST['dbpass']) > 0 ) )
			{	//Mantenemos la pass guardada
				//Pero permitimos que se pueda
				//Cambiar
				$_SESSION['i_dbpass']=$_POST['dbpass'];
			}
			
			//si todos los campos del formulario
			//se han introducido	
			if( strlen($_SESSION['i_domain']) > 0 &&
			strlen($_SESSION['i_ctmail']) > 0 &&
			strlen($_SESSION['i_dbname']) > 0 &&
			strlen($_SESSION['i_dbuser']) > 0 &&
			strlen($_SESSION['i_dbpass']) > 0 &&
			strlen($_SESSION['i_dbhost']) > 0 )
			{
				
				$link= @mysql_connect($_SESSION['i_dbhost'],
					$_SESSION['i_dbuser'],
					$_SESSION['i_dbpass']);	
				if( ! $link )
				{ 
					$theme->addvar("{ERROR}", mysql_error()); 
				}
				elseif(!mysql_select_db(
				$_SESSION['i_dbname'], 
				$link))
				{
					$theme->addvar("{ERROR}", mysql_error());
				}
				else
				{
					mysql_close($link);
					$conf_pwd=file_get_contents("../config-pwd-sample.php");
					$conf_pwd=str_replace(
						array(
							'$mysql_host = \'...\';',
							'$mysql_db = \'...\';',
							'$mysql_user = \'...\';',
							'$mysql_pass = \'...\';'
						),
						array(
							'$mysql_host = \''.$_SESSION["i_dbhost"].'\';',
							'$mysql_db = \''.$_SESSION["i_dbname"].'\';',
							'$mysql_user = \''.$_SESSION["i_dbuser"].'\';',
							'$mysql_pass = \''.$_SESSION["i_dbpass"].'\';'
						), 
						$conf_pwd);
					if(file_put_contents("../config-pwd.php",$conf_pwd) === FALSE)
					{
						$theme->addvar(
							"{ERROR}", "No se pudo escribir en config-pwd.php,".
							" compruebe los permisos.
						");
					}
					else
					{
						//aqui escribimos config.php
						$conf = file_get_contents("../config-sample.php");
						$conf = str_replace(
								array(
									'define(\'DOMAIN\', \'virtualpol.com\');',
									'define(\'DOMAIN\', \'\');',
									'define(\'CONTACTO_EMAIL\', \'desarrollo@virtualpol.com\');'
								),
								array(
									'define(\'DOMAIN\', \''.$_SESSION["i_domain"].'\');',
									'define(\'DOMAIN\', \''.$_SESSION["i_domain"].'\');',
									'define(\'CONTACTO_EMAIL\', \''.$_SESSION["i_ctmail"].'\');'
								),
								$conf);
						if(file_put_contents("../config.php",$conf) === FALSE)
						{
							$theme->addvar(
								"{ERROR}", "No se pudo escribir en config.php, compruebe".
								" los permisos."
							);
						}
						else
						{
							//comprobamos que todo se ha configurado correctamente
							include("../config-pwd.php");
							$link = conectar(true);
							if( !$link )
							{
								$theme->addvar(
									"{ERROR}", 
									"Error: Parece que los valores de conexi&oacute;n".
									" no se han escrito correctamente ".mysql_error()
								);
							}
							else
							{
								mysql_close($link);
								header("Location: ?step=1");
							}
						}
					}
				}
				
			}else{ //Algun dato del formulario no se ha introducido
				$theme->addvar("{ERROR}", "Todos los campos son obligatorios");
			}
		}

		$theme->addvar("{DOMINIO}",
			(isset($_SESSION['i_domain'])) ? 
			$_SESSION['i_domain'] : 
			$_SERVER['SERVER_NAME']);

		$theme->addvar("{CTMAIL}",
			(isset($_SESSION['i_ctmail'])) ?
			$_SESSION['i_ctmail'] :
			"desarrollo@virtualpol.com");

		$theme->addvar("{DBNAME}",
			(isset($_SESSION['i_dbname'])) ? 
			$_SESSION['i_dbname'] : "");

		$theme->addvar("{DBUSER}",
			(isset($_SESSION['i_dbuser'])) ? 
			$_SESSION['i_dbuser'] : "");

		$theme->addvar("{DBHOST}",
			(isset($_SESSION['i_dbhost'])) ? 
			$_SESSION['i_dbhost'] : "localhost");


		$theme->addvar("{DBPASS}","");

		$theme->addvar("{TITLE}","step0");
		$theme->putfile("step0");
		break;
		

	case 1:

		include("../config-pwd.php");
		$link = conectar(true);
		if( !$link ){
			$theme->addvar(
				"{ERROR}", "- Error: Parece que los valores de conexi&oacute;n".
				" no se han escrito correctamente ".mysql_error()
			);
		}else{
			if(isset($_POST['send'])){
				$incidencias=0; //control de incidencias
				$tablasok=0; //control de tablas instaladas correctamente 0 = Todas OK
				//las tablas a comprobar
				$vp_tables = array
						(	
							"15m_foros",
							"15m_foros_hilos",
							"15m_foros_msg",
							"atlantis_foros",
							"atlantis_foros_hilos",
							"atlantis_foros_msg",
							"cargos",
							"cargos_users",
							"cat",
							"chats",
							"chats_msg",
							"config",
							"cuentas",
							"docs",
							"empresas",
							"empresas_acciones",
							"examenes",
							"examenes_preg",
							"expulsiones",
							"grupos",
							"hechos",
							"hispania_foros",
							"hispania_foros_hilos",
							"hispania_foros_msg",
							"kicks",
							"log",
							"mapa",
							"mensajes",
							"notificaciones",
							"partidos",
							"partidos_listas",
							"plataformas",
							"pol_foros",
							"pol_foros_hilos",
							"pol_foros_msg",
							"pujas",
							"referencias",
							"stats",
							"transacciones",
							"users",
							"votacion",
							"votacion_votos",
							"votos",
							"vp_foros",
							"vp_foros_hilos",
							"vp_foros_msg",
							"vulcan_foros",
							"vulcan_foros_hilos",
							"vulcan_foros_msg",
							"dry_foros",
							"dry_foros_hilos",
							"dry_foros_msg",
							"mic_foros",
							"mic_foros_hilos",
							"mic_foros_msg"

						);
				$runinfo.="<br /><strong>Eliminando tablas:</strong><br />";
				foreach( $vp_tables as $vp_table ){
					$runinfo.="- Eliminando $vp_table <br />";
					if( ! mysql_query("drop table if exists $vp_table", $link))
					{ 
						$incidencias++;
						$theme->concvar(
							"{ERROR}", "- Error Eliminando tabla".
							" $vp_table: ".mysql_error()."<br />"
						);
					}

				}
				$runinfo.="<br /><strong>Instalando tablas:</strong><br />";
				//aqui instalamos las tablas
				$db_file = preg_split("/;\s*[\r\n]+/", file_get_contents(DBPATH) );
				foreach($db_file as $query){
					if(strlen($query) > 0){
						if( ! mysql_query($query, $link))
						{ 
							$incidencias++;
							$theme->concvar(
								"{ERROR}", "- Incidencia volcando DB: ".
								mysql_error()."<br />"
							);
						}
					}
				}
				$runinfo.="Incidencias: $incidencias<br />";
				$runinfo.="<br /><strong>Comprobando tablas:</strong><br />";
				foreach($vp_tables as $vp_table){
					$runinfo.="- Comprobando ".$vp_table;
					$result = mysql_query("SELECT count(table_name) as cantidad 
								FROM INFORMATION_SCHEMA.TABLES WHERE 
								TABLE_SCHEMA = '".$_SESSION["i_dbname"]."' AND 
								TABLE_NAME = '".$vp_table."'", $link);
		                        $r = mysql_fetch_array($result);
		                        if( $r['cantidad'] > 0 )
					{
						$runinfo.=" OK<br />";
					}
					else
					{
						$tablasok++;
						$incidencias++;
						$theme->concvar(
							"{ERROR}", "- Error Verificando tablas.".
							" La tabla $vp_table No Existe<br />"
						);
						$runinfo.=" FAIL<br />";
					}
				}
				$vp_tables=""; //liberando ram

				$theme->addvar("{RUNINFO}",$runinfo);


				if($tablasok > 0){
					$theme->addvar("{INCIDENCIAS}", "Error: $tablasok tablas no fueron instaladas. ");
				}


				if($tablasok > 0 || $incidencias > 0){
					$theme->concvar(
						"{INCIDENCIAS}",
						"Se encontraron algunas incidencias ($incidencias)".
						" instalando la base de datos. Puede continuar con".
						" la instalaci&oacute;n pero no se asegura el correcto".
						" funcionamiento del sistema."
					);
				}	

				$theme->incfile("{SIGUIENTE}","nextstep2");

			}else{
				$result = mysql_query("SELECT count(table_name) as cantidad 
							FROM INFORMATION_SCHEMA.TABLES WHERE 
							TABLE_SCHEMA = '".$_SESSION["i_dbname"]."'", $link);
				$r = mysql_fetch_array($result);
				if( $r['cantidad'] > 0 ){
					$theme->addvar(
						"{ERROR}", 
						"Parece que su base de datos contiene algunas".
						" tablas. Si continua, las tablas de VirtualPol".
						" ser&aacute;n reseteadas. Perder&aacute; todos".
						" los datos almacenados. &iexcl;Haga Backup!
					");
				}


			}
		}


		$theme->incfile("{INSTALLDB}","installdb"); //incluimos el archivo installdb.*** en {INSTALLDB}

		$theme->addvar("{TITLE}","step1"); 
		$theme->putfile("step1"); //insertamos el archivo step1
		break;

	case 2:
		$theme->addvar("{TITLE}","step2");
		$theme->putfile("step2");
		$theme->addvar(
				"{FINMSG}", 
				"Si se han completado correctamente todos los pasos anteriores,".
				" es hora de realizar algunos ajustes:<br />".
				"1) Configure los subdominios adecuados para las plataformas: ".
				"hispania, 15m, mic<br />".
				"2) Configure 'cron' para ejecutar los guiones dentro de source/cron/<br />".
				"3) Si la instalaci&oacute;n fue correcta, elimine el directorio install/".
				" de instalaci&oacute;n para evitar problemas de seguridad. Es en serio, ".
				"elimina el directorio o podr&iacute;as lamentarlo." 
		);
		unset($_SESSION['i_domain']);
		unset($_SESSION['i_ctmail']);
		unset($_SESSION['i_dbname']);
		unset($_SESSION['i_dbuser']);
		unset($_SESSION['i_dbhost']);
		unset($_SESSION['i_dbpass']);
	
		break;
	default:
		header("Location: ?step=0");
}

$theme->putfile("footer"); //insertamos el archivo de pie de pagina
echo $theme->return_html(); //procesamos y mostramos el html


?>
