<?
	include("files/conecta.php");
	include("files/funcoes.php");
	
	$Motivo = $_GET['Motivo']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<script type = 'text/javascript' src = 'js/funcoes.js'></script>
		<script type = 'text/javascript' src = 'js/index.js'></script>
		<script type = 'text/javascript' src = 'js/mensagens.js'></script>
		<script type = 'text/javascript' src = 'js/event.js'></script>
		<link REL="SHORTCUT ICON" HREF="img/estrutura_sistema/favicon.ico">
	</head>
	<body>
		<?
			include("files/cabecalho.php");
		?>
		<div id='sem_permissao'>
			<?
				if($Motivo == 1){
					echo "<p id='p1'>N�o foi possivel enviar o email para o destinat�rio informado.</p> 
						  <p id='p2'>Favor entrar em contato com o administrador de seu sistema.</p>";
				}
				if($Motivo == 2){
					echo "<p id='p1'>O usu�rio n�o possui e-mail cadastrado em seu cadastrado de pessoa.</p> 
						  <p id='p2'>Favor entrar em contato com o administrador de seu sistema.</p>";
				}
				if($Motivo == 3){
					echo "<p id='p1'>O e-mail informado � diferente do cadastrado em seus dados cadastrais.</p>
						  <p id='p2'>$nbsp</p>";
				}
				if($Motivo == 4){
					echo "<p id='p1'>Esta Solicita��o de altera��o de senha se encontra cancelada ou j� foi concluida!</p>
						  <p id='p2'>$nbsp</p>";
				}
			?>
		</div>		
	</body>
</html>