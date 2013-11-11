<?
	$localModulo	=	2;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('rotinas/verifica.php');
	
	$local_Login = $_SESSION['LoginHD'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/menu.css' />
		<script type = 'text/javascript' src = '../../js/menu.js'></script>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
	</head>
	<body>
		<h1>Menu Principal</h1>
		<ul class='mn'>
			<li onClick="javascript:open_close('mn_cadastro',this)">Help Desk</li>
		</ul>
		<ul id='mn_cadastro'>			
			<li class='n2_end' onClick="parent.conteudo.location='menu_help_desk.php'">Ticket</li>
		</ul>
		<ul class='mn'>
			<li onClick="javascript:open_close('mn_relatorio',this)">Relatório</li>
		</ul>
		<ul id='mn_relatorio'>
			<li class='n2_end' onClick="parent.conteudo.location='listar_help_desk_opcoes.php'">Ticket</li>
		</ul>
		<ul class='mn_normal'>	
			<li onClick='sair()'>Sair</li>
		</ul>
	</body>
</html>
<script>
	function atualizaCabecalho(){
		if(window.parent.cabecalho.document.getElementById('cp_modulo_atual') != undefined){
			parent.cabecalho.location = 'cabecalho.php?Titulo='+window.parent.cabecalho.document.getElementById('cp_modulo_atual').innerHTML;
		}else{
			parent.cabecalho.location = 'cabecalho.php';
		}
		setTimeout("atualizaCabecalho()",<?=getParametroSistema(108,1)*1000?>);
	}
	atualizaCabecalho();
</script>
