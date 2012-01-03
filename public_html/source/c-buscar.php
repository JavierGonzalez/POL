<?php
include('inc-login.php');

/* GOOGLE BUSQUEDA IFRAME
<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=es"></script>

*/

if (!$_GET['q']) { $_GET['q'] = 'site:'.PAIS.'.virtualpol.com '; } 

$txt .= '

<form action="/buscar/" id="cse-search-box">
<div> 
<input type="hidden" name="cx" value="000141954329957006250:h-_yuvq_rwk" />
<input type="hidden" name="cof" value="FORID:9" />
<input type="hidden" name="ie" value="UTF-8" />
<input type="text" name="q" size="50" style="font-size:18px;" value="'.$_GET['q'].'" />
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

/* GOOGLE BUSQUEDA AJAX
$txt .= '
<form action="" id="searchbox_000141954329957006250:h-_yuvq_rwk" onsubmit="return false;">
  <div>
    <input type="text" name="q" size="40"/>
    <input type="submit" value="Search"/>
  </div>
</form>
<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=searchbox_000141954329957006250%3Ah-_yuvq_rwk&lang=es"></script>

<div id="results_000141954329957006250:h-_yuvq_rwk" style="display:none">
  <div class="cse-closeResults"> 
    <a>&times; Cerrar</a>
  </div>
  <div class="cse-resultsContainer"></div>
</div>

<style type="text/css">
@import url(http://www.google.es/cse/api/overlay.css);
</style>

<script src="http://www.google.com/uds/api?file=uds.js&v=1.0&key=ABQIAAAA3x7avNobQn9IKOcVfx9_jhRjiXjbTPmz2rT4Y36F-28R3FDxwRSeIwW9f9lNvqz_hSuQcxltxHROoA&hl=es" type="text/javascript"></script>
<script src="http://www.google.es/cse/api/overlay.js" type="text/javascript"></script>
<script type="text/javascript">
function OnLoad() {
  new CSEOverlay("000141954329957006250:h-_yuvq_rwk",
                 document.getElementById("searchbox_000141954329957006250:h-_yuvq_rwk"),
                 document.getElementById("results_000141954329957006250:h-_yuvq_rwk"));
}
GSearch.setOnLoadCallback(OnLoad);
</script>
';
*/






//THEME
$txt_title = 'Busqueda';
include('theme.php');
?>