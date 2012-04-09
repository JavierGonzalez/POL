<?php
if(!isset($_GET['step'])){ header("Location: ?step=0"); }
include("common.php");

$theme->putfile("header");

switch($_GET['step']){
	case 0:
		if(isset($_POST['send']))
		{
			$_SESSION['i_domain']=$_POST['domain'];
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
					if(file_put_contents("../config-pwd.php",$conf_pwd) === FALSE){
						$theme->addvar("{ERROR}", "No se pudo escribir en config-pwd.php, compruebe los permisos.");
					}else{
						header("Location: ?step=1");
					}
				}
				
			}
		}

		$theme->addvar("{DOMINIO}",
			(isset($_SESSION['i_domain'])) ? 
			$_SESSION['i_domain'] : 
			$_SERVER['SERVER_NAME']);

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
		$theme->putfile("step1");
		break;
	default:
		header("Location: ?step=0");
}

$theme->putfile("footer");
echo $theme->return_html();


?>
