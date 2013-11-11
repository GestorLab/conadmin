<?
	$localModulo		=	1;
	$localOperacao		=	10001;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Radius )
	//$array_operacao 	= array(  "10000" ) ;
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<style type='text/css'>
			.impress { padding: 0 2px 2px 2px; cursor:pointer; border: 1px #A4A4A4 solid; height: 20px;}
		</style>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media = 'print' />
	</head>
	<body onLoad="ativaNome('Radius/Gráfico de Utilização')">
		<? include('filtro_radius_tempo_conexao.php'); ?>
		<br /><br />
		<div style='text-align:center;'><input class='impress' type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick="javascript:parent.frames['conteudo'].print();"></div>
		<br />
	</body>
</html>
<script>
	function listar(e){
		var e = e || event;
		var k = e.keyCode || e.which;
		if (k==13){
			if(document.filtro.filtro_servidor.value != "" && (document.filtro.filtro_login.value != "" || document.filtro.filtro_mac.value != "") && document.filtro.filtro_mes_referencia.value){
				document.filtro.submit();
			}else{
				alert("Atencao!\nPreencha todos os campos do filtro.");
				return false;
			}
		} 
	}
	function validar(){
		if(document.filtro.filtro_servidor.value != "" && (document.filtro.filtro_login.value != "" || document.filtro.filtro_mac.value != "") && document.filtro.filtro_mes_referencia.value){
			document.filtro.submit();
		}else{
			alert("Atencao!\nPreencha todos os campos do filtro.");
			return false;
		}
	}
</script>
