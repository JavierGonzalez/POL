<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

if (!$_GET['q']) { $_GET['q'] = 'site:'.strtolower(PAIS).'.'.DOMAIN.' '; } 

$txt .= '

<p>
<form action="/buscar" id="cse-search-box">
<input type="hidden" name="cx" value="000141954329957006250:h-_yuvq_rwk" />
<input type="hidden" name="cof" value="FORID:9" />
<input type="hidden" name="ie" value="UTF-8" />
<input type="text" name="q" id="iq" size="60" style="font-size:18px;" value="'.$_GET['q'].'" />
<input type="submit" name="sa" value="'._('Buscar').'" />
</form>
</p>



<div id="cse-search-results"></div>
<script type="text/javascript">
  var googleSearchIframeName = "cse-search-results";
  var googleSearchFormName = "cse-search-box";
  var googleSearchFrameWidth = 600;
  var googleSearchDomain = "www.google.com";
  var googleSearchPath = "/cse";
</script>
<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>


<p style="color:#999;margin-top:50px;">'._('Buscador en fase de desarrollo').'.</p>
';


//THEME
$txt_title = _('Buscar');
$txt_menu = 'info';
$txt_nav = array(_('Buscar'));
include('theme.php');
?>