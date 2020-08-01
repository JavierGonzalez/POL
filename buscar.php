<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



if (!$_GET['q']) { $_GET['q'] = 'site:'.strtolower(PAIS).'.'.DOMAIN.' '; } 

echo '

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

?>