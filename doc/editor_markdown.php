<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 
$maxsim['output'] = 'text';
echo '
<script type="text/javascript" src="/img/lib/jquery-1.7.1.min.js"></script>
		
<script src="/doc/easymde.min.js"></script>
<link rel="stylesheet" href="/doc/easymde.min.css">

<textarea id="document_body"></textarea>
<script>
	var easyMDE = new EasyMDE({element: document.getElementById("document_body"),
							   spellChecker: false,
							   autosave: {
								   enabled: false
							   },
							   minHeight: "500px"});
	easyMDE.value(window.parent.$("#html_doc").val());
	easyMDE.codemirror.on("change", function(){
		window.parent.$("#html_doc").val(easyMDE.value());
	});
</script>';
