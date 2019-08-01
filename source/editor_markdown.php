<html>
	<head>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
		<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
		<style>
			body{
				height: 700px;
			}
		</style>
	</head>
	<body>
		<textarea id="document_body"></textarea>
		<script>
			var easyMDE = new EasyMDE({element: document.getElementById('document_body'),
									  spellChecker: false,
									  autosave: {
										enabled: false
									},
									  minHeight: "500px"});
			easyMDE.value(window.parent.$('#html_doc').val());
			easyMDE.codemirror.on("change", function(){
				window.parent.$('#html_doc').val(easyMDE.value());
			});
		</script>
	</body>
</html>