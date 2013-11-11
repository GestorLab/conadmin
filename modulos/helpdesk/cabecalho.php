<?
	$localModulo	=	2;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('rotinas/verifica.php');
	
	$local_login 	= $_SESSION["LoginHD"];

	if($local_Titulo == ''){	$local_Titulo = 'Help Desk - Página Principal';	}
	
	LogAcessoAtualizaHelpDesk();
	
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
	</head>
	<body>
		<img src='../../img/personalizacao/logo_cab.gif'  	style='display: none' />
		<div id='logo' style='<?=getParametroSistema(4,2)?>'>
			<a href='index.php' target='_parent' ><img src='../../img/personalizacao/logo_cab.gif' alt='<?=getParametroSistema(4,3)?>'/></a>
		</div>
		<div id='info'>
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td class='l1_cl1'><?=getParametroSistema(4,1)?>&nbsp;v. <?=$IdVersao?></td>
					<td class='l1_cl2'><a href='http://www.cntsistemas.com.br' target='conteudo'>© CNT Sistemas</a></td>
				</tr>
				<tr>
					<?
						$sql	=	"select 
									       substr(Pessoa.Nome,1,30) Nome,
									       substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
									       Pessoa.TipoPessoa
									  from 
									       Usuario,
									       Pessoa 
									  where 
									        Usuario.Login='$local_login'  and
									        Usuario.IdPessoa = Pessoa.IdPessoa";
						$res	=	@mysql_query($sql,$con);
						$lin	=	@mysql_fetch_array($res);
					?>
					<td class='l2_cl1'><B>Usuário:</B>&nbsp;<?=$lin[Nome]?></td>
					<td class='l2_cl3'>&nbsp;</td>
				</tr>
			</table>
			<div id='sair'>[<a href='#' onclick='sair()'>Sair</a>]</div>
			
			<div class='nomeModuloLeft'>&nbsp;</div>
			<div class='nomeModulo' id='cp_modulo_atual'><?=$local_Titulo?></div>
			<div class='nomeModuloRight'>&nbsp;</div>
			
		</div>
	</body>
</html>
