<?	
	# Vars Cabeçalho **********************************
#	$local_EtapaProxima		= "verificaCPF.php";
	$local_EtapaAnterior	= "";
	$local_Etapa			= "menu";
	$local_Erro				= $_GET['Erro'];
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');	
	include('files/funcoes.php');	
	include('rotinas/verifica.php');
	
	$local_IdLoja	= $_SESSION["IdLojaCDA"];
	$IdLoja			= $_SESSION["IdLojaCDA"];
	$local_IdPessoa	= $_SESSION["IdPessoaCDA"];	
	# Fim Vars Cabeçalho *******************************	

	$Perfil = logoPerfil();
?>
<html>
	<head>
		<?
			include ("files/header.php");
		?>
	</head>
	<body>
		<div id='carregando'>carregando</div>												
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
		  	<tr>
		    	<td height="20" align="center">
					<div id='cabecalho'>
						<?
							if(file_exists('personalizacao/cabecalho.php')){
								include("personalizacao/cabecalho.php");
							}else{
								include("cabecalho.php");
							}
						?>
					</div>
				</td>
		  	</tr>
			<tr>
		    	<td align="center">
					<div id="geral">
						<div id="main">
							<div id="coluna1">
							<?
								if(file_exists('personalizacao/coluna1.php')){
									include("personalizacao/coluna1.php");
								}else{
									include("coluna1.php");
								}
							?>
							</div>
							<div id="coluna2">
								<?
									if(!$_GET['ctt']){
										include('ctt/index.php');
									}else{
										if(@file_exists("ctt/".$_GET['ctt'])){
											include("ctt/".$_GET['ctt']);
										}else{
											include('ctt/index.php');
										}
									}
								?>
							</div>
						</div>
					</div>
				</td>
		  	</tr>
		  	<tr>
		    	<td height="20" align="center">
					<div id='rodape'>
					<?
						if(file_exists('personalizacao/rodape.php')){
							include("personalizacao/rodape.php");
						}else{
							include("files/rodape.php");
						}
					?>
					</div>
				</td>
		  	</tr>
		</table>
	</body>
</html>