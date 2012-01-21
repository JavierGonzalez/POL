<?php
include('inc-login.php');

if (!$_GET['q']) { $_GET['q'] = 'site:'.strtolower(PAIS).'.virtualpol.com '; } 

$txt .= '

<p style="color:#777;">Buscador en fase de desarrollo, pero ya es funcional.</p>

<form action="/buscar/" id="cse-search-box">
<div> 
<input type="hidden" name="cx" value="000141954329957006250:h-_yuvq_rwk" />
<input type="hidden" name="cof" value="FORID:9" />
<input type="hidden" name="ie" value="UTF-8" />
<input type="text" name="q" id="iq" size="50" style="font-size:18px;" value="'.$_GET['q'].'" />
<input type="submit" name="sa" value="Buscar" />
</div>
</form>




<div id="cse-search-results"></div>
<script type="text/javascript">
  var googleSearchIframeName = "cse-search-results";
  var googleSearchFormName = "cse-search-box";
  var googleSearchFrameWidth = 600;
  var googleSearchDomain = "www.google.com";
  var googleSearchPath = "/cse";
</script>
<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>
';


//THEME
$txt_title = 'Buscar';
include('theme.php');
?>