/* POL js */
pnick = true;
whois_cache = new Array();

function print_whois(whois, wnick) {
var w = whois.split(":");
if (!whois) { $("#pnick").html("&dagger;"); } else {
if (w[6] == 1) { var wa = "<img src=\"/img/a/" + w[0] + ".jpg\" style=\"float:right;margin:0 -6px 0 0;\" />"; } else { var wa = ""; }
if (w[11] != 0) { var wc = "<img src=\"/img/cargos/" + w[11] + ".gif\" width=\"16\" /> "; } else { var wc = ""; }
$("#pnick").html(wc + "<b style=\"color:grey;\">" + wnick + " (<span class=\"" + w[9] + "\">" + w[9].substr(0,1).toUpperCase() + w[9].substr(1,w[9].length) + "</span> de " + w[10] + ")</b><br />" + wa + "Nivel: <b>" + w[3] + "</b><br />Nota: <b>" + w[4] + "</b><br />Partido: <b>" + w[7] + "</b><br />Foro: <b>" + w[8] + "</b><br /><br />Online: <b>" + w[5] + "</b><br />Ultimo acceso: <b>" + w[2] + "</b><br />Registrado hace: <b>" + w[1] + "</b>").css("display","inline");
}
}

$(document).ready(function(){
$(".bred").css("background-image", "url(/img/alerta_roja.gif)");
$(".bred").click(function(){
	var bg = $(this).css("background-image");
	if (bg != "none") { $(this).css("background-image", "none"); return false; }
});

$("dd:not(:eq(" + menu_ID + "))").hide();
$("dt a").click(function(){
	$("dd:visible").slideUp("normal");
	$(this).parent().next().slideDown("normal");
	return false;
});
$("#pnick").css("display","none").css("position","absolute");

$(".nick").mouseover(function(){
	var wnick = $(this).text();
	if (wnick == "GONZO") { $("#pnick").html("<b style=\"color:grey;\">Desarrollador</b>").css("display","inline");
	} else if (!whois_cache[wnick]) { pnick = setTimeout(function(){ $.post("/ajax.php", { a: "whois", nick: wnick }, function(data){ $("#pnick").css("display","none"); whois_cache[wnick] = data; print_whois(data, wnick); }); }, 500);
	} else { print_whois(whois_cache[wnick], wnick); }
}).mouseout(function(){ clearTimeout(pnick); pnick = false; $("#pnick").css("display","none"); });
$(document).mousemove(function(e){ $("#pnick").css({top: e.pageY + "px", left: e.pageX + 15 + "px"}); 
});
});
