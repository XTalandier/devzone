<?php
if(isset($_GET['file'])){
	echo file_get_contents($_GET['file']);
	exit;
}
if(isset($_POST['file']) && isset($_POST['content'])){
	file_put_contents($_POST['file'], $_POST['content']);
	exit;
}
?>
<html>
<head>
	<META HTTP-EQUIV="CONTENT-TYPE"
		  CONTENT="text/html; charset=UTF-8">
	<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js" type="text/javascript"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<link rel="stylesheet" href="styles/default/default.css" />
<style>
	body {
		margin: 0;
		background-color: #2B292E;
	}
	ul {
		padding-left: 15px;
	}
	#editor {
		height: 100%;
	}
	.pft-file a, .pft-directory a {
		color: #EFEFEF;
	}

	.box-alert {
		position: absolute;
		right: 10px;
		z-index: 9999;
		display: none;
	}

</style>
</head>
<body>
<div class="box-alert alert alert-success" role="alert" id="msg" onclick="$('#msg').hide('fast');"></div>
<div class="row">
	<div class="col-lg-2" style="padding-left: 25px">
		<?php
		require dirname(__FILE__).'/php_file_tree.php';
		echo php_file_tree(dirname(dirname(__FILE__)), "javascript:loadFile('[link]');");
		?>
	</div>
	<div class="col-lg-10">
		<div class="buttons">
			<a href="javascript:save();" class="btn">Enregistrer</a>
		</div>
		<div id="editor"></div>
	</div>
</div>

<script>
	var currentFile = '';
	var editor = ace.edit("editor");
	editor.setTheme("ace/theme/ambiance");
	editor.getSession().setMode("ace/mode/javascript");

	function loadFile(url){
		var type = url.split('.');
		type = type[type.length - 1];
		currentFile = url;
		$.get('?file=' + url, function(data){
			if(type == 'js'){
				type = 'javascript';
			}
			editor.getSession().setMode("ace/mode/" + type);
			editor.getSession().setValue(data);
		});
	}

	function save() {
		$.post('?', {
			file: currentFile,
			content: editor.getSession().getValue()
		},
		function (data) {
			showAlert('Fichier enregistré');
			console.log(data);
		});
	}

	function showAlert(msg){
		$('#msg').html(msg).show('fast');
		setTimeout(function(){
			$('#msg').hide('fast');
		}, 3000);

	}

	$(document).ready(function () {
		loadFile('/Applications/MAMP/htdocs/editor/test.php');
	});

	function init_php_file_tree(){if(!document.getElementsByTagName)return;var e=document.getElementsByTagName("LI");for(var t=0;t<e.length;t++){var n=e[t].className;if(n.indexOf("pft-directory")>-1){var r=e[t].childNodes;for(var i=0;i<r.length;i++){if(r[i].tagName=="A"){r[i].onclick=function(){var e=this.nextSibling;while(1){if(e!=null){if(e.tagName=="UL"){var t=e.style.display=="none";e.style.display=t?"block":"none";this.className=t?"open":"closed";return false}e=e.nextSibling}else{return false}}return false};r[i].className=n.indexOf("open")>-1?"open":"closed"}if(r[i].tagName=="UL")r[i].style.display=n.indexOf("open")>-1?"block":"none"}}}}window.onload=init_php_file_tree
	</script>
</body>
</html>
