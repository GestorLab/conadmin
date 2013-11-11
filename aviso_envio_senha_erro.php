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
					echo "<p id='p1'>Não foi possivel enviar o email para o destinatário informado.</p> 
						  <p id='p2'>Favor entrar em contato com o administrador de seu sistema.</p>";
				}
				if($Motivo == 2){
					echo "<p id='p1'>O usuário não possui e-mail cadastrado em seu cadastrado de pessoa.</p> 
						  <p id='p2'>Favor entrar em contato com o administrador de seu sistema.</p>";
				}
				if($Motivo == 3){
					echo "<p id='p1'>O e-mail informado é diferente do cadastrado em seus dados cadastrais.</p>
						  <p id='p2'>$nbsp</p>";
				}
				if($Motivo == 4){
					echo "<p id='p1'>Esta Solicitação de alteração de senha se encontra cancelada ou já foi concluida!</p>
						  <p id='p2'>$nbsp</p>";
				}
			?>
		</div>		
	</body>
</html>