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
			{
				$_SESSION['i_dbpass']=$_POST['dbpass'];
			}
				
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
						$theme->addvar("{ERROR}", "No se pudo escribir en config-pwd.php, compruebe los permisos.");
					}
					else
					{

						//aqui escribimos config.php
						$conf = file_get_contents("../config-sample.php");
						$conf = str_replace(
								array(
									'define(\'DOMAIN\', \'virtualpol.com\');',
									'define(\'CONTACTO_EMAIL\', \'desarrollo@virtualpol.com\');'
								),
								array(
									'define(\'DOMAIN\', \''.$_SESSION["domain"].'\');',
									'define(\'CONTACTO_EMAIL\', \''.$_SESSION["ctmail"].'\');'
								),
								$conf);
						if(file_put_contents("../config.php",$conf) === FALSE)
						{
							$theme->addvar("{ERROR}", "No se pudo escribir en config.php, compruebe los permisos.");
						}
						else
						{

							//comprobamos que todo se ha configurado correctamente
							include("../config-pwd.php");
							$link = conectar();
							if( !$link )
							{
								$theme->addvar("{ERROR}", "Error: Parece que los valores de conexi&oacute;n no se han escrito correctamente ".mysql_error());
							}
							else
							{
								mysql_close($link);
								header("Location: ?step=1");
							}
						}
					}
				}
				
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
		$link = conectar();
		if( !$link ){
			$theme->addvar("{ERROR}", "Error: Parece que los valores de conexi&oacute;n no se han escrito correctamente ".mysql_error());
		}else{

			$result = mysql_query("SELECT count(table_name) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".$_SESSION["i_dbname"]."'", $link);
			if( mysql_num_rows ($result) > 0 ){
				$theme->addvar("{ERROR}", "Parece que su base de datos contiene algunas tablas. Si continua, las tablas de VirtualPol ser&aacute;n reseteadas. Perder&aacute; todos los datos almacenados. Haga Backup!");
			}

			if(isset($_POST['send'])){
				
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
							"pol_foros",
							"pol_foros_hilos",
							"pol_foros_msg",
							"pujas",
							"referencias",
							"rssv_foros",
							"rssv_foros_hilos",
							"rssv_foros_msg",
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
							"vulcan_foros_msg"
						);

				foreach( $vp_tables as $vp_table ){
					$runinfo.="- Eliminando $vp_table <br />";
					if( ! mysql_query("drop table if exists $vp_table", $link)){ $runinfo.="********".mysql_error()."********<br />"; }

				}

				//aqui instalamos las tablas
				$vp_tables=""; //liberando ram


				$theme->addvar("{RUNINFO}",$runinfo);

			}
		}


		$theme->addvar("{TITLE}","step1");
		$theme->putfile("step1");
		break;
	default:
		header("Location: ?step=0");
}

$theme->putfile("footer");
echo $theme->return_html();


?>
