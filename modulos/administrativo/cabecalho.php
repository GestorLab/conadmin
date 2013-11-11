<?
	$localModulo	=	1;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_login 	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION["IdLoja"];

	$local_Titulo = $_GET["Titulo"];

	if($local_Titulo == ''){	$local_Titulo = 'Página Principal';	}

	LogAcessoAtualiza();

	$Perfil   = logoPerfil();
	$IdVersao = versao();
	$IdVersao = $IdVersao[DescricaoVersao];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/cabecalho.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<meta http-equiv="refresh" content="<?=getParametroSistema(108,1)?>">
		<?=getParametroSistema(242,1)?> 
	</head>
	<body>
		<div name='logo_head' style='<?=getParametroSistema(4,2)?>'>
			<a href='index.php' target='_parent' ><img id='logo_head' src='<?=$Perfil[UrlLogoGIF]?>' alt='<?=$Perfil[DescricaoPerfil]?>'/></a>
		</div>
		<div id='info'>
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td class='l1_cl1'><?=getParametroSistema(4,1)?>&nbsp;v. <?=$IdVersao?></td>
					<td class='l1_cl2'><a href='http://www.cntsistemas.com.br' target='conteudo'><?=dicionario(22)?></a></td>
				</tr>
				<tr>
					<?
						$sql	=	"select 
									       Pessoa.Nome,
									       Pessoa.RazaoSocial,
									       Pessoa.TipoPessoa
									  from 
									       Usuario,
									       Pessoa 
									  where 
									        Usuario.Login='$local_login'  and
									        Usuario.IdPessoa = Pessoa.IdPessoa";
						$res	=	@mysql_query($sql,$con);
						$lin	=	@mysql_fetch_array($res);
						
						if($lin[TipoPessoa] == 1){
							$lin[Nome]	=	$lin[getCodigoInterno(3,24)];				
						}else{
							$lin[Nome]	=	$lin[Nome];
						}
						
						$sqlUltimoAcesso = "select
												max(DataHora) DataHora
											from
												LogAcesso
											where
												Login = '$local_login'
											group by
												DataHora
											order by 
												DataHora DESC
											limit 1,1";
						$resUltimoAcesso = @mysql_query($sqlUltimoAcesso,$con);
						if($linUltimoAcesso = @mysql_fetch_array($resUltimoAcesso)){
							
							$linUltimoAcesso[DataHora] = dataConv($linUltimoAcesso[DataHora], 'Y-m-d H:i:s', 'd/m/Y H:i:s');

							$UltimoAcesso = "<B>Último Acesso:</B>&nbsp;$linUltimoAcesso[DataHora]";
						}

						$sqlLoja = "select
										DescricaoLoja
									from
										Loja
									where
										IdLoja = $local_IdLoja";
						$resLoja = mysql_query($sqlLoja,$con);
						$linLoja = mysql_fetch_array($resLoja);
						
						echo"
							<td class='l2_cl1'><B>Loja:</B> $linLoja[DescricaoLoja]&nbsp;-&nbsp;<B>Usuário:</B>&nbsp;$lin[Nome]</td>
							<td class='l2_cl3'>$UltimoAcesso</td>
						";
					?>
				</tr>
			</table>
			<div id='sair'>[<a href='#' onclick='sair()'><?=dicionario(23)?></a>]</div>
			
			<div class='nomeModuloLeft'>&nbsp;</div>
			<div class='nomeModulo' id='cp_modulo_atual'><?=$local_Titulo?></div>
			<div class='nomeModuloRight'>&nbsp;</div>
			
		</div>
	</body>
</html>