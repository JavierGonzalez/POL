<?php
if(!isset($_GET['step'])){ header("Location: ?step=0"); }
include("common.php");

$theme->putfile("header");

switch($_GET['step']){
	case 0:
		if(isset($_POST['send']))
		{
			echo "Dentro";
			$_SESSION['install-domain']=$_POST['domain'];
			$_SESSION['install-dbname']=$_POST['dbname'];
			$_SESSION['install-dbuser']=$_POST['dbuser'];
			$_SESSION['install-dbhost']=$_POST['dbhost'];

			if(!isset($_SESSION['install-dbpass']) 
			|| ($_SESSION['install-dbpass'] != $_POST['dbpass'] 
			&& strlen($_POST['dbpass']) > 0 ) )
			{
				$_SESSION['install-dbpass']=$_POST['dbpass'];
			}
				
			if( strlen($_SESSION['install-domain']) > 0 &&
			strlen($_SESSION['install-dbname']) > 0 &&
			strlen($_SESSION['install-dbuser']) > 0 &&
			strlen($_SESSION['install-dbpass']) > 0 &&
			strlen($_SESSION['install-dbhost']) > 0 )
			{
				$link= @mysql_connect($_SESSION['install-dbhost'],
					$_SESSION['install-dbuser'],
					$_SESSION['install-dbpass']);	
				if( ! $link )
				{ 
					$theme->addvar("{ERROR}", mysql_error()); 
				}
				else
				{
					if(!mysql_select_db(
					$_SESSION['install-dbname'], 
					$link))
					{
						$theme->addvar("{ERROR}", mysql_error());
					}
					else
					{
						mysql_close($link);
						header("Location: ?step=1");
					}
				}
			}
		}

		$theme->addvar("{DOMINIO}",
			(isset($_SESSION['install-domain'])) ? 
			$_SESSION['install-domain'] : 
			$_SERVER['SERVER_NAME']);

		$theme->addvar("{DBNAME}",
			(isset($_SESSION['install-dbname'])) ? 
			$_SESSION['install-dbname'] : "");

		$theme->addvar("{DBUSER}",
			(isset($_SESSION['install-dbuser'])) ? 
			$_SESSION['install-dbuser'] : "");

		$theme->addvar("{DBHOST}",
			(isset($_SESSION['install-dbhost'])) ? 
			$_SESSION['install-dbhost'] : "localhost");

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
