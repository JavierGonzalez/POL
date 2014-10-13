/* lib/kickstart/js/prettify.js */
var q=null;window.PR_SHOULD_USE_CONTINUATION=!0;
(function(){function L(a){function m(a){var f=a.charCodeAt(0);if(f!==92)return f;var b=a.charAt(1);return(f=r[b])?f:"0"<=b&&b<="7"?parseInt(a.substring(1),8):b==="u"||b==="x"?parseInt(a.substring(2),16):a.charCodeAt(1)}function e(a){if(a<32)return(a<16?"\\x0":"\\x")+a.toString(16);a=String.fromCharCode(a);if(a==="\\"||a==="-"||a==="["||a==="]")a="\\"+a;return a}function h(a){for(var f=a.substring(1,a.length-1).match(/\\u[\dA-Fa-f]{4}|\\x[\dA-Fa-f]{2}|\\[0-3][0-7]{0,2}|\\[0-7]{1,2}|\\[\S\s]|[^\\]/g),a=
[],b=[],o=f[0]==="^",c=o?1:0,i=f.length;c<i;++c){var j=f[c];if(/\\[bdsw]/i.test(j))a.push(j);else{var j=m(j),d;c+2<i&&"-"===f[c+1]?(d=m(f[c+2]),c+=2):d=j;b.push([j,d]);d<65||j>122||(d<65||j>90||b.push([Math.max(65,j)|32,Math.min(d,90)|32]),d<97||j>122||b.push([Math.max(97,j)&-33,Math.min(d,122)&-33]))}}b.sort(function(a,f){return a[0]-f[0]||f[1]-a[1]});f=[];j=[NaN,NaN];for(c=0;c<b.length;++c)i=b[c],i[0]<=j[1]+1?j[1]=Math.max(j[1],i[1]):f.push(j=i);b=["["];o&&b.push("^");b.push.apply(b,a);for(c=0;c<
f.length;++c)i=f[c],b.push(e(i[0])),i[1]>i[0]&&(i[1]+1>i[0]&&b.push("-"),b.push(e(i[1])));b.push("]");return b.join("")}function y(a){for(var f=a.source.match(/\[(?:[^\\\]]|\\[\S\s])*]|\\u[\dA-Fa-f]{4}|\\x[\dA-Fa-f]{2}|\\\d+|\\[^\dux]|\(\?[!:=]|[()^]|[^()[\\^]+/g),b=f.length,d=[],c=0,i=0;c<b;++c){var j=f[c];j==="("?++i:"\\"===j.charAt(0)&&(j=+j.substring(1))&&j<=i&&(d[j]=-1)}for(c=1;c<d.length;++c)-1===d[c]&&(d[c]=++t);for(i=c=0;c<b;++c)j=f[c],j==="("?(++i,d[i]===void 0&&(f[c]="(?:")):"\\"===j.charAt(0)&&
(j=+j.substring(1))&&j<=i&&(f[c]="\\"+d[i]);for(i=c=0;c<b;++c)"^"===f[c]&&"^"!==f[c+1]&&(f[c]="");if(a.ignoreCase&&s)for(c=0;c<b;++c)j=f[c],a=j.charAt(0),j.length>=2&&a==="["?f[c]=h(j):a!=="\\"&&(f[c]=j.replace(/[A-Za-z]/g,function(a){a=a.charCodeAt(0);return"["+String.fromCharCode(a&-33,a|32)+"]"}));return f.join("")}for(var t=0,s=!1,l=!1,p=0,d=a.length;p<d;++p){var g=a[p];if(g.ignoreCase)l=!0;else if(/[a-z]/i.test(g.source.replace(/\\u[\da-f]{4}|\\x[\da-f]{2}|\\[^UXux]/gi,""))){s=!0;l=!1;break}}for(var r=
{b:8,t:9,n:10,v:11,f:12,r:13},n=[],p=0,d=a.length;p<d;++p){g=a[p];if(g.global||g.multiline)throw Error(""+g);n.push("(?:"+y(g)+")")}return RegExp(n.join("|"),l?"gi":"g")}function M(a){function m(a){switch(a.nodeType){case 1:if(e.test(a.className))break;for(var g=a.firstChild;g;g=g.nextSibling)m(g);g=a.nodeName;if("BR"===g||"LI"===g)h[s]="\n",t[s<<1]=y++,t[s++<<1|1]=a;break;case 3:case 4:g=a.nodeValue,g.length&&(g=p?g.replace(/\r\n?/g,"\n"):g.replace(/[\t\n\r ]+/g," "),h[s]=g,t[s<<1]=y,y+=g.length,
t[s++<<1|1]=a)}}var e=/(?:^|\s)nocode(?:\s|$)/,h=[],y=0,t=[],s=0,l;a.currentStyle?l=a.currentStyle.whiteSpace:window.getComputedStyle&&(l=document.defaultView.getComputedStyle(a,q).getPropertyValue("white-space"));var p=l&&"pre"===l.substring(0,3);m(a);return{a:h.join("").replace(/\n$/,""),c:t}}function B(a,m,e,h){m&&(a={a:m,d:a},e(a),h.push.apply(h,a.e))}function x(a,m){function e(a){for(var l=a.d,p=[l,"pln"],d=0,g=a.a.match(y)||[],r={},n=0,z=g.length;n<z;++n){var f=g[n],b=r[f],o=void 0,c;if(typeof b===
"string")c=!1;else{var i=h[f.charAt(0)];if(i)o=f.match(i[1]),b=i[0];else{for(c=0;c<t;++c)if(i=m[c],o=f.match(i[1])){b=i[0];break}o||(b="pln")}if((c=b.length>=5&&"lang-"===b.substring(0,5))&&!(o&&typeof o[1]==="string"))c=!1,b="src";c||(r[f]=b)}i=d;d+=f.length;if(c){c=o[1];var j=f.indexOf(c),k=j+c.length;o[2]&&(k=f.length-o[2].length,j=k-c.length);b=b.substring(5);B(l+i,f.substring(0,j),e,p);B(l+i+j,c,C(b,c),p);B(l+i+k,f.substring(k),e,p)}else p.push(l+i,b)}a.e=p}var h={},y;(function(){for(var e=a.concat(m),
l=[],p={},d=0,g=e.length;d<g;++d){var r=e[d],n=r[3];if(n)for(var k=n.length;--k>=0;)h[n.charAt(k)]=r;r=r[1];n=""+r;p.hasOwnProperty(n)||(l.push(r),p[n]=q)}l.push(/[\S\s]/);y=L(l)})();var t=m.length;return e}function u(a){var m=[],e=[];a.tripleQuotedStrings?m.push(["str",/^(?:'''(?:[^'\\]|\\[\S\s]|''?(?=[^']))*(?:'''|$)|"""(?:[^"\\]|\\[\S\s]|""?(?=[^"]))*(?:"""|$)|'(?:[^'\\]|\\[\S\s])*(?:'|$)|"(?:[^"\\]|\\[\S\s])*(?:"|$))/,q,"'\""]):a.multiLineStrings?m.push(["str",/^(?:'(?:[^'\\]|\\[\S\s])*(?:'|$)|"(?:[^"\\]|\\[\S\s])*(?:"|$)|`(?:[^\\`]|\\[\S\s])*(?:`|$))/,
q,"'\"`"]):m.push(["str",/^(?:'(?:[^\n\r'\\]|\\.)*(?:'|$)|"(?:[^\n\r"\\]|\\.)*(?:"|$))/,q,"\"'"]);a.verbatimStrings&&e.push(["str",/^@"(?:[^"]|"")*(?:"|$)/,q]);var h=a.hashComments;h&&(a.cStyleComments?(h>1?m.push(["com",/^#(?:##(?:[^#]|#(?!##))*(?:###|$)|.*)/,q,"#"]):m.push(["com",/^#(?:(?:define|elif|else|endif|error|ifdef|include|ifndef|line|pragma|undef|warning)\b|[^\n\r]*)/,q,"#"]),e.push(["str",/^<(?:(?:(?:\.\.\/)*|\/?)(?:[\w-]+(?:\/[\w-]+)+)?[\w-]+\.h|[a-z]\w*)>/,q])):m.push(["com",/^#[^\n\r]*/,
q,"#"]));a.cStyleComments&&(e.push(["com",/^\/\/[^\n\r]*/,q]),e.push(["com",/^\/\*[\S\s]*?(?:\*\/|$)/,q]));a.regexLiterals&&e.push(["lang-regex",/^(?:^^\.?|[!+-]|!=|!==|#|%|%=|&|&&|&&=|&=|\(|\*|\*=|\+=|,|-=|->|\/|\/=|:|::|;|<|<<|<<=|<=|=|==|===|>|>=|>>|>>=|>>>|>>>=|[?@[^]|\^=|\^\^|\^\^=|{|\||\|=|\|\||\|\|=|~|break|case|continue|delete|do|else|finally|instanceof|return|throw|try|typeof)\s*(\/(?=[^*/])(?:[^/[\\]|\\[\S\s]|\[(?:[^\\\]]|\\[\S\s])*(?:]|$))+\/)/]);(h=a.types)&&e.push(["typ",h]);a=(""+a.keywords).replace(/^ | $/g,
"");a.length&&e.push(["kwd",RegExp("^(?:"+a.replace(/[\s,]+/g,"|")+")\\b"),q]);m.push(["pln",/^\s+/,q," \r\n\t\xa0"]);e.push(["lit",/^@[$_a-z][\w$@]*/i,q],["typ",/^(?:[@_]?[A-Z]+[a-z][\w$@]*|\w+_t\b)/,q],["pln",/^[$_a-z][\w$@]*/i,q],["lit",/^(?:0x[\da-f]+|(?:\d(?:_\d+)*\d*(?:\.\d*)?|\.\d\+)(?:e[+-]?\d+)?)[a-z]*/i,q,"0123456789"],["pln",/^\\[\S\s]?/,q],["pun",/^.[^\s\w"-$'./@\\`]*/,q]);return x(m,e)}function D(a,m){function e(a){switch(a.nodeType){case 1:if(k.test(a.className))break;if("BR"===a.nodeName)h(a),
a.parentNode&&a.parentNode.removeChild(a);else for(a=a.firstChild;a;a=a.nextSibling)e(a);break;case 3:case 4:if(p){var b=a.nodeValue,d=b.match(t);if(d){var c=b.substring(0,d.index);a.nodeValue=c;(b=b.substring(d.index+d[0].length))&&a.parentNode.insertBefore(s.createTextNode(b),a.nextSibling);h(a);c||a.parentNode.removeChild(a)}}}}function h(a){function b(a,d){var e=d?a.cloneNode(!1):a,f=a.parentNode;if(f){var f=b(f,1),g=a.nextSibling;f.appendChild(e);for(var h=g;h;h=g)g=h.nextSibling,f.appendChild(h)}return e}
for(;!a.nextSibling;)if(a=a.parentNode,!a)return;for(var a=b(a.nextSibling,0),e;(e=a.parentNode)&&e.nodeType===1;)a=e;d.push(a)}var k=/(?:^|\s)nocode(?:\s|$)/,t=/\r\n?|\n/,s=a.ownerDocument,l;a.currentStyle?l=a.currentStyle.whiteSpace:window.getComputedStyle&&(l=s.defaultView.getComputedStyle(a,q).getPropertyValue("white-space"));var p=l&&"pre"===l.substring(0,3);for(l=s.createElement("LI");a.firstChild;)l.appendChild(a.firstChild);for(var d=[l],g=0;g<d.length;++g)e(d[g]);m===(m|0)&&d[0].setAttribute("value",
m);var r=s.createElement("OL");r.className="linenums";for(var n=Math.max(0,m-1|0)||0,g=0,z=d.length;g<z;++g)l=d[g],l.className="L"+(g+n)%10,l.firstChild||l.appendChild(s.createTextNode("\xa0")),r.appendChild(l);a.appendChild(r)}function k(a,m){for(var e=m.length;--e>=0;){var h=m[e];A.hasOwnProperty(h)?window.console&&console.warn("cannot override language handler %s",h):A[h]=a}}function C(a,m){if(!a||!A.hasOwnProperty(a))a=/^\s*</.test(m)?"default-markup":"default-code";return A[a]}function E(a){var m=
a.g;try{var e=M(a.h),h=e.a;a.a=h;a.c=e.c;a.d=0;C(m,h)(a);var k=/\bMSIE\b/.test(navigator.userAgent),m=/\n/g,t=a.a,s=t.length,e=0,l=a.c,p=l.length,h=0,d=a.e,g=d.length,a=0;d[g]=s;var r,n;for(n=r=0;n<g;)d[n]!==d[n+2]?(d[r++]=d[n++],d[r++]=d[n++]):n+=2;g=r;for(n=r=0;n<g;){for(var z=d[n],f=d[n+1],b=n+2;b+2<=g&&d[b+1]===f;)b+=2;d[r++]=z;d[r++]=f;n=b}for(d.length=r;h<p;){var o=l[h+2]||s,c=d[a+2]||s,b=Math.min(o,c),i=l[h+1],j;if(i.nodeType!==1&&(j=t.substring(e,b))){k&&(j=j.replace(m,"\r"));i.nodeValue=
j;var u=i.ownerDocument,v=u.createElement("SPAN");v.className=d[a+1];var x=i.parentNode;x.replaceChild(v,i);v.appendChild(i);e<o&&(l[h+1]=i=u.createTextNode(t.substring(b,o)),x.insertBefore(i,v.nextSibling))}e=b;e>=o&&(h+=2);e>=c&&(a+=2)}}catch(w){"console"in window&&console.log(w&&w.stack?w.stack:w)}}var v=["break,continue,do,else,for,if,return,while"],w=[[v,"auto,case,char,const,default,double,enum,extern,float,goto,int,long,register,short,signed,sizeof,static,struct,switch,typedef,union,unsigned,void,volatile"],
"catch,class,delete,false,import,new,operator,private,protected,public,this,throw,true,try,typeof"],F=[w,"alignof,align_union,asm,axiom,bool,concept,concept_map,const_cast,constexpr,decltype,dynamic_cast,explicit,export,friend,inline,late_check,mutable,namespace,nullptr,reinterpret_cast,static_assert,static_cast,template,typeid,typename,using,virtual,where"],G=[w,"abstract,boolean,byte,extends,final,finally,implements,import,instanceof,null,native,package,strictfp,super,synchronized,throws,transient"],
H=[G,"as,base,by,checked,decimal,delegate,descending,dynamic,event,fixed,foreach,from,group,implicit,in,interface,internal,into,is,lock,object,out,override,orderby,params,partial,readonly,ref,sbyte,sealed,stackalloc,string,select,uint,ulong,unchecked,unsafe,ushort,var"],w=[w,"debugger,eval,export,function,get,null,set,undefined,var,with,Infinity,NaN"],I=[v,"and,as,assert,class,def,del,elif,except,exec,finally,from,global,import,in,is,lambda,nonlocal,not,or,pass,print,raise,try,with,yield,False,True,None"],
J=[v,"alias,and,begin,case,class,def,defined,elsif,end,ensure,false,in,module,next,nil,not,or,redo,rescue,retry,self,super,then,true,undef,unless,until,when,yield,BEGIN,END"],v=[v,"case,done,elif,esac,eval,fi,function,in,local,set,then,until"],K=/^(DIR|FILE|vector|(de|priority_)?queue|list|stack|(const_)?iterator|(multi)?(set|map)|bitset|u?(int|float)\d*)/,N=/\S/,O=u({keywords:[F,H,w,"caller,delete,die,do,dump,elsif,eval,exit,foreach,for,goto,if,import,last,local,my,next,no,our,print,package,redo,require,sub,undef,unless,until,use,wantarray,while,BEGIN,END"+
I,J,v],hashComments:!0,cStyleComments:!0,multiLineStrings:!0,regexLiterals:!0}),A={};k(O,["default-code"]);k(x([],[["pln",/^[^<?]+/],["dec",/^<!\w[^>]*(?:>|$)/],["com",/^<\!--[\S\s]*?(?:--\>|$)/],["lang-",/^<\?([\S\s]+?)(?:\?>|$)/],["lang-",/^<%([\S\s]+?)(?:%>|$)/],["pun",/^(?:<[%?]|[%?]>)/],["lang-",/^<xmp\b[^>]*>([\S\s]+?)<\/xmp\b[^>]*>/i],["lang-js",/^<script\b[^>]*>([\S\s]*?)(<\/script\b[^>]*>)/i],["lang-css",/^<style\b[^>]*>([\S\s]*?)(<\/style\b[^>]*>)/i],["lang-in.tag",/^(<\/?[a-z][^<>]*>)/i]]),
["default-markup","htm","html","mxml","xhtml","xml","xsl"]);k(x([["pln",/^\s+/,q," \t\r\n"],["atv",/^(?:"[^"]*"?|'[^']*'?)/,q,"\"'"]],[["tag",/^^<\/?[a-z](?:[\w-.:]*\w)?|\/?>$/i],["atn",/^(?!style[\s=]|on)[a-z](?:[\w:-]*\w)?/i],["lang-uq.val",/^=\s*([^\s"'>]*(?:[^\s"'/>]|\/(?=\s)))/],["pun",/^[/<->]+/],["lang-js",/^on\w+\s*=\s*"([^"]+)"/i],["lang-js",/^on\w+\s*=\s*'([^']+)'/i],["lang-js",/^on\w+\s*=\s*([^\s"'>]+)/i],["lang-css",/^style\s*=\s*"([^"]+)"/i],["lang-css",/^style\s*=\s*'([^']+)'/i],["lang-css",
/^style\s*=\s*([^\s"'>]+)/i]]),["in.tag"]);k(x([],[["atv",/^[\S\s]+/]]),["uq.val"]);k(u({keywords:F,hashComments:!0,cStyleComments:!0,types:K}),["c","cc","cpp","cxx","cyc","m"]);k(u({keywords:"null,true,false"}),["json"]);k(u({keywords:H,hashComments:!0,cStyleComments:!0,verbatimStrings:!0,types:K}),["cs"]);k(u({keywords:G,cStyleComments:!0}),["java"]);k(u({keywords:v,hashComments:!0,multiLineStrings:!0}),["bsh","csh","sh"]);k(u({keywords:I,hashComments:!0,multiLineStrings:!0,tripleQuotedStrings:!0}),
["cv","py"]);k(u({keywords:"caller,delete,die,do,dump,elsif,eval,exit,foreach,for,goto,if,import,last,local,my,next,no,our,print,package,redo,require,sub,undef,unless,until,use,wantarray,while,BEGIN,END",hashComments:!0,multiLineStrings:!0,regexLiterals:!0}),["perl","pl","pm"]);k(u({keywords:J,hashComments:!0,multiLineStrings:!0,regexLiterals:!0}),["rb"]);k(u({keywords:w,cStyleComments:!0,regexLiterals:!0}),["js"]);k(u({keywords:"all,and,by,catch,class,else,extends,false,finally,for,if,in,is,isnt,loop,new,no,not,null,of,off,on,or,return,super,then,true,try,unless,until,when,while,yes",
hashComments:3,cStyleComments:!0,multilineStrings:!0,tripleQuotedStrings:!0,regexLiterals:!0}),["coffee"]);k(x([],[["str",/^[\S\s]+/]]),["regex"]);window.prettyPrintOne=function(a,m,e){var h=document.createElement("PRE");h.innerHTML=a;e&&D(h,e);E({g:m,i:e,h:h});return h.innerHTML};window.prettyPrint=function(a){function m(){for(var e=window.PR_SHOULD_USE_CONTINUATION?l.now()+250:Infinity;p<h.length&&l.now()<e;p++){var n=h[p],k=n.className;if(k.indexOf("prettyprint")>=0){var k=k.match(g),f,b;if(b=
!k){b=n;for(var o=void 0,c=b.firstChild;c;c=c.nextSibling)var i=c.nodeType,o=i===1?o?b:c:i===3?N.test(c.nodeValue)?b:o:o;b=(f=o===b?void 0:o)&&"CODE"===f.tagName}b&&(k=f.className.match(g));k&&(k=k[1]);b=!1;for(o=n.parentNode;o;o=o.parentNode)if((o.tagName==="pre"||o.tagName==="code"||o.tagName==="xmp")&&o.className&&o.className.indexOf("prettyprint")>=0){b=!0;break}b||((b=(b=n.className.match(/\blinenums\b(?::(\d+))?/))?b[1]&&b[1].length?+b[1]:!0:!1)&&D(n,b),d={g:k,h:n,i:b},E(d))}}p<h.length?setTimeout(m,
250):a&&a()}for(var e=[document.getElementsByTagName("pre"),document.getElementsByTagName("code"),document.getElementsByTagName("xmp")],h=[],k=0;k<e.length;++k)for(var t=0,s=e[k].length;t<s;++t)h.push(e[k][t]);var e=q,l=Date;l.now||(l={now:function(){return+new Date}});var p=0,d,g=/\blang(?:uage)?-([\w.]+)(?!\S)/;m()};window.PR={createSimpleLexer:x,registerLangHandler:k,sourceDecorator:u,PR_ATTRIB_NAME:"atn",PR_ATTRIB_VALUE:"atv",PR_COMMENT:"com",PR_DECLARATION:"dec",PR_KEYWORD:"kwd",PR_LITERAL:"lit",
PR_NOCODE:"nocode",PR_PLAIN:"pln",PR_PUNCTUATION:"pun",PR_SOURCE:"src",PR_STRING:"str",PR_TAG:"tag",PR_TYPE:"typ"}})();

/* lib/kickstart/js/kickstart.js */
/*
	99Lime.com HTML KickStart by Joshua Gatcke
	kickstart.js
*/

$(document).ready(function(){

	/*---------------------------------
		MENU Dropdowns
	-----------------------------------*/
	$('ul.menu').each(function(){
		// find menu items with children.
		$(this).find('li').has('ul').addClass('has-menu')
		.append('<span class="arrow">&nbsp;</span>');
	});
	
	$('ul.menu li').hover(function(){
		$(this).find('ul:first').stop(true, true).show();
		$(this).addClass('hover');
	},
	function(){
		$(this).find('ul').stop(true, true).hide();
		$(this).removeClass('hover');
	});
	
	
	/*---------------------------------
		ScrollTo/LocalScroll
	-----------------------------------*/
	$.localScroll({
		filter: ':not(.tabs>li>a)',
		lazy: true,
		hash: true
	});
	
	/*---------------------------------
		Slideshow
	-----------------------------------*/
	// setup
	$('ul.slideshow').wrap('<div class="slideshow-wrap"><div class="slideshow-inner"></div></div>')
	.each(function(){
		var wrap = $(this).parents('.slideshow-wrap');
		var inner = $(this).parents('.slideshow-inner');
		
		// set height and width
		var swidth = $(this).attr('width');
		var sheight = $(this).attr('height');
		if(swidth != undefined && sheight != undefined){wrap.width(swidth); inner.height(sheight);}
		$(this).width('999em').attr('width','').attr('height','');
	
		$(this).find('li:first').addClass('current');
		$(this).delay(2000).animate({alpha:1}, function(){
			KSslideshow($(this), null);
		});
		
		// add navigation buttons
		var items = $(this).find('li');
		wrap.append('<ul class="slideshow-buttons"></ul>');
		items.each(function(index){
			wrap.find('.slideshow-buttons')
			.append('<li><a href="#slideshow-'+index+'" rel="'+index+'">'+(index+1)+'</a></li>');
		});
		
		// stop play button
		wrap.find('.slideshow-buttons')
		.append('<li class="slideshow-stop"><a href="#slideshow-stop">Stop</a></li>');
		wrap.find('.slideshow-stop a').click(function(e){
			e.preventDefault();
			if(!wrap.hasClass('paused')){ next = wrap.find('.slideshow li.current');}
			else{ next = null; }
			var slideshow = $(this).parents('.slideshow-wrap').find('ul.slideshow');
			KSslideshow(slideshow, next);
		});
		
		// button events
		$('.slideshow-buttons li:first').addClass('current');
		wrap.find('.slideshow-buttons li').not('.slideshow-stop').find('a').click(function(e){
			e.preventDefault();
			var slideshow = wrap.find('ul.slideshow');
			var link = $(e.currentTarget).attr('rel');
			var next = slideshow.find('li').eq(link);
			KSslideshow(slideshow, next);
		});
	});
	
	// run slideshow
	function KSslideshow(slideshow, next){
		var wrap = slideshow.parents('.slideshow-wrap');
		var inner = slideshow.parents('.slideshow-inner');
		var current = slideshow.find('li.current');
		var nav = slideshow.parents('.slideshow-wrap').find('.slideshow-buttons li');
		var sstop = nav.filter('.slideshow-stop');	
		
		// next slide
		if(next == null){
			next = current.next();		
			if(next.length < 1){ next = slideshow.find('li:first'); }
			wrap.removeClass('paused');
			sstop.find('a').html('Stop');
		}else{
			wrap.addClass('paused');
			sstop.find('a').html('Play');
		}
		
		// scroll
		var scrollEffect = inner.scrollTo(next, 1000);
		current.removeClass('current');
		next.addClass('current');
		nav.removeClass('current').eq(next.index()).addClass('current');
		slideshow.delay(3000).animate({alpha:1}, function(){
			if(wrap.hasClass('paused')== false){ KSslideshow(slideshow, null);  }
		});
	}
	
	/*---------------------------------
		HTML5 Placeholder Support
	-----------------------------------*/
	$('input[placeholder], textarea[placeholder]').placeholder();
	
	/*---------------------------------
		SELECT MENUS - CHOSEN
	-----------------------------------*/
	$('select.fancy').chosen();
	
	/*---------------------------------
		MEDIA
	-----------------------------------*/
	// video placeholder
	$('a.video-placeholder').each(function(){
		$(this).append('<span class="icon x-large white" data-icon="&nbsp;"></span>');
	});
	
	// calendar
	$('.calendar').each(function(){
		if($(this).attr('data-month')){ cMonth = $(this).attr('data-month'); }
		if($(this).attr('data-year')){ cYear = $(this).attr('data-year'); }
		$(this).calendarWidget({month:cMonth, year: cYear});
	});
	
	/*---------------------------------
		Fancybox Lightbox
	-----------------------------------*/
	$('.gallery').each(function(i){
		$(this).find('a').attr('rel', 'gallery'+i)
		.fancybox({
			overlayOpacity: 0.2,
			overlayColor: '#000'
		});
	});
	
	// lightbox links
	$('a.lightbox').fancybox({
		overlayOpacity: 0.2,
		overlayColor: '#000'
	});
	
	/*---------------------------------
		Tabs
	-----------------------------------*/
	// tab setup
	$('.tab-content').addClass('clearfix').not(':first').hide();
	$('ul.tabs').each(function(){
		var current = $(this).find('li.current');
		if(current.length < 1){ $(this).find('li:first').addClass('current'); }
		current = $(this).find('li.current a').attr('href');
		$(current).show();
	});
	
	// tab click
	$('ul.tabs a[href^="#"]').live('click', function(e){
		e.preventDefault();
		var tabs = $(this).parents('ul.tabs').find('li');
		var tab_next = $(this).attr('href');
		var tab_current = tabs.filter('.current').find('a').attr('href');
		$(tab_current).hide();
		tabs.removeClass('current');
		$(this).parent().addClass('current');
		$(tab_next).show();
		return false;
	});
	
	/*---------------------------------
		Image Style Helpers
	-----------------------------------*/
	$('img.style1, img.style2, img.style3').each(function(){
		$(this).wrap('<span>');
		$(this).parent('span')
			.attr('class', 'img-wrap '+$(this).attr('class'))
			.css('background-image','url('+$(this).attr('src')+')')
			.css('background-position','center center')
			.css('background-repeat','no-repeat')
			.css('height', $(this).height())
			.css('width', $(this).width());
		$(this).attr('class','').hide();
	});
	
	/*---------------------------------
		Image Caption
	-----------------------------------*/
	$('img.caption').each(function(){
		$(this).wrap('<div class="caption">');
		$(this).parents('div.caption')
			.attr('class', 'caption '+$(this).attr('class'))
			.css('width', $(this).width()+'px');
		if($(this).attr('title')){ 
			$(this).parents('div.caption')
			.append('<span>'+$(this).attr('title')+'</span>');
		}
	});
	
	/*---------------------------------
		Notice
	-----------------------------------*/
	$('.notice a.close').live('click', function(e){
		e.preventDefault();
		var notice = $(this).parents('.notice');
		$(this).hide();
		notice.fadeOut('slow');
	});
	
	/*---------------------------------
		ToolTip - TipTip
	-----------------------------------*/	
	
	// Standard tooltip
	$('.tooltip, .tooltip-top, .tooltip-bottom, .tooltip-right, .tooltip-left').each(function(){
		// variables 
		var tpos = 'top';
		var content = $(this).attr('title');
		var dataContent = $(this).attr('data-content');
		var keepAlive = false;
		var action = 'hover';
		
		// position
		if($(this).hasClass('tooltip-top'))	{ tpos = 'top'; 	}
		if($(this).hasClass('tooltip-right'))	{ tpos = 'right'; 	}
		if($(this).hasClass('tooltip-bottom'))	{ tpos = 'bottom'; 	}
		if($(this).hasClass('tooltip-left'))	{ tpos = 'left'; 	}
		
		// content
		$('.tooltip-content').removeClass('hide').wrap('<div class="hide"></div>');
		if(dataContent){ content = $(dataContent).html(); keepAlive = true; }
		
		// action (hover or click)defaults to hover
		if($(this).attr('data-action')== 'click'){ action = 'click'; }
		
		// tooltip
		$(this).attr('title','')
		.tipTip({defaultPosition: tpos, content: content, keepAlive: keepAlive, activation: action});
	});
	
	/*---------------------------------
		Table Sort
	-----------------------------------*/
	// init
	var aAsc = [];
	$('table.sortable').each(function(){
		$(this).find('thead th').each(function(index){$(this).attr('rel', index);});
		$(this).find('th,td').each(function(){$(this).attr('value', $(this).text());});
	});

	// table click
	$('table.sortable thead th').live('click', function(e){
		// update arrow icon
		$(this).parents('table.sortable').find('span.arrow').remove();
		$(this).append('<span class="arrow"></span>');
	
		// sort direction
		var nr = $(this).attr('rel');
		aAsc[nr] = aAsc[nr]=='asc'?'desc':'asc';
		if(aAsc[nr] == 'desc'){ $(this).find('span.arrow').addClass('up'); }
		
		// sort rows
		var rows = $(this).parents('table.sortable').find('tbody tr');
		rows.tsort('td:eq('+nr+')',{order:aAsc[nr],attr:'value'});
		
		// fix row classes
		rows.removeClass('alt first last');
		var table = $(this).parents('table.sortable');
		table.find('tr:even').addClass('alt');
		table.find('tr:first').addClass('first');
		table.find('tr:last').addClass('last');
	});
	
	/*---------------------------------
		Icons
	-----------------------------------*/
	$('.icon').each(function(){
		$(this).append('<span aria-hidden="true">'+$(this).attr('data-icon')+'</span>')
		.css('display', 'inline-block');
	});
	
	/*---------------------------------
		CSS Helpers
	-----------------------------------*/
	if($.browser.msie){ $('body').addClass('msie'); }
	$('input[type=checkbox]').addClass('checkbox');
	$('input[type=radio]').addClass('radio');
	$('input[type=file]').addClass('file');
	$('[disabled=disabled]').addClass('disabled');
	$('table').find('tr:even').addClass('alt');
	$('table').find('tr:first-child').addClass('first');
	$('table').find('tr:last-child').addClass('last');
	$('ul').find('li:first-child').addClass('first');
	$('ul').find('li:last-child').addClass('last');
	$('hr').before('<div class="clear">&nbsp;</div>');
	$('[class*=col_]').not('input, label').addClass('column').wrapInner('<div class="inner">');
	$('pre').addClass('prettyprint');prettyPrint();
	
});

/**
 * jQuery.LocalScroll - Animated scrolling navigation, using anchors.
 * Copyright © 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 3/11/2009
 * @author Ariel Flesler
 * @version 1.2.7
 **/
(function($){var l=location.href.replace(/#.*/,'');var g=$.localScroll=function(a){$('body').localScroll(a)};g.defaults={duration:1e3,axis:'y',event:'click',stop:true,target:window,reset:true};g.hash=function(a){if(location.hash){a=$.extend({},g.defaults,a);a.hash=false;if(a.reset){var e=a.duration;delete a.duration;$(a.target).scrollTo(0,a);a.duration=e}i(0,location,a)}};$.fn.localScroll=function(b){b=$.extend({},g.defaults,b);return b.lazy?this.bind(b.event,function(a){var e=$([a.target,a.target.parentNode]).filter(d)[0];if(e)i(a,e,b)}):this.find('a,area').filter(d).bind(b.event,function(a){i(a,this,b)}).end().end();function d(){return!!this.href&&!!this.hash&&this.href.replace(this.hash,'')==l&&(!b.filter||$(this).is(b.filter))}};function i(a,e,b){var d=e.hash.slice(1),f=document.getElementById(d)||document.getElementsByName(d)[0];if(!f)return;if(a)a.preventDefault();var h=$(b.target);if(b.lock&&h.is(':animated')||b.onBefore&&b.onBefore.call(b,a,f,h)===false)return;if(b.stop)h.stop(true);if(b.hash){var j=f.id==d?'id':'name',k=$('<a> </a>').attr(j,d).css({position:'absolute',top:$(window).scrollTop(),left:$(window).scrollLeft()});f[j]='';$('body').prepend(k);location=e.hash;k.remove();f[j]=d}h.scrollTo(f,b).trigger('notify.serialScroll',[f])}})(jQuery);

/**
 * jQuery.ScrollTo - Easy element scrolling using jQuery.
 * Copyright © 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 5/25/2009
 * @author Ariel Flesler
 * @version 1.4.2
 *
 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 */
;(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/*
* Placeholder plugin for jQuery
* ---
* Copyright 2010, Daniel Stocks (http://webcloud.se)
* Released under the MIT, BSD, and GPL Licenses.
*/

(function(b){function d(a){this.input=a;a.attr("type")=="password"&&this.handlePassword();b(a[0].form).submit(function(){if(a.hasClass("placeholder")&&a[0].value==a.attr("placeholder"))a[0].value=""})}d.prototype={show:function(a){if(this.input[0].value===""||a&&this.valueIsPlaceholder()){if(this.isPassword)try{this.input[0].setAttribute("type","text")}catch(b){this.input.before(this.fakePassword.show()).hide()}this.input.addClass("placeholder");this.input[0].value=this.input.attr("placeholder")}},
hide:function(){if(this.valueIsPlaceholder()&&this.input.hasClass("placeholder")&&(this.input.removeClass("placeholder"),this.input[0].value="",this.isPassword)){try{this.input[0].setAttribute("type","password")}catch(a){}this.input.show();this.input[0].focus()}},valueIsPlaceholder:function(){return this.input[0].value==this.input.attr("placeholder")},handlePassword:function(){var a=this.input;a.attr("realType","password");this.isPassword=!0;if(b.browser.msie&&a[0].outerHTML){var c=b(a[0].outerHTML.replace(/type=(['"])?password\1/gi,
"type=$1text$1"));this.fakePassword=c.val(a.attr("placeholder")).addClass("placeholder").focus(function(){a.trigger("focus");b(this).hide()});b(a[0].form).submit(function(){c.remove();a.show()})}}};var e=!!("placeholder"in document.createElement("input"));b.fn.placeholder=function(){return e?this:this.each(function(){var a=b(this),c=new d(a);c.show(!0);a.focus(function(){c.hide()});a.blur(function(){c.show(!1)});b.browser.msie&&(b(window).load(function(){a.val()&&a.removeClass("placeholder");c.show(!0)}),
a.focus(function(){if(this.value==""){var a=this.createTextRange();a.collapse(!0);a.moveStart("character",0);a.select()}}))})}})(jQuery);

/*
 * FancyBox - jQuery Plugin
 * Simple and fancy lightbox alternative
 *
 * Examples and documentation at: http://fancybox.net
 * 
 * Copyright (c)2008 - 2010 Janis Skarnelis
 * That said, it is hardly a one-person project. Many people have submitted bugs, code, and offered their advice freely. Their support is greatly appreciated.
 * 
 * Version: 1.3.4 (11/11/2010)
 * Requires: jQuery v1.3+
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

(function(b){var m,t,u,f,D,j,E,n,z,A,q=0,e={},o=[],p=0,d={},l=[],G=null,v=new Image,J=/\.(jpg|gif|png|bmp|jpeg)(.*)?$/i,W=/[^\.]\.(swf)\s*$/i,K,L=1,y=0,s="",r,i,h=false,B=b.extend(b("<div/>")[0],{prop:0}),M=b.browser.msie&&b.browser.version<7&&!window.XMLHttpRequest,N=function(){t.hide();v.onerror=v.onload=null;G&&G.abort();m.empty()},O=function(){if(false===e.onError(o,q,e)){t.hide();h=false}else{e.titleShow=false;e.width="auto";e.height="auto";m.html('<p id="fancybox-error">The requested content cannot be loaded.<br />Please try again later.</p>');
F()}},I=function(){var a=o[q],c,g,k,C,P,w;N();e=b.extend({},b.fn.fancybox.defaults,typeof b(a).data("fancybox")=="undefined"?e:b(a).data("fancybox"));w=e.onStart(o,q,e);if(w===false)h=false;else{if(typeof w=="object")e=b.extend(e,w);k=e.title||(a.nodeName?b(a).attr("title"):a.title)||"";if(a.nodeName&&!e.orig)e.orig=b(a).children("img:first").length?b(a).children("img:first"):b(a);if(k===""&&e.orig&&e.titleFromAlt)k=e.orig.attr("alt");c=e.href||(a.nodeName?b(a).attr("href"):a.href)||null;if(/^(?:javascript)/i.test(c)||
c=="#")c=null;if(e.type){g=e.type;if(!c)c=e.content}else if(e.content)g="html";else if(c)g=c.match(J)?"image":c.match(W)?"swf":b(a).hasClass("iframe")?"iframe":c.indexOf("#")===0?"inline":"ajax";if(g){if(g=="inline"){a=c.substr(c.indexOf("#"));g=b(a).length>0?"inline":"ajax"}e.type=g;e.href=c;e.title=k;if(e.autoDimensions)if(e.type=="html"||e.type=="inline"||e.type=="ajax"){e.width="auto";e.height="auto"}else e.autoDimensions=false;if(e.modal){e.overlayShow=true;e.hideOnOverlayClick=false;e.hideOnContentClick=
false;e.enableEscapeButton=false;e.showCloseButton=false}e.padding=parseInt(e.padding,10);e.margin=parseInt(e.margin,10);m.css("padding",e.padding+e.margin);b(".fancybox-inline-tmp").unbind("fancybox-cancel").bind("fancybox-change",function(){b(this).replaceWith(j.children())});switch(g){case "html":m.html(e.content);F();break;case "inline":if(b(a).parent().is("#fancybox-content")===true){h=false;break}b('<div class="fancybox-inline-tmp" />').hide().insertBefore(b(a)).bind("fancybox-cleanup",function(){b(this).replaceWith(j.children())}).bind("fancybox-cancel",
function(){b(this).replaceWith(m.children())});b(a).appendTo(m);F();break;case "image":h=false;b.fancybox.showActivity();v=new Image;v.onerror=function(){O()};v.onload=function(){h=true;v.onerror=v.onload=null;e.width=v.width;e.height=v.height;b("<img />").attr({id:"fancybox-img",src:v.src,alt:e.title}).appendTo(m);Q()};v.src=c;break;case "swf":e.scrolling="no";C='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+e.width+'" height="'+e.height+'"><param name="movie" value="'+c+
'"></param>';P="";b.each(e.swf,function(x,H){C+='<param name="'+x+'" value="'+H+'"></param>';P+=" "+x+'="'+H+'"'});C+='<embed src="'+c+'" type="application/x-shockwave-flash" width="'+e.width+'" height="'+e.height+'"'+P+"></embed></object>";m.html(C);F();break;case "ajax":h=false;b.fancybox.showActivity();e.ajax.win=e.ajax.success;G=b.ajax(b.extend({},e.ajax,{url:c,data:e.ajax.data||{},error:function(x){x.status>0&&O()},success:function(x,H,R){if((typeof R=="object"?R:G).status==200){if(typeof e.ajax.win==
"function"){w=e.ajax.win(c,x,H,R);if(w===false){t.hide();return}else if(typeof w=="string"||typeof w=="object")x=w}m.html(x);F()}}}));break;case "iframe":Q()}}else O()}},F=function(){var a=e.width,c=e.height;a=a.toString().indexOf("%")>-1?parseInt((b(window).width()-e.margin*2)*parseFloat(a)/100,10)+"px":a=="auto"?"auto":a+"px";c=c.toString().indexOf("%")>-1?parseInt((b(window).height()-e.margin*2)*parseFloat(c)/100,10)+"px":c=="auto"?"auto":c+"px";m.wrapInner('<div style="width:'+a+";height:"+c+
";overflow: "+(e.scrolling=="auto"?"auto":e.scrolling=="yes"?"scroll":"hidden")+';position:relative;"></div>');e.width=m.width();e.height=m.height();Q()},Q=function(){var a,c;t.hide();if(f.is(":visible")&&false===d.onCleanup(l,p,d)){b.event.trigger("fancybox-cancel");h=false}else{h=true;b(j.add(u)).unbind();b(window).unbind("resize.fb scroll.fb");b(document).unbind("keydown.fb");f.is(":visible")&&d.titlePosition!=="outside"&&f.css("height",f.height());l=o;p=q;d=e;if(d.overlayShow){u.css({"background-color":d.overlayColor,
opacity:d.overlayOpacity,cursor:d.hideOnOverlayClick?"pointer":"auto",height:b(document).height()});if(!u.is(":visible")){M&&b("select:not(#fancybox-tmp select)").filter(function(){return this.style.visibility!=="hidden"}).css({visibility:"hidden"}).one("fancybox-cleanup",function(){this.style.visibility="inherit"});u.show()}}else u.hide();i=X();s=d.title||"";y=0;n.empty().removeAttr("style").removeClass();if(d.titleShow!==false){if(b.isFunction(d.titleFormat))a=d.titleFormat(s,l,p,d);else a=s&&s.length?
d.titlePosition=="float"?'<table id="fancybox-title-float-wrap" cellpadding="0" cellspacing="0"><tr><td id="fancybox-title-float-left"></td><td id="fancybox-title-float-main">'+s+'</td><td id="fancybox-title-float-right"></td></tr></table>':'<div id="fancybox-title-'+d.titlePosition+'">'+s+"</div>":false;s=a;if(!(!s||s==="")){n.addClass("fancybox-title-"+d.titlePosition).html(s).appendTo("body").show();switch(d.titlePosition){case "inside":n.css({width:i.width-d.padding*2,marginLeft:d.padding,marginRight:d.padding});
y=n.outerHeight(true);n.appendTo(D);i.height+=y;break;case "over":n.css({marginLeft:d.padding,width:i.width-d.padding*2,bottom:d.padding}).appendTo(D);break;case "float":n.css("left",parseInt((n.width()-i.width-40)/2,10)*-1).appendTo(f);break;default:n.css({width:i.width-d.padding*2,paddingLeft:d.padding,paddingRight:d.padding}).appendTo(f)}}}n.hide();if(f.is(":visible")){b(E.add(z).add(A)).hide();a=f.position();r={top:a.top,left:a.left,width:f.width(),height:f.height()};c=r.width==i.width&&r.height==
i.height;j.fadeTo(d.changeFade,0.3,function(){var g=function(){j.html(m.contents()).fadeTo(d.changeFade,1,S)};b.event.trigger("fancybox-change");j.empty().removeAttr("filter").css({"border-width":d.padding,width:i.width-d.padding*2,height:e.autoDimensions?"auto":i.height-y-d.padding*2});if(c)g();else{B.prop=0;b(B).animate({prop:1},{duration:d.changeSpeed,easing:d.easingChange,step:T,complete:g})}})}else{f.removeAttr("style");j.css("border-width",d.padding);if(d.transitionIn=="elastic"){r=V();j.html(m.contents());
f.show();if(d.opacity)i.opacity=0;B.prop=0;b(B).animate({prop:1},{duration:d.speedIn,easing:d.easingIn,step:T,complete:S})}else{d.titlePosition=="inside"&&y>0&&n.show();j.css({width:i.width-d.padding*2,height:e.autoDimensions?"auto":i.height-y-d.padding*2}).html(m.contents());f.css(i).fadeIn(d.transitionIn=="none"?0:d.speedIn,S)}}}},Y=function(){if(d.enableEscapeButton||d.enableKeyboardNav)b(document).bind("keydown.fb",function(a){if(a.keyCode==27&&d.enableEscapeButton){a.preventDefault();b.fancybox.close()}else if((a.keyCode==
37||a.keyCode==39)&&d.enableKeyboardNav&&a.target.tagName!=="INPUT"&&a.target.tagName!=="TEXTAREA"&&a.target.tagName!=="SELECT"){a.preventDefault();b.fancybox[a.keyCode==37?"prev":"next"]()}});if(d.showNavArrows){if(d.cyclic&&l.length>1||p!==0)z.show();if(d.cyclic&&l.length>1||p!=l.length-1)A.show()}else{z.hide();A.hide()}},S=function(){if(!b.support.opacity){j.get(0).style.removeAttribute("filter");f.get(0).style.removeAttribute("filter")}e.autoDimensions&&j.css("height","auto");f.css("height","auto");
s&&s.length&&n.show();d.showCloseButton&&E.show();Y();d.hideOnContentClick&&j.bind("click",b.fancybox.close);d.hideOnOverlayClick&&u.bind("click",b.fancybox.close);b(window).bind("resize.fb",b.fancybox.resize);d.centerOnScroll&&b(window).bind("scroll.fb",b.fancybox.center);if(d.type=="iframe")b('<iframe id="fancybox-frame" name="fancybox-frame'+(new Date).getTime()+'" frameborder="0" hspace="0" '+(b.browser.msie?'allowtransparency="true""':"")+' scrolling="'+e.scrolling+'" src="'+d.href+'"></iframe>').appendTo(j);
f.show();h=false;b.fancybox.center();d.onComplete(l,p,d);var a,c;if(l.length-1>p){a=l[p+1].href;if(typeof a!=="undefined"&&a.match(J)){c=new Image;c.src=a}}if(p>0){a=l[p-1].href;if(typeof a!=="undefined"&&a.match(J)){c=new Image;c.src=a}}},T=function(a){var c={width:parseInt(r.width+(i.width-r.width)*a,10),height:parseInt(r.height+(i.height-r.height)*a,10),top:parseInt(r.top+(i.top-r.top)*a,10),left:parseInt(r.left+(i.left-r.left)*a,10)};if(typeof i.opacity!=="undefined")c.opacity=a<0.5?0.5:a;f.css(c);
j.css({width:c.width-d.padding*2,height:c.height-y*a-d.padding*2})},U=function(){return[b(window).width()-d.margin*2,b(window).height()-d.margin*2,b(document).scrollLeft()+d.margin,b(document).scrollTop()+d.margin]},X=function(){var a=U(),c={},g=d.autoScale,k=d.padding*2;c.width=d.width.toString().indexOf("%")>-1?parseInt(a[0]*parseFloat(d.width)/100,10):d.width+k;c.height=d.height.toString().indexOf("%")>-1?parseInt(a[1]*parseFloat(d.height)/100,10):d.height+k;if(g&&(c.width>a[0]||c.height>a[1]))if(e.type==
"image"||e.type=="swf"){g=d.width/d.height;if(c.width>a[0]){c.width=a[0];c.height=parseInt((c.width-k)/g+k,10)}if(c.height>a[1]){c.height=a[1];c.width=parseInt((c.height-k)*g+k,10)}}else{c.width=Math.min(c.width,a[0]);c.height=Math.min(c.height,a[1])}c.top=parseInt(Math.max(a[3]-20,a[3]+(a[1]-c.height-40)*0.5),10);c.left=parseInt(Math.max(a[2]-20,a[2]+(a[0]-c.width-40)*0.5),10);return c},V=function(){var a=e.orig?b(e.orig):false,c={};if(a&&a.length){c=a.offset();c.top+=parseInt(a.css("paddingTop"),
10)||0;c.left+=parseInt(a.css("paddingLeft"),10)||0;c.top+=parseInt(a.css("border-top-width"),10)||0;c.left+=parseInt(a.css("border-left-width"),10)||0;c.width=a.width();c.height=a.height();c={width:c.width+d.padding*2,height:c.height+d.padding*2,top:c.top-d.padding-20,left:c.left-d.padding-20}}else{a=U();c={width:d.padding*2,height:d.padding*2,top:parseInt(a[3]+a[1]*0.5,10),left:parseInt(a[2]+a[0]*0.5,10)}}return c},Z=function(){if(t.is(":visible")){b("div",t).css("top",L*-40+"px");L=(L+1)%12}else clearInterval(K)};
b.fn.fancybox=function(a){if(!b(this).length)return this;b(this).data("fancybox",b.extend({},a,b.metadata?b(this).metadata():{})).unbind("click.fb").bind("click.fb",function(c){c.preventDefault();if(!h){h=true;b(this).blur();o=[];q=0;c=b(this).attr("rel")||"";if(!c||c==""||c==="nofollow")o.push(this);else{o=b("a[rel="+c+"], area[rel="+c+"]");q=o.index(this)}I()}});return this};b.fancybox=function(a,c){var g;if(!h){h=true;g=typeof c!=="undefined"?c:{};o=[];q=parseInt(g.index,10)||0;if(b.isArray(a)){for(var k=
0,C=a.length;k<C;k++)if(typeof a[k]=="object")b(a[k]).data("fancybox",b.extend({},g,a[k]));else a[k]=b({}).data("fancybox",b.extend({content:a[k]},g));o=jQuery.merge(o,a)}else{if(typeof a=="object")b(a).data("fancybox",b.extend({},g,a));else a=b({}).data("fancybox",b.extend({content:a},g));o.push(a)}if(q>o.length||q<0)q=0;I()}};b.fancybox.showActivity=function(){clearInterval(K);t.show();K=setInterval(Z,66)};b.fancybox.hideActivity=function(){t.hide()};b.fancybox.next=function(){return b.fancybox.pos(p+
1)};b.fancybox.prev=function(){return b.fancybox.pos(p-1)};b.fancybox.pos=function(a){if(!h){a=parseInt(a);o=l;if(a>-1&&a<l.length){q=a;I()}else if(d.cyclic&&l.length>1){q=a>=l.length?0:l.length-1;I()}}};b.fancybox.cancel=function(){if(!h){h=true;b.event.trigger("fancybox-cancel");N();e.onCancel(o,q,e);h=false}};b.fancybox.close=function(){function a(){u.fadeOut("fast");n.empty().hide();f.hide();b.event.trigger("fancybox-cleanup");j.empty();d.onClosed(l,p,d);l=e=[];p=q=0;d=e={};h=false}if(!(h||f.is(":hidden"))){h=
true;if(d&&false===d.onCleanup(l,p,d))h=false;else{N();b(E.add(z).add(A)).hide();b(j.add(u)).unbind();b(window).unbind("resize.fb scroll.fb");b(document).unbind("keydown.fb");j.find("iframe").attr("src",M&&/^https/i.test(window.location.href||"")?"javascript:void(false)":"about:blank");d.titlePosition!=="inside"&&n.empty();f.stop();if(d.transitionOut=="elastic"){r=V();var c=f.position();i={top:c.top,left:c.left,width:f.width(),height:f.height()};if(d.opacity)i.opacity=1;n.empty().hide();B.prop=1;
b(B).animate({prop:0},{duration:d.speedOut,easing:d.easingOut,step:T,complete:a})}else f.fadeOut(d.transitionOut=="none"?0:d.speedOut,a)}}};b.fancybox.resize=function(){u.is(":visible")&&u.css("height",b(document).height());b.fancybox.center(true)};b.fancybox.center=function(a){var c,g;if(!h){g=a===true?1:0;c=U();!g&&(f.width()>c[0]||f.height()>c[1])||f.stop().animate({top:parseInt(Math.max(c[3]-20,c[3]+(c[1]-j.height()-40)*0.5-d.padding)),left:parseInt(Math.max(c[2]-20,c[2]+(c[0]-j.width()-40)*0.5-
d.padding))},typeof a=="number"?a:200)}};b.fancybox.init=function(){if(!b("#fancybox-wrap").length){b("body").append(m=b('<div id="fancybox-tmp"></div>'),t=b('<div id="fancybox-loading"><div></div></div>'),u=b('<div id="fancybox-overlay"></div>'),f=b('<div id="fancybox-wrap"></div>'));D=b('<div id="fancybox-outer"></div>').append('<div class="fancybox-bg" id="fancybox-bg-n"></div><div class="fancybox-bg" id="fancybox-bg-ne"></div><div class="fancybox-bg" id="fancybox-bg-e"></div><div class="fancybox-bg" id="fancybox-bg-se"></div><div class="fancybox-bg" id="fancybox-bg-s"></div><div class="fancybox-bg" id="fancybox-bg-sw"></div><div class="fancybox-bg" id="fancybox-bg-w"></div><div class="fancybox-bg" id="fancybox-bg-nw"></div>').appendTo(f);
D.append(j=b('<div id="fancybox-content"></div>'),E=b('<a id="fancybox-close"></a>'),n=b('<div id="fancybox-title"></div>'),z=b('<a href="javascript:;" id="fancybox-left"><span class="fancy-ico" id="fancybox-left-ico"></span></a>'),A=b('<a href="javascript:;" id="fancybox-right"><span class="fancy-ico" id="fancybox-right-ico"></span></a>'));E.click(b.fancybox.close);t.click(b.fancybox.cancel);z.click(function(a){a.preventDefault();b.fancybox.prev()});A.click(function(a){a.preventDefault();b.fancybox.next()});
b.fn.mousewheel&&f.bind("mousewheel.fb",function(a,c){if(h)a.preventDefault();else if(b(a.target).get(0).clientHeight==0||b(a.target).get(0).scrollHeight===b(a.target).get(0).clientHeight){a.preventDefault();b.fancybox[c>0?"prev":"next"]()}});b.support.opacity||f.addClass("fancybox-ie");if(M){t.addClass("fancybox-ie6");f.addClass("fancybox-ie6");b('<iframe id="fancybox-hide-sel-frame" src="'+(/^https/i.test(window.location.href||"")?"javascript:void(false)":"about:blank")+'" scrolling="no" border="0" frameborder="0" tabindex="-1"></iframe>').prependTo(D)}}};
b.fn.fancybox.defaults={padding:10,margin:40,opacity:false,modal:false,cyclic:false,scrolling:"auto",width:560,height:340,autoScale:true,autoDimensions:true,centerOnScroll:false,ajax:{},swf:{wmode:"transparent"},hideOnOverlayClick:true,hideOnContentClick:false,overlayShow:true,overlayOpacity:0.7,overlayColor:"#777",titleShow:true,titlePosition:"float",titleFormat:null,titleFromAlt:false,transitionIn:"fade",transitionOut:"fade",speedIn:300,speedOut:300,changeSpeed:300,changeFade:"fast",easingIn:"swing",
easingOut:"swing",showCloseButton:true,showNavArrows:true,enableEscapeButton:true,enableKeyboardNav:true,onStart:function(){},onCancel:function(){},onComplete:function(){},onCleanup:function(){},onClosed:function(){},onError:function(){}};b(document).ready(function(){b.fancybox.init()})})(jQuery);

// HTML5 Shiv v3 | @jon_neal @afarkas @rem | MIT/GPL2 Licensed
// Uncompressed source: https://github.com/aFarkas/html5shiv
(function(a,b){function f(a){var c,d,e,f;b.documentMode>7?(c=b.createElement("font"),c.setAttribute("data-html5shiv",a.nodeName.toLowerCase())):c=b.createElement("shiv:"+a.nodeName);while(a.firstChild)c.appendChild(a.childNodes[0]);for(d=a.attributes,e=d.length,f=0;f<e;++f)d[f].specified&&c.setAttribute(d[f].nodeName,d[f].nodeValue);c.style.cssText=a.style.cssText,a.parentNode.replaceChild(c,a),c.originalElement=a}function g(a){var b=a.originalElement;while(a.childNodes.length)b.appendChild(a.childNodes[0]);a.parentNode.replaceChild(b,a)}function h(a,b){b=b||"all";var c=-1,d=[],e=a.length,f,g;while(++c<e){f=a[c],g=f.media||b;if(f.disabled||!/print|all/.test(g))continue;d.push(h(f.imports,g),f.cssText)}return d.join("")}function i(c){var d=new RegExp("(^|[\\s,{}])("+a.html5.elements.join("|")+")","gi"),e=c.split("{"),f=e.length,g=-1;while(++g<f)e[g]=e[g].split("}"),b.documentMode>7?e[g][e[g].length-1]=e[g][e[g].length-1].replace(d,'$1font[data-html5shiv="$2"]'):e[g][e[g].length-1]=e[g][e[g].length-1].replace(d,"$1shiv\\:$2"),e[g]=e[g].join("}");return e.join("{")}var c=function(a){return a.innerHTML="<x-element></x-element>",a.childNodes.length===1}(b.createElement("a")),d=function(a,b,c){return b.appendChild(a),(c=(c?c(a):a.currentStyle).display)&&b.removeChild(a)&&c==="block"}(b.createElement("nav"),b.documentElement,a.getComputedStyle),e={elements:"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video".split(" "),shivDocument:function(a){a=a||b;if(a.documentShived)return;a.documentShived=!0;var f=a.createElement,g=a.createDocumentFragment,h=a.getElementsByTagName("head")[0],i=function(a){f(a)};c||(e.elements.join(" ").replace(/\w+/g,i),a.createElement=function(a){var b=f(a);return b.canHaveChildren&&e.shivDocument(b.document),b},a.createDocumentFragment=function(){return e.shivDocument(g())});if(!d&&h){var j=f("div");j.innerHTML=["x<style>","article,aside,details,figcaption,figure,footer,header,hgroup,nav,section{display:block}","audio{display:none}","canvas,video{display:inline-block;*display:inline;*zoom:1}","[hidden]{display:none}audio[controls]{display:inline-block;*display:inline;*zoom:1}","mark{background:#FF0;color:#000}","</style>"].join(""),h.insertBefore(j.lastChild,h.firstChild)}return a}};e.shivDocument(b),a.html5=e;if(c||!a.attachEvent)return;a.attachEvent("onbeforeprint",function(){if(a.html5.supportsXElement||!b.namespaces)return;b.namespaces.shiv||b.namespaces.add("shiv");var c=-1,d=new RegExp("^("+a.html5.elements.join("|")+")$","i"),e=b.getElementsByTagName("*"),g=e.length,j,k=i(h(function(a,b){var c=[],d=a.length;while(d)c.unshift(a[--d]);d=b.length;while(d)c.unshift(b[--d]);c.sort(function(a,b){return a.sourceIndex-b.sourceIndex}),d=c.length;while(d)c[--d]=c[d].styleSheet;return c}(b.getElementsByTagName("style"),b.getElementsByTagName("link"))));while(++c<g)j=e[c],d.test(j.nodeName)&&f(j);b.appendChild(b._shivedStyleSheet=b.createElement("style")).styleSheet.cssText=k}),a.attachEvent("onafterprint",function(){if(a.html5.supportsXElement||!b.namespaces)return;var c=-1,d=b.getElementsByTagName("*"),e=d.length,f;while(++c<e)f=d[c],f.originalElement&&g(f);b._shivedStyleSheet&&b._shivedStyleSheet.parentNode.removeChild(b._shivedStyleSheet)})});(this,document);

// Chosen, a Select Box Enhancer for jQuery and Protoype
// by Patrick Filler for Harvest, http://getharvest.com
// 
// Version 0.9.7
// Full source at https://github.com/harvesthq/chosen
// Copyright (c)2011 Harvest http://getharvest.com

// MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md
// This file is generated by `cake build`, do not edit it by hand.
((function(){var a;a=function(){function a(){this.options_index=0,this.parsed=[]}return a.prototype.add_node=function(a){return a.nodeName==="OPTGROUP"?this.add_group(a):this.add_option(a)},a.prototype.add_group=function(a){var b,c,d,e,f,g;b=this.parsed.length,this.parsed.push({array_index:b,group:!0,label:a.label,children:0,disabled:a.disabled}),f=a.childNodes,g=[];for(d=0,e=f.length;d<e;d++)c=f[d],g.push(this.add_option(c,b,a.disabled));return g},a.prototype.add_option=function(a,b,c){if(a.nodeName==="OPTION")return a.text!==""?(b!=null&&(this.parsed[b].children+=1),this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:a.value,text:a.text,html:a.innerHTML,selected:a.selected,disabled:c===!0?c:a.disabled,group_array_index:b,classes:a.className,style:a.style.cssText})):this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0}),this.options_index+=1},a}(),a.select_to_array=function(b){var c,d,e,f,g;d=new a,g=b.childNodes;for(e=0,f=g.length;e<f;e++)c=g[e],d.add_node(c);return d.parsed},this.SelectParser=a})).call(this),function(){var a,b;b=this,a=function(){function a(a,b){this.form_field=a,this.options=b!=null?b:{},this.set_default_values(),this.is_multiple=this.form_field.multiple,this.default_text_default=this.is_multiple?"Select Some Options":"Select an Option",this.setup(),this.set_up_html(),this.register_observers(),this.finish_setup()}return a.prototype.set_default_values=function(){var a=this;return this.click_test_action=function(b){return a.test_active_click(b)},this.activate_action=function(b){return a.activate_field(b)},this.active_field=!1,this.mouse_on_container=!1,this.results_showing=!1,this.result_highlighted=null,this.result_single_selected=null,this.allow_single_deselect=this.options.allow_single_deselect!=null&&this.form_field.options[0]!=null&&this.form_field.options[0].text===""?this.options.allow_single_deselect:!1,this.disable_search_threshold=this.options.disable_search_threshold||0,this.choices=0,this.results_none_found=this.options.no_results_text||"No results match"},a.prototype.mouse_enter=function(){return this.mouse_on_container=!0},a.prototype.mouse_leave=function(){return this.mouse_on_container=!1},a.prototype.input_focus=function(a){var b=this;if(!this.active_field)return setTimeout(function(){return b.container_mousedown()},50)},a.prototype.input_blur=function(a){var b=this;if(!this.mouse_on_container)return this.active_field=!1,setTimeout(function(){return b.blur_test()},100)},a.prototype.result_add_option=function(a){var b,c;return a.disabled?"":(a.dom_id=this.container_id+"_o_"+a.array_index,b=a.selected&&this.is_multiple?[]:["active-result"],a.selected&&b.push("result-selected"),a.group_array_index!=null&&b.push("group-option"),a.classes!==""&&b.push(a.classes),c=a.style.cssText!==""?' style="'+a.style+'"':"",'<li id="'+a.dom_id+'" class="'+b.join(" ")+'"'+c+">"+a.html+"</li>")},a.prototype.results_update_field=function(){return this.result_clear_highlight(),this.result_single_selected=null,this.results_build()},a.prototype.results_toggle=function(){return this.results_showing?this.results_hide():this.results_show()},a.prototype.results_search=function(a){return this.results_showing?this.winnow_results():this.results_show()},a.prototype.keyup_checker=function(a){var b,c;b=(c=a.which)!=null?c:a.keyCode,this.search_field_scale();switch(b){case 8:if(this.is_multiple&&this.backstroke_length<1&&this.choices>0)return this.keydown_backstroke();if(!this.pending_backstroke)return this.result_clear_highlight(),this.results_search();break;case 13:a.preventDefault();if(this.results_showing)return this.result_select(a);break;case 27:return this.results_showing&&this.results_hide(),!0;case 9:case 38:case 40:case 16:case 91:case 17:break;default:return this.results_search()}},a.prototype.generate_field_id=function(){var a;return a=this.generate_random_id(),this.form_field.id=a,a},a.prototype.generate_random_char=function(){var a,b,c;return a="0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZ",c=Math.floor(Math.random()*a.length),b=a.substring(c,c+1)},a}(),b.AbstractChosen=a}.call(this),function(){var a,b,c,d,e=Object.prototype.hasOwnProperty,f=function(a,b){function d(){this.constructor=a}for(var c in b)e.call(b,c)&&(a[c]=b[c]);return d.prototype=b.prototype,a.prototype=new d,a.__super__=b.prototype,a};d=this,a=jQuery,a.fn.extend({chosen:function(c){return!a.browser.msie||a.browser.version!=="6.0"&&a.browser.version!=="7.0"?a(this).each(function(d){if(!a(this).hasClass("chzn-done"))return new b(this,c)}):this}}),b=function(b){function e(){e.__super__.constructor.apply(this,arguments)}return f(e,b),e.prototype.setup=function(){return this.form_field_jq=a(this.form_field),this.is_rtl=this.form_field_jq.hasClass("chzn-rtl")},e.prototype.finish_setup=function(){return this.form_field_jq.addClass("chzn-done")},e.prototype.set_up_html=function(){var b,d,e,f;return this.container_id=this.form_field.id.length?this.form_field.id.replace(/(:|\.)/g,"_"):this.generate_field_id(),this.container_id+="_chzn",this.f_width=this.form_field_jq.outerWidth(),this.default_text=this.form_field_jq.data("placeholder")?this.form_field_jq.data("placeholder"):this.default_text_default,b=a("<div />",{id:this.container_id,"class":"chzn-container"+(this.is_rtl?" chzn-rtl":""),style:"width: "+this.f_width+"px;"}),this.is_multiple?b.html('<ul class="chzn-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chzn-drop" style="left:-9000px;"><ul class="chzn-results"></ul></div>'):b.html('<a href="javascript:void(0)" class="chzn-single"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chzn-drop" style="left:-9000px;"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>'),this.form_field_jq.hide().after(b),this.container=a("#"+this.container_id),this.container.addClass("chzn-container-"+(this.is_multiple?"multi":"single")),this.dropdown=this.container.find("div.chzn-drop").first(),d=this.container.height(),e=this.f_width-c(this.dropdown),this.dropdown.css({width:e+"px",top:d+"px"}),this.search_field=this.container.find("input").first(),this.search_results=this.container.find("ul.chzn-results").first(),this.search_field_scale(),this.search_no_results=this.container.find("li.no-results").first(),this.is_multiple?(this.search_choices=this.container.find("ul.chzn-choices").first(),this.search_container=this.container.find("li.search-field").first()):(this.search_container=this.container.find("div.chzn-search").first(),this.selected_item=this.container.find(".chzn-single").first(),f=e-c(this.search_container)-c(this.search_field),this.search_field.css({width:f+"px"})),this.results_build(),this.set_tab_index(),this.form_field_jq.trigger("liszt:ready",{chosen:this})},e.prototype.register_observers=function(){var a=this;return this.container.mousedown(function(b){return a.container_mousedown(b)}),this.container.mouseup(function(b){return a.container_mouseup(b)}),this.container.mouseenter(function(b){return a.mouse_enter(b)}),this.container.mouseleave(function(b){return a.mouse_leave(b)}),this.search_results.mouseup(function(b){return a.search_results_mouseup(b)}),this.search_results.mouseover(function(b){return a.search_results_mouseover(b)}),this.search_results.mouseout(function(b){return a.search_results_mouseout(b)}),this.form_field_jq.bind("liszt:updated",function(b){return a.results_update_field(b)}),this.search_field.blur(function(b){return a.input_blur(b)}),this.search_field.keyup(function(b){return a.keyup_checker(b)}),this.search_field.keydown(function(b){return a.keydown_checker(b)}),this.is_multiple?(this.search_choices.click(function(b){return a.choices_click(b)}),this.search_field.focus(function(b){return a.input_focus(b)})):this.container.click(function(a){return a.preventDefault()})},e.prototype.search_field_disabled=function(){this.is_disabled=this.form_field_jq[0].disabled;if(this.is_disabled)return this.container.addClass("chzn-disabled"),this.search_field[0].disabled=!0,this.is_multiple||this.selected_item.unbind("focus",this.activate_action),this.close_field();this.container.removeClass("chzn-disabled"),this.search_field[0].disabled=!1;if(!this.is_multiple)return this.selected_item.bind("focus",this.activate_action)},e.prototype.container_mousedown=function(b){var c;if(!this.is_disabled)return c=b!=null?a(b.target).hasClass("search-choice-close"):!1,b&&b.type==="mousedown"&&b.stopPropagation(),!this.pending_destroy_click&&!c?(this.active_field?!this.is_multiple&&b&&(a(b.target)[0]===this.selected_item[0]||a(b.target).parents("a.chzn-single").length)&&(b.preventDefault(),this.results_toggle()):(this.is_multiple&&this.search_field.val(""),a(document).click(this.click_test_action),this.results_show()),this.activate_field()):this.pending_destroy_click=!1},e.prototype.container_mouseup=function(a){if(a.target.nodeName==="ABBR")return this.results_reset(a)},e.prototype.blur_test=function(a){if(!this.active_field&&this.container.hasClass("chzn-container-active"))return this.close_field()},e.prototype.close_field=function(){return a(document).unbind("click",this.click_test_action),this.is_multiple||(this.selected_item.attr("tabindex",this.search_field.attr("tabindex")),this.search_field.attr("tabindex",-1)),this.active_field=!1,this.results_hide(),this.container.removeClass("chzn-container-active"),this.winnow_results_clear(),this.clear_backstroke(),this.show_search_field_default(),this.search_field_scale()},e.prototype.activate_field=function(){return!this.is_multiple&&!this.active_field&&(this.search_field.attr("tabindex",this.selected_item.attr("tabindex")),this.selected_item.attr("tabindex",-1)),this.container.addClass("chzn-container-active"),this.active_field=!0,this.search_field.val(this.search_field.val()),this.search_field.focus()},e.prototype.test_active_click=function(b){return a(b.target).parents("#"+this.container_id).length?this.active_field=!0:this.close_field()},e.prototype.results_build=function(){var a,b,c,e,f;this.parsing=!0,this.results_data=d.SelectParser.select_to_array(this.form_field),this.is_multiple&&this.choices>0?(this.search_choices.find("li.search-choice").remove(),this.choices=0):this.is_multiple||(this.selected_item.find("span").text(this.default_text),this.form_field.options.length<=this.disable_search_threshold?this.container.addClass("chzn-container-single-nosearch"):this.container.removeClass("chzn-container-single-nosearch")),a="",f=this.results_data;for(c=0,e=f.length;c<e;c++)b=f[c],b.group?a+=this.result_add_group(b):b.empty||(a+=this.result_add_option(b),b.selected&&this.is_multiple?this.choice_build(b):b.selected&&!this.is_multiple&&(this.selected_item.find("span").text(b.text),this.allow_single_deselect&&this.single_deselect_control_build()));return this.search_field_disabled(),this.show_search_field_default(),this.search_field_scale(),this.search_results.html(a),this.parsing=!1},e.prototype.result_add_group=function(b){return b.disabled?"":(b.dom_id=this.container_id+"_g_"+b.array_index,'<li id="'+b.dom_id+'" class="group-result">'+a("<div />").text(b.label).html()+"</li>")},e.prototype.result_do_highlight=function(a){var b,c,d,e,f;if(a.length){this.result_clear_highlight(),this.result_highlight=a,this.result_highlight.addClass("highlighted"),d=parseInt(this.search_results.css("maxHeight"),10),f=this.search_results.scrollTop(),e=d+f,c=this.result_highlight.position().top+this.search_results.scrollTop(),b=c+this.result_highlight.outerHeight();if(b>=e)return this.search_results.scrollTop(b-d>0?b-d:0);if(c<f)return this.search_results.scrollTop(c)}},e.prototype.result_clear_highlight=function(){return this.result_highlight&&this.result_highlight.removeClass("highlighted"),this.result_highlight=null},e.prototype.results_show=function(){var a;return this.is_multiple||(this.selected_item.addClass("chzn-single-with-drop"),this.result_single_selected&&this.result_do_highlight(this.result_single_selected)),a=this.is_multiple?this.container.height():this.container.height()-1,this.dropdown.css({top:a+"px",left:0}),this.results_showing=!0,this.search_field.focus(),this.search_field.val(this.search_field.val()),this.winnow_results()},e.prototype.results_hide=function(){return this.is_multiple||this.selected_item.removeClass("chzn-single-with-drop"),this.result_clear_highlight(),this.dropdown.css({left:"-9000px"}),this.results_showing=!1},e.prototype.set_tab_index=function(a){var b;if(this.form_field_jq.attr("tabindex"))return b=this.form_field_jq.attr("tabindex"),this.form_field_jq.attr("tabindex",-1),this.is_multiple?this.search_field.attr("tabindex",b):(this.selected_item.attr("tabindex",b),this.search_field.attr("tabindex",-1))},e.prototype.show_search_field_default=function(){return this.is_multiple&&this.choices<1&&!this.active_field?(this.search_field.val(this.default_text),this.search_field.addClass("default")):(this.search_field.val(""),this.search_field.removeClass("default"))},e.prototype.search_results_mouseup=function(b){var c;c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first();if(c.length)return this.result_highlight=c,this.result_select(b)},e.prototype.search_results_mouseover=function(b){var c;c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first();if(c)return this.result_do_highlight(c)},e.prototype.search_results_mouseout=function(b){if(a(b.target).hasClass("active-result"))return this.result_clear_highlight()},e.prototype.choices_click=function(b){b.preventDefault();if(this.active_field&&!a(b.target).hasClass("search-choice")&&!this.results_showing)return this.results_show()},e.prototype.choice_build=function(b){var c,d,e=this;return c=this.container_id+"_c_"+b.array_index,this.choices+=1,this.search_container.before('<li class="search-choice" id="'+c+'"><span>'+b.html+'</span><a href="javascript:void(0)" class="search-choice-close" rel="'+b.array_index+'"></a></li>'),d=a("#"+c).find("a").first(),d.click(function(a){return e.choice_destroy_link_click(a)})},e.prototype.choice_destroy_link_click=function(b){return b.preventDefault(),this.is_disabled?b.stopPropagation:(this.pending_destroy_click=!0,this.choice_destroy(a(b.target)))},e.prototype.choice_destroy=function(a){return this.choices-=1,this.show_search_field_default(),this.is_multiple&&this.choices>0&&this.search_field.val().length<1&&this.results_hide(),this.result_deselect(a.attr("rel")),a.parents("li").first().remove()},e.prototype.results_reset=function(b){this.form_field.options[0].selected=!0,this.selected_item.find("span").text(this.default_text),this.show_search_field_default(),a(b.target).remove(),this.form_field_jq.trigger("change");if(this.active_field)return this.results_hide()},e.prototype.result_select=function(a){var b,c,d,e;if(this.result_highlight)return b=this.result_highlight,c=b.attr("id"),this.result_clear_highlight(),this.is_multiple?this.result_deactivate(b):(this.search_results.find(".result-selected").removeClass("result-selected"),this.result_single_selected=b),b.addClass("result-selected"),e=c.substr(c.lastIndexOf("_")+1),d=this.results_data[e],d.selected=!0,this.form_field.options[d.options_index].selected=!0,this.is_multiple?this.choice_build(d):(this.selected_item.find("span").first().text(d.text),this.allow_single_deselect&&this.single_deselect_control_build()),(!a.metaKey||!this.is_multiple)&&this.results_hide(),this.search_field.val(""),this.form_field_jq.trigger("change"),this.search_field_scale()},e.prototype.result_activate=function(a){return a.addClass("active-result")},e.prototype.result_deactivate=function(a){return a.removeClass("active-result")},e.prototype.result_deselect=function(b){var c,d;return d=this.results_data[b],d.selected=!1,this.form_field.options[d.options_index].selected=!1,c=a("#"+this.container_id+"_o_"+b),c.removeClass("result-selected").addClass("active-result").show(),this.result_clear_highlight(),this.winnow_results(),this.form_field_jq.trigger("change"),this.search_field_scale()},e.prototype.single_deselect_control_build=function(){if(this.allow_single_deselect&&this.selected_item.find("abbr").length<1)return this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>')},e.prototype.winnow_results=function(){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r;this.no_results_clear(),i=0,j=this.search_field.val()===this.default_text?"":a("<div/>").text(a.trim(this.search_field.val())).html(),f=new RegExp("^"+j.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),"i"),m=new RegExp(j.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),"i"),r=this.results_data;for(n=0,p=r.length;n<p;n++){c=r[n];if(!c.disabled&&!c.empty)if(c.group)a("#"+c.dom_id).css("display","none");else if(!this.is_multiple||!c.selected){b=!1,h=c.dom_id,g=a("#"+h);if(f.test(c.html))b=!0,i+=1;else if(c.html.indexOf(" ")>=0||c.html.indexOf("[")===0){e=c.html.replace(/\[|\]/g,"").split(" ");if(e.length)for(o=0,q=e.length;o<q;o++)d=e[o],f.test(d)&&(b=!0,i+=1)}b?(j.length?(k=c.html.search(m),l=c.html.substr(0,k+j.length)+"</em>"+c.html.substr(k+j.length),l=l.substr(0,k)+"<em>"+l.substr(k)):l=c.html,g.html(l),this.result_activate(g),c.group_array_index!=null&&a("#"+this.results_data[c.group_array_index].dom_id).css("display","list-item")):(this.result_highlight&&h===this.result_highlight.attr("id")&&this.result_clear_highlight(),this.result_deactivate(g))}}return i<1&&j.length?this.no_results(j):this.winnow_results_set_highlight()},e.prototype.winnow_results_clear=function(){var b,c,d,e,f;this.search_field.val(""),c=this.search_results.find("li"),f=[];for(d=0,e=c.length;d<e;d++)b=c[d],b=a(b),b.hasClass("group-result")?f.push(b.css("display","auto")):!this.is_multiple||!b.hasClass("result-selected")?f.push(this.result_activate(b)):f.push(void 0);return f},e.prototype.winnow_results_set_highlight=function(){var a,b;if(!this.result_highlight){b=this.is_multiple?[]:this.search_results.find(".result-selected.active-result"),a=b.length?b.first():this.search_results.find(".active-result").first();if(a!=null)return this.result_do_highlight(a)}},e.prototype.no_results=function(b){var c;return c=a('<li class="no-results">'+this.results_none_found+' "<span></span>"</li>'),c.find("span").first().html(b),this.search_results.append(c)},e.prototype.no_results_clear=function(){return this.search_results.find(".no-results").remove()},e.prototype.keydown_arrow=function(){var b,c;this.result_highlight?this.results_showing&&(c=this.result_highlight.nextAll("li.active-result").first(),c&&this.result_do_highlight(c)):(b=this.search_results.find("li.active-result").first(),b&&this.result_do_highlight(a(b)));if(!this.results_showing)return this.results_show()},e.prototype.keyup_arrow=function(){var a;if(!this.results_showing&&!this.is_multiple)return this.results_show();if(this.result_highlight)return a=this.result_highlight.prevAll("li.active-result"),a.length?this.result_do_highlight(a.first()):(this.choices>0&&this.results_hide(),this.result_clear_highlight())},e.prototype.keydown_backstroke=function(){return this.pending_backstroke?(this.choice_destroy(this.pending_backstroke.find("a").first()),this.clear_backstroke()):(this.pending_backstroke=this.search_container.siblings("li.search-choice").last(),this.pending_backstroke.addClass("search-choice-focus"))},e.prototype.clear_backstroke=function(){return this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus"),this.pending_backstroke=null},e.prototype.keydown_checker=function(a){var b,c;b=(c=a.which)!=null?c:a.keyCode,this.search_field_scale(),b!==8&&this.pending_backstroke&&this.clear_backstroke();switch(b){case 8:this.backstroke_length=this.search_field.val().length;break;case 9:this.results_showing&&!this.is_multiple&&this.result_select(a),this.mouse_on_container=!1;break;case 13:a.preventDefault();break;case 38:a.preventDefault(),this.keyup_arrow();break;case 40:this.keydown_arrow()}},e.prototype.search_field_scale=function(){var b,c,d,e,f,g,h,i,j;if(this.is_multiple){d=0,h=0,f="position:absolute; left: -1000px; top: -1000px; display:none;",g=["font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"];for(i=0,j=g.length;i<j;i++)e=g[i],f+=e+":"+this.search_field.css(e)+";";return c=a("<div />",{style:f}),c.text(this.search_field.val()),a("body").append(c),h=c.width()+25,c.remove(),h>this.f_width-10&&(h=this.f_width-10),this.search_field.css({width:h+"px"}),b=this.container.height(),this.dropdown.css({top:b+"px"})}},e.prototype.generate_random_id=function(){var b;b="sel"+this.generate_random_char()+this.generate_random_char()+this.generate_random_char();while(a("#"+b).length>0)b+=this.generate_random_char();return b},e}(AbstractChosen),c=function(a){var b;return b=a.outerWidth()-a.width()},d.get_side_border_padding=c}.call(this);

 /*
 * TipTip
 * Copyright 2010 Drew Wilson
 * www.drewwilson.com
 * code.drewwilson.com/entry/tiptip-jquery-plugin
 *
 * Version 1.3   -   Updated: Mar. 23, 2010
 *
 * This Plug-In will create a custom tooltip to replace the default
 * browser tooltip. It is extremely lightweight and very smart in
 * that it detects the edges of the browser window and will make sure
 * the tooltip stays within the current window size. As a result the
 * tooltip will adjust itself to be displayed above, below, to the left 
 * or to the right depending on what is necessary to stay within the
 * browser window. It is completely customizable as well via CSS.
 *
 * This TipTip jQuery plug-in is dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
(function($){$.fn.tipTip=function(options){var defaults={activation:"hover",keepAlive:false,maxWidth:"200px",edgeOffset:3,defaultPosition:"bottom",delay:400,fadeIn:200,fadeOut:200,attribute:"title",content:false,enter:function(){},exit:function(){}};var opts=$.extend(defaults,options);if($("#tiptip_holder").length<=0){var tiptip_holder=$('<div id="tiptip_holder" style="max-width:'+opts.maxWidth+';"></div>');var tiptip_content=$('<div id="tiptip_content"></div>');var tiptip_arrow=$('<div id="tiptip_arrow"></div>');$("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow.html('<div id="tiptip_arrow_inner"></div>')))}else{var tiptip_holder=$("#tiptip_holder");var tiptip_content=$("#tiptip_content");var tiptip_arrow=$("#tiptip_arrow")}return this.each(function(){var org_elem=$(this);if(opts.content){var org_title=opts.content}else{var org_title=org_elem.attr(opts.attribute)}if(org_title!=""){if(!opts.content){org_elem.removeAttr(opts.attribute)}var timeout=false;if(opts.activation=="hover"){org_elem.hover(function(){active_tiptip()},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}else if(opts.activation=="focus"){org_elem.focus(function(){active_tiptip()}).blur(function(){deactive_tiptip()})}else if(opts.activation=="click"){org_elem.click(function(){active_tiptip();return false}).hover(function(){},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}function active_tiptip(){opts.enter.call(this);tiptip_content.html(org_title);tiptip_holder.hide().removeAttr("class").css("margin","0");tiptip_arrow.removeAttr("style");var top=parseInt(org_elem.offset()['top']);var left=parseInt(org_elem.offset()['left']);var org_width=parseInt(org_elem.outerWidth());var org_height=parseInt(org_elem.outerHeight());var tip_w=tiptip_holder.outerWidth();var tip_h=tiptip_holder.outerHeight();var w_compare=Math.round((org_width-tip_w)/2);var h_compare=Math.round((org_height-tip_h)/2);var marg_left=Math.round(left+w_compare);var marg_top=Math.round(top+org_height+opts.edgeOffset);var t_class="";var arrow_top="";var arrow_left=Math.round(tip_w-12)/2;if(opts.defaultPosition=="bottom"){t_class="_bottom"}else if(opts.defaultPosition=="top"){t_class="_top"}else if(opts.defaultPosition=="left"){t_class="_left"}else if(opts.defaultPosition=="right"){t_class="_right"}var right_compare=(w_compare+left)<parseInt($(window).scrollLeft());var left_compare=(tip_w+left)>parseInt($(window).width());if((right_compare&&w_compare<0)||(t_class=="_right"&&!left_compare)||(t_class=="_left"&&left<(tip_w+opts.edgeOffset+5))){t_class="_right";arrow_top=Math.round(tip_h-13)/2;arrow_left=-12;marg_left=Math.round(left+org_width+opts.edgeOffset);marg_top=Math.round(top+h_compare)}else if((left_compare&&w_compare<0)||(t_class=="_left"&&!right_compare)){t_class="_left";arrow_top=Math.round(tip_h-13)/2;arrow_left=Math.round(tip_w);marg_left=Math.round(left-(tip_w+opts.edgeOffset+5));marg_top=Math.round(top+h_compare)}var top_compare=(top+org_height+opts.edgeOffset+tip_h+8)>parseInt($(window).height()+$(window).scrollTop());var bottom_compare=((top+org_height)-(opts.edgeOffset+tip_h+8))<0;if(top_compare||(t_class=="_bottom"&&top_compare)||(t_class=="_top"&&!bottom_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_top"}else{t_class=t_class+"_top"}arrow_top=tip_h;marg_top=Math.round(top-(tip_h+5+opts.edgeOffset))}else if(bottom_compare|(t_class=="_top"&&bottom_compare)||(t_class=="_bottom"&&!top_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_bottom"}else{t_class=t_class+"_bottom"}arrow_top=-12;marg_top=Math.round(top+org_height+opts.edgeOffset)}if(t_class=="_right_top"||t_class=="_left_top"){marg_top=marg_top+5}else if(t_class=="_right_bottom"||t_class=="_left_bottom"){marg_top=marg_top-5}if(t_class=="_left_top"||t_class=="_left_bottom"){marg_left=marg_left+5}tiptip_arrow.css({"margin-left":arrow_left+"px","margin-top":arrow_top+"px"});tiptip_holder.css({"margin-left":marg_left+"px","margin-top":marg_top+"px"}).attr("class","tip"+t_class);if(timeout){clearTimeout(timeout)}timeout=setTimeout(function(){tiptip_holder.stop(true,true).fadeIn(opts.fadeIn)},opts.delay)}function deactive_tiptip(){opts.exit.call(this);if(timeout){clearTimeout(timeout)}tiptip_holder.fadeOut(opts.fadeOut)}}})}})(jQuery);

/* TINY SORT */
(function(e){var a=false,g=null,f=parseFloat,b=/(\d+\.?\d*)$/g;e.tinysort={id:"TinySort",version:"1.2.18",copyright:"Copyright (c)2008-2012 Ron Valstar",uri:"http://tinysort.sjeiti.com/",licenced:{MIT:"http://www.opensource.org/licenses/mit-license.php",GPL:"http://www.gnu.org/licenses/gpl.html"},defaults:{order:"asc",attr:g,data:g,useVal:a,place:"start",returns:a,cases:a,forceStrings:a,sortFunction:g}};e.fn.extend({tinysort:function(m,h){if(m&&typeof(m)!="string"){h=m;m=g}var n=e.extend({},e.tinysort.defaults,h),s,B=this,x=e(this).length,C={},p=!(!m||m==""),q=!(n.attr===g||n.attr==""),w=n.data!==g,j=p&&m[0]==":",k=j?B.filter(m):B,r=n.sortFunction,v=n.order=="asc"?1:-1,l=[];if(!r){r=n.order=="rand"?function(){return Math.random()<0.5?1:-1}:function(F,E){var i=!n.cases?d(F.s):F.s,K=!n.cases?d(E.s):E.s;if(!n.forceStrings){var H=i.match(b),G=K.match(b);if(H&&G){var J=i.substr(0,i.length-H[0].length),I=K.substr(0,K.length-G[0].length);if(J==I){i=f(H[0]);K=f(G[0])}}}return v*(i<K?-1:(i>K?1:0))}}B.each(function(G,H){var I=e(H),E=p?(j?k.filter(H):I.find(m)):I,J=w?E.data(n.data):(q?E.attr(n.attr):(n.useVal?E.val():E.text())),F=I.parent();if(!C[F]){C[F]={s:[],n:[]}}if(E.length>0){C[F].s.push({s:J,e:I,n:G})}else{C[F].n.push({e:I,n:G})}});for(s in C){C[s].s.sort(r)}for(s in C){var y=C[s],A=[],D=x,u=[0,0],z;switch(n.place){case"first":e.each(y.s,function(E,F){D=Math.min(D,F.n)});break;case"org":e.each(y.s,function(E,F){A.push(F.n)});break;case"end":D=y.n.length;break;default:D=0}for(z=0;z<x;z++){var o=c(A,z)?!a:z>=D&&z<D+y.s.length,t=(o?y.s:y.n)[u[o?0:1]].e;t.parent().append(t);if(o||!n.returns){l.push(t.get(0))}u[o?0:1]++}}return B.pushStack(l)}});function d(h){return h&&h.toLowerCase?h.toLowerCase():h}function c(j,m){for(var k=0,h=j.length;k<h;k++){if(j[k]==m){return !a}}return a}e.fn.TinySort=e.fn.Tinysort=e.fn.tsort=e.fn.tinysort})(jQuery);

/*
	jQuery Calendar
	http://eisabainyo.net/demo/jquery.calendar-widget.php
*/
(function(g){function f(a,b){var c=[31,28,31,30,31,30,31,31,30,31,30,31];return 1==a&&0==b%4&&(0!=b%100||0==b%400)?29:c[a]}g.fn.calendarWidget=function(a){var b=new Date,c=b.getMonth(),b=b.getYear()+1900,c={month:c,year:b};g.extend(c,a);var e="Sun,Mon,Tue,Wed,Thu,Fri,Sat".split(",");month=b=parseInt(c.month);year=parseInt(c.year);a=""+('<h4 id="current-month">'+"January,February,March,April,May,June,July,August,September,October,November,December".split(",")[month]+" "+
year+"</h4>");a=a+('<table class="calendar-month " id="calendar-month'+b+' " cellspacing="0">')+"<tr>";for(d=0;7>d;d++)a+='<th class="weekday">'+e[d]+"</th>";a+="</tr>";f(month,year);b=new Date(year,month,1);c=b.getDay();e=f(month,year);b=new Date(year,month,1);c=b.getDay();e=0==month?11:month-1;e=f(e,11==e?year-1:year);c=0==c&&b?7:c;for(j=b=0;42>j;j++)j<c?a+='<td class="other-month"><span class="day">'+(e-c+j+1)+"</span></td>":j>=c+f(month,year)?(b+=1,a+='<td class="other-month"><span class="day">'+
b+"</span></td>"):a+='<td class="current-month day'+(j-c+1)+'"><span class="day">'+(j-c+1)+"</span></td>",6==j%7&&(a+="</tr>");this.html(a+"</table>");return this}})(jQuery);
/* scripts2.js */
/* The source code packaged with this file is Free Software, Copyright (C)2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

// VARIABLES
pnick = '';
whois_cache = new Array();
chat_msg_ID = new Array();
p_st = '';

// ON LOAD
$(document).ready(function(){
	
	// Reemplazo de emoticonos, etc.
	$(".rich").each(function (i){ $(this).html(enriquecer($(this).html(), true)); });

	// Botones HTML de votos +1 -1
	$(".votar").each(function (i){
		var tipo = $(this).attr("type");
		var item_ID = $(this).attr("name");
		var voto = parseInt($(this).attr("value"));
		if (voto == 1){ var c_mas = " checked=\"checked\""; }
		if (voto == -1){ var c_menos = " checked=\"checked\""; }
		var radio_ID = tipo + item_ID;
		$(this).html("+<input type=\"radio\" class=\"radio_" + radio_ID + "\" name=\"radio_" + radio_ID + "\" onclick=\"votar(1, '" + tipo + "', '" + item_ID + "');\"" + c_mas + " /><input type=\"radio\" class=\"radio_" + radio_ID + "\" name=\"radio_" + radio_ID + "\" onclick=\"votar(-1, '" + tipo + "', '" + item_ID + "');\"" + c_menos + " />&#8211;"); 
	});

	search_timers();
	setInterval("search_timers()", 60000); // Actualiza temporizadores, cada 1 minuto.

	// Efecto scroll horizontal de Notificaciones.
	if (p_scroll == true){ 
		p_r = false;
		pl = 0;
		if (Math.floor(Math.random()*2)== 1){ p_r = true; } // Deslizado izquierda/derecha aleatorio.
		var p_st = setInterval("pscr()", 95); // Inicia deslizado cada 95ms (10 veces por segundo)
		var p_st_close = setTimeout("pscr_close()", 180000); // Detiene deslizado tras 3 min.
	}

	// Popup de info de ciudadanos.
	$(".nick").mouseover(function(){
		var wnick = $(this).text();
		if (!whois_cache[wnick]){ pnick = setTimeout(function(){ $.post("/ajax.php", { a: "whois", nick: wnick }, function(data){ $("#pnick").css("display","none"); whois_cache[wnick] = data; print_whois(data, wnick); }); }, 500);
		} else { print_whois(whois_cache[wnick], wnick); }
	}).mouseout(function(){ clearTimeout(pnick); pnick = ""; $("#pnick").css("display","none"); });
	$(document).mousemove(function(e){ $("#pnick").css({top: e.pageY + "px", left: e.pageX + 15 + "px"}); });

	// Mensajes emergentes de ayuda.
	$(".ayuda").hover(
		function (){
			var txt = $(this).attr("value");
			$(this).append('<span class="ayudap">' + txt + '</span>');
		}, 
		function (){ $(".ayudap").remove(); }
	);

	setInterval("actualizar_noti()", 180000); // Actualiza notificaciones cada 3 min.
});



/*** FUNCIONES ***/


function pscr(){
	// Esta funcion es critica, debe optimizarse al máximo. Se ejecuta 10 veces por segundo.
	if (p_scroll == true){
		if (p_r == true){ pl++; } else { pl--; }
		document.getElementById('menu-noti').style.backgroundPosition = pl + 'px 0';
	} else { if (p_st){ clearInterval(p_st); } }
}
function pscr_close(){ p_scroll = false; }

function actualizar_noti(){ 
	$('#notif').load('/ajax.php?a=noti');
	if (p_scroll == false){ clearInterval(p_st); }
	search_timers();
}

function votar(voto, tipo, item_ID){
	var radio_ID = tipo + item_ID;
	$(".radio_" + radio_ID).blur();
	var voto_pre = parseInt($("#data_" + radio_ID).attr("value"));
	if (voto_pre == voto){ voto = 0; $(".radio_" + radio_ID).removeAttr("checked"); }
	$.get("/accion.php", { a: "voto", tipo: tipo, item_ID: item_ID, voto: voto }, function(data){
		if (data){
			if (data == "false"){
				$(".radio_" + radio_ID).removeAttr("checked");
				alert("El voto no se ha podido realizar.");
			} else if (data == "limite"){
				$(".radio_" + radio_ID).removeAttr("checked");
				alert("Has llegado al limite de votos emitibles.");
			} else {
				if (tipo != "confianza"){ $("#" + radio_ID).html(print_votonum(data)); }
				$("#data_" + radio_ID).attr("value", voto);
			}
		}
	});
}
function print_votonum(num){
	var num = parseInt(num);
	if (num >= 10){ return "<span class=\"vcc\">+" + num + "</span>"; }
	else if (num >= 0){ return "<span class=\"vc\">+" + num + "</span>"; } 
	else if (num > -10){ return "<span class=\"vcn\">" + num + "</span>"; }
	else { return "<span class=\"vcnn\">" + num + "</span>"; }
}



function print_whois(whois, wnick){
	var w = whois.split(":"); print_votonum(w[13])
	if (!whois){ $("#pnick").html("&dagger;"); } else {
	if (w[6] == 1){ var wa = "<img src=\"" + IMG + "a/" + w[0] + ".jpg\" style=\"float:right;margin:0 -6px 0 0;\" />"; } else { var wa = ""; }
	if (w[11] != 0){ var wc = "<img src=\"" + IMG + "cargos/" + w[11] + ".gif\" width=\"16\" /> "; } else { var wc = ""; }
	if (w[9] == "expulsado"){ var exp = "<br /><b style=\"color:red;\">" + w[12] + "</b>"; } else { var exp = ""; }
		
		$("#pnick").html("<legend>" + wc + "<b style=\"color:grey;\"><span style=\"color:#555;\">" + wnick + "</span> (<span class=\"" + w[9] + "\">" + w[9].substr(0,1).toUpperCase()+ w[9].substr(1,w[9].length)+ "</span> de " + w[10] + ")</b>" + exp + "</legend>" + wa + "Confianza: " + print_votonum(w[13])+ "<br /><!--Afil: <b>" + w[7] + "</b><br />-->Foro: <b>" + w[8] + "</b><br /><br />Online: <b>" + w[5] + "</b><br />Ultimo acceso: <b>" + w[2] + "</b><br />Registrado: <b>" + w[1] + "</b>").css("display","inline");
	}
}


function search_timers(){
	var ts = Math.round((new Date()).getTime()/ 1000);
	$(".timer").each(function (i){
		var cuando = $(this).attr("value");
		$(this).text(hace(cuando, ts, 1, false));
	});
}

function hace(cuando, ts, num, pre){
	tiempo = (cuando - ts);
	if (pre){ if (tiempo >= 0){ pre = _["En"]; } else { pre = _["Hace"]; } }
	tiempo = Math.abs(tiempo);
	
	var periods_sec = new Array(2419200, 86400, 3600, 60, 1);
	var periods_txt = new Array(_["meses"], _["días"], _["horas"], _["min"], _["seg"]);

	if (pre){ var duracion = pre + " "; } else { var duracion = ""; }

	tiempo_cont = tiempo;
	nm = 0;
	for (n in periods_sec){
		sec = periods_sec[n];
		if ((nm < num)&& ((tiempo_cont >= (sec*2))|| (n == 4))){
			period = Math.floor(tiempo_cont / sec);
			if (n == 4){ 
				duracion += _["Segundos"];
			} else {
				duracion += period + " " + periods_txt[n];
			}
			if ((num != 1)&& (n != 4)){ if (n != 3){ duracion += ", "; } else { duracion += " y "; } }
			tiempo_cont = tiempo_cont - (period * sec);
			nm++;
		}
	}
	return duracion;
}






// FUNCIONES CHAT START

function actualizar_ahora(){
	chat_delay = 4000;
	refresh = setTimeout(chat_query_ajax, chat_delay);
	delays();
	chat_query_ajax();
	scroll_abajo();
	$("#vpc_msg").focus();
}

function scroll_abajo(){
	if (chat_scroll <= document.getElementById("vpc").scrollTop){
		document.getElementById("vpc").scrollTop = 90000000;
		chat_scroll = document.getElementById("vpc").scrollTop;
	}
}

function siControlPulsado(event, nick){
	if (event.ctrlKey==1){ toggle_ignorados(nick); return false; }
}

function toggle_ignorados(nick){
	var idx = $.inArray(nick, array_ignorados);
	if (idx != -1){
		array_ignorados.splice(idx, 1);
		$("."+nick).show();
		scroll_abajo();
	} else {
		array_ignorados.push(nick); 
		$("."+nick).hide();
	}
	merge_list();
	chat_scroll = 0;
	scroll_abajo();
}

function cf_cambiarnick(){
	nick_anonimo = $("#cf_nick").val();
	nick_anonimo = nick_anonimo.replace(/[^A-Za-z0-9_-]/g, "");
	if ((nick_anonimo)&& (nick_anonimo.length >= 3)&& (nick_anonimo.length <= 14)){
		elnick = "-" + nick_anonimo.replace(" ", "_"); 
		anonimo = elnick;
		$("#cf").hide();
		$("#chatform").show();
	} else { $("#cf_nick").val(""); }
}

function chat_filtro_change(){
	if (chat_filtro == "normal"){
		chat_filtro = "solochat";
		$(".cf_c, .cf_e").hide();
	} else {
		chat_filtro = "normal";
		$(".cf_c, .cf_e").show();	
	}
	chat_scroll = 0;
	scroll_abajo();
	$("#vpc_msg").focus();
}

function msgkeyup(evt, elem){
	if ($(elem).attr("value").substr(0,1)== "/"){
		$(elem).css("background", "#FF7777").css("color", "#952500");
	} else {
		$(elem).css("background", "none").css("color", "black");
	}
}

function msgkeydown(evt, elem){
	obj = elem;
	var keyCode;
	if ("which" in evt){ keyCode=evt.which; }
	else if ("keyCode" in evt){ keyCode=evt.keyCode; }
	else if ("keyCode" in window.event){ keyCode=window.event.keyCode; }
	else if ("which" in window.event){ keyCode=evt.which; }
	if (keyCode == 9){
		var elmsg = $(elem).attr("value");
		var array_elmsg = elmsg.split(" ");
		var elmsg_num = array_elmsg.length;
		var palabras = "";
		for (i=0;i<elmsg_num;i++){
			if (i == (elmsg_num - 1)){ var ultima_palabra = array_elmsg[i].toLowerCase(); } else { palabras += array_elmsg[i] + " "; }
		}
		if (ultima_palabra){
			var len_ultima_palabra = ultima_palabra.length;
			for (elnick in al){
				var elmnick = elnick.toLowerCase();
				if (ultima_palabra == elmnick.substr(0, len_ultima_palabra)){
					if (palabras){ obj.value = palabras + elnick + " "; } else { obj.value = elnick + " "; }
				}
			}
		}
		setTimeout("obj.focus()", 10);
	}
}

function chat_query_ajax(){
	if (ajax_refresh){
		ajax_refresh = false;
		clearTimeout(refresh);
		var start = new Date().getTime();
		$("#vpc_actividad").attr("src", IMG + "ico/punto_azul.png");
		$.post("/ajax.php", { chat_ID: chat_ID, n: msg_ID },
			function(data){
				ajax_refresh = true;
				if (data){ print_msg(data); }
				refresh = setTimeout(chat_query_ajax, chat_delay);
				var elapsed = new Date().getTime()- start;
				$("#vpc_actividad").attr("src", IMG + "ico/punto_gris.png").attr("title", "Chat actualizado (" + (chat_delay/1000)+ " segundos, " + elapsed + "ms)");
			}
		);
		if (ajax_refresh == true){ merge_list(); }
	}
}

function refresh_sin_leer(){ document.title = chat_sin_leer_yo + chat_sin_leer + " - " + titulo_html; }

function print_msg(data){
	if (ajax_refresh){
		var chat_sin_leer_antes = chat_sin_leer;
		var escondidos = new Array();
		var arraydata = data.split("\n");
		var msg_num = arraydata.length - 1;
		var list = "";
		for (i=0;i<msg_num;i++){
			var mli = arraydata[i].split(" ");
			var txt = ""; var ml = mli.length; for (var e=4; e<ml; e++){ txt += mli[e] + " "; }

			var m_ID = mli[0];
			var m_tipo = mli[1];
			var m_time = mli[2];
			var m_nick = mli[3];

			if (!chat_msg_ID[m_ID]){
				
				chat_msg_ID[m_ID] = true;

				if (chat_time == m_time){ m_time = "<span style=\"color:#eee;\">" + m_time + "</span>"; } else { chat_time = m_time; }

				switch(m_tipo){
					case "c":
					case "e":
						list += "<li id=\"" + m_ID + "\" class=\"cf_" + m_tipo + "\">" + m_time + " <span class=\"vpc_accion\">" + txt + "</span></li>\n";
						m_tipo = "0";
						break;

					case "p":
						if ((m_nick == minick)&& (mli[4] == "<b>Nuevo")){ } else {
							var nick_solo = m_nick.split("&rarr;");
							var nick_s = nick_solo[0];
							if (minick == nick_s){
								list += "<li id=\"" + m_ID + "\" class=\"" + nick_s + "\">" + m_time + " <span class=\"vpc_priv\" style=\"color:#004FC6\" ;OnClick=\"auto_priv(\'" + nick_s + "\');\"><b>[PRIV] " + m_nick + "</b>: " + txt + "</span></li>\n";
							} else {
								list += "<li id=\"" + m_ID + "\" class=\"" + nick_s + "\">" + m_time + " <span class=\"vpc_priv\" OnClick=\"auto_priv(\'" + nick_s + "\');\"><b>[PRIV] " + m_nick + "</b>: " + txt + "</span></li>\n";
								chat_sin_leer_yo = chat_sin_leer_yo + "+";
							}
						}
						var nick_p = m_nick.split("&rarr"); m_nick = nick_p[0]; m_tipo = "0";
						break;

					default:
						if (minick != ""){ 
							var txt = " " + txt;
							var regexp = eval("/ "+minick+"/gi");
							var txt = txt.replace(regexp, " <b style=\"color:orange;\">" + minick + "</b>"); 
							if (txt.search(regexp)!= -1){ chat_sin_leer_yo = chat_sin_leer_yo + "+"; } 
						}

						var vpc_yo = "";
						if (minick == m_nick){ var vpc_yo = " class=\"vpc_yo\""; }
						if (m_tipo.substr(0,3)== "98_"){ var cargo_ID = 98; } else { var cargo_ID = m_tipo; }
						list += "<li id=\"" + m_ID + "\" class=\"" + m_nick + "\">" + m_time + " <img src=\""+IMG+"cargos/" + cargo_ID + ".gif\" width=\"16\" height=\"16\" title=\"" + array_cargos[cargo_ID] + "\" /> <b" + vpc_yo + " OnClick=\"auto_priv(\'" + m_nick + "\');\">" + m_nick + "</b>: " + txt + "</li>\n";
				}

				if (((msg_num - 1)== i)&& (msg_num != "n")&& (m_nick != "&nbsp;")){ msg_ID = m_ID; }
				if ((m_tipo != "c")&& (m_nick != "_")&& (m_nick != "")){ 
					al[m_nick] = parseInt(new Date().getTime().toString().substring(0, 10));
					if ((al_cargo[m_nick] == "0")|| (!al_cargo[m_nick])){ al_cargo[m_nick] = m_tipo; }
				}

				var idx = $.inArray(m_nick, array_ignorados);
				if (idx != -1){ escondidos.push(m_ID); } else { chat_sin_leer++; }
			}
		}

		$("#vpc_ul").append(enriquecer(list, false));
		merge_list();
		if ((chat_sin_leer > 0)|| (chat_sin_leer_antes == -1)){
			refresh_sin_leer();
			print_delay();
		}
		for (var i=0;i<escondidos.length;i++){ $("#"+escondidos[i]).hide(); }
	}
}

function merge_list(){
	var unix_timestamp = parseInt(new Date().getTime().toString().substring(0, 10));
	var times_exp = parseInt(unix_timestamp - 1500); //25min
	
	array_list = new Array();
	for (elnick in al){
		if (al[elnick] < times_exp){
			al[elnick] = null;
			al_cargo[elnick] = null;
		} else {
			var cargo_ID = al_cargo[elnick];

			if (cargo_ID.substr(0,3)== "98_"){ var kick_nick  = "ip-" + cargo_ID.substr(3); } 
			else { var kick_nick  = elnick; }

			var idx = $.inArray(elnick, array_ignorados);
			if (idx != -1){ nick_tachado = "<strike>" + elnick + "</strike>"; } else { nick_tachado = elnick; }
			
			if (array_list[cargo_ID] === undefined){ array_list[cargo_ID] = ""; }

			if (hace_kick){
				js_kick = "<a href=\"/control/kick/" + kick_nick  + "/" + chat_ID  + "\" target=\"_blank\"><img src=\"" + IMG + "varios/kick.gif\" title=\"Kickear\" alt=\"Kickear\" border=\"0\" /></a>";
			} else {
				js_kick = "";
			}

			array_list[cargo_ID] += "<li>" + js_kick + " <img src=\""+IMG+"cargos/" + cargo_ID + ".gif\" title=\"" + array_cargos[cargo_ID] + "\" /> <a href=\"/perfil/" + elnick  + "\" class=\"nick\" onClick=\"siControlPulsado(event,\'"+ elnick +"\');\" target=\"_blank\">" + nick_tachado + "</a></li>\n";
		}
	}

	var list = "";

	for (cargo_ID in array_cargos){
		if ((array_list[cargo_ID] !== undefined)&& (cargo_ID > 0)){ list += array_list[cargo_ID]; }
	}
	for (cargo_ID in array_cargos){
		if ((array_list[cargo_ID] !== undefined)&& (cargo_ID == 0)){ list += array_list[cargo_ID]; }
	}

	$("#chat_list").html(list);
}



function print_delay(){
	if (chat_filtro == "solochat"){ $(".cf_c, .cf_e").hide(); }
	$("#vpc_msg").focus();
	scroll_abajo();
}



function enviarmsg(){
 	var elmsg = $("#vpc_msg").attr("value");
	var boton_envia_estado = $("#botonenviar").attr("disabled");
	$("#vpc_actividad").attr("src", IMG + "ico/punto_rojo.png");
	if ((elmsg)&& (boton_envia_estado != "disabled")){
		ajax_refresh = false;
		clearTimeout(refresh);
		$("#botonenviar").attr("disabled","disabled");
		$("#vpc_msg").attr("value","").css("background", "none").css("color", "black");
		$.post("/ajax.php", { a: "enviar", chat_ID: chat_ID, n: msg_ID, msg: elmsg, anonimo: anonimo }, 
		function(data){ 
			ajax_refresh = true;
			if (data){ chat_sin_leer = -1; print_msg(data); }
			setTimeout(function(){ $("#botonenviar").removeAttr("disabled"); }, 1600);
			chat_delay = 4000;
			refresh = setTimeout(chat_query_ajax, chat_delay);
			delays();
			$("#vpc_actividad").attr("src", IMG + "ico/punto_gris.png");
		});
	}
	return false;
}

function change_delay(delay){ chat_delay = parseInt(delay)* parseInt(1000); }

function delays(){
	if (chat_delay1){ clearTimeout(chat_delay1); } chat_delay1 = setTimeout("change_delay(6)", 25000);
	if (chat_delay2){ clearTimeout(chat_delay2); } chat_delay2 = setTimeout("change_delay(10)", 60000);
	if (chat_delay3){ clearTimeout(chat_delay3); } chat_delay3 = setTimeout("change_delay(15)", 120000);
	if (chat_delay4){ clearTimeout(chat_delay4); } chat_delay4 = setTimeout("change_delay(60)", 300000);
	if (chat_delay_close){ clearTimeout(chat_delay_close); } chat_delay_close = setTimeout("chat_close()", 7200000); // 2h
}

function chat_close(){
	clearTimeout(refresh);
	$("body").before("<div id=\"chat_alert\" style=\"position:absolute;top:40%;left:40%;\"><button onclick=\"chat_enabled();\" style=\"font-weight:bold;font-size:28px;color:#888;z-index:20px;\">Volver al chat...</button></div>");
}

function chat_enabled(){
	$("#chat_alert").remove();
	chat_query_ajax();
	chat_delay = 4500;
	refresh = setTimeout(chat_query_ajax, chat_delay);
	delays();
	$("#vpc_msg").focus();
	scroll_abajo();
}

function auto_priv(nick){ $("#vpc_msg").attr("value","/msg " + nick + " ").css("background", "#FF7777").css("color", "#952500").focus(); }

// ### FUNCIONES CHAT END


function enriquecer(m, bbcode){

	// Emoticonos
	m = m.replace(/(\s|^):\)/gi,			' <img src="'+IMG+'smiley/sonrie.gif" border="0" alt=":)" title=":)" width="15" height="15" />');
	m = m.replace(/(\s|^):\(/gi,			' <img src="'+IMG+'smiley/disgustado.gif" border="0" alt=":(" title=":(" width="15" height="15" />');
	m = m.replace(/(\s|^):\|/gi,			' <img src="'+IMG+'smiley/desconcertado.gif" border="0" alt=":|" title=":|" width="15" height="15" />');
	m = m.replace(/(\s|^):D/gi,				' <img src="'+IMG+'smiley/xd.gif" alt=":D" border="0" title=":D" width="15" height="15" />');
	m = m.replace(/(\s|^):\*/gi,			' <img src="'+IMG+'smiley/muacks.gif" alt=":*" border="0" title=":*" width="15" height="15" />');
	m = m.replace(/(\s|^);\)/gi,			' <img src="'+IMG+'smiley/guino.gif" alt=";)" border="0" title=";)" width="15" height="15" />');
	m = m.replace(/(\s|^):O/gi,				' <img src="'+IMG+'smiley/bocaabierta.gif" alt=":O" border="0" title=":O" width="15" height="15" />');
	m = m.replace(/:(tarta|roto2|palm|moneda):/gi,	' <img src="'+IMG+'smiley/$1.gif" alt=":$1:" border="0" title=":$1:" width="16" height="16" />');
	m = m.replace(/(\s|^)(:troll:)/gi,			' <img src="'+IMG+'smiley/troll.gif" alt=":troll:" border="0" title=":troll:" width="15" height="15" />');
	m = m.replace(/(\s|^)(:falso:)/gi,			' <img src="'+IMG+'smiley/sonrie.gif" border="0" alt=":falso:" title=":falso:" width="15" height="15" onMouseOver="$(this).attr(\'src\', \''+IMG+'smiley/troll.gif\');" />');


	// URLs
	m = m.replace(/(\s|^|>)(\/[-A-Z0-9\/_]{3,})/ig, ' <a href="$2" target="_blank">$2</a>'); // /url
	m = m.replace(/(\s|^|>)@([-A-Z0-9_]{2,20})/ig, ' <a href="/perfil/$2" class="nick">@<b>$2</b></a>'); // @nick
	m = m.replace(/(\s|^|>)(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, ' <a href="$2" target="_blank">$2</a>');

	// BBCODE
	if (bbcode){
		m = m.replace(/\[(b|i|em|s)\](.*?)\[\/\1\]/gi, '<$1>$2</$1>'); 
		m = m.replace(/\[img\](.*?)\[\/img\]/gi, '<img src="$1" alt="img" style="max-width:800px;" />');
		m = m.replace(/\[youtube\]http\:\/\/www\.youtube\.com\/watch\?v=(.*?)\[\/youtube\]/gi, '<iframe width="520" height="390" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>');
		m = m.replace(/\[quote\]/gi, '<blockquote><div class="quote">');
		m = m.replace(/\[quote=(.*?)\]/gi, '<blockquote><div class="quote"><cite>$1 escribió:</cite>');
		m = m.replace(/\[\/quote\]/gi, '</div></blockquote>');
	}

	// Botones Instant
	if (bbcode){ var boton_width = 50; } else { var boton_width = 16; }
	m = m.replace(/:(aplauso|noo|rickroll|relax|alarmanuclear|porquenotecallas|zas|aleluya):/gi, html_instant('$1', boton_width));

	return m;
}

function html_instant(nom, width){
	return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" title=":' + nom + ':" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="' + width + '" width="' + width + '"><param name="quality" value="high" /><param name="wmode" value="transparent" /><param name="movie" value="' + IMG + 'instant/' + nom + '.swf" /><embed style="margin:0 0 -3px 0;" height="' + width + '" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="' + IMG + 'instant/' + nom + '.swf" type="application/x-shockwave-flash" width="' + width + '" wmode="transparent"></embed></object>';
}
_ = {"Ahora":"Ahora","ahora":"ahora","años":"años","meses":"meses","días":"días","horas":"horas","minutos":"minutos","min":"min","seg":"seg","Pocos segundos":"Pocos segundos","Segundos":"Segundos","En":"En","Hace":"Hace",};