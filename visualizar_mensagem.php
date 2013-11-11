<?
	include ('files/conecta.php');
	include ('files/funcoes.php');

	$IdMensagem				= $_GET[mensagem];
	$UrlSistema				= getParametroSistema(6,3);
	$IdHistoricoMensagem	= $_GET[IdHistoricoMensagem];
	$IdLoja					= $_GET[IdLoja];

	if($IdMensagem == ''){
		$sql = "select
					HistoricoMensagem.MD5
				from
					HistoricoMensagem
				where
					HistoricoMensagem.IdLoja = $_GET[IdLoja] and					
					HistoricoMensagem.IdHistoricoMensagem = $_GET[IdHistoricoMensagem]";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);	
	
		$IdMensagem = $lin[MD5];
	}
	$sql2 = "select 
					HistoricoMensagem.IdTipoMensagem,
					HistoricoMensagem.IdPessoa 
				from
					HistoricoMensagem,
					MalaDireta 
				where
					HistoricoMensagem.IdLoja = $IdLoja and
					HistoricoMensagem.IdLoja = MalaDireta.IdLoja and
					HistoricoMensagem.IdHistoricoMensagem = '$IdHistoricoMensagem'
				group by
					IdHistoricoMensagem";
	$res2 =	@mysql_query($sql2,$con);
	$lin2 =	@mysql_fetch_array($res2);
	if($lin2[IdTipoMensagem] >= 1000000){
		$from ="left join MalaDireta 
				on (
				  TipoMensagem.IdTipoMensagem = MalaDireta.IdTipoMensagem and
				  MalaDireta.IdLoja = '$IdLoja' and
				  TipoMensagem.IdLoja = MalaDireta.IdLoja 
				)";
		if($lin2[IdPessoa] != ''){
			$filtro_sql_id_pessoa = " HistoricoMensagem.IdPessoa = Pessoa.IdPessoa and ";
		}else{
			$filtro_sql_id_pessoa = "";
		}
		$where				 .=	" and HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem";
		$order_group		 =	" group by HistoricoMensagem.IdHistoricoMensagem";
	}else{
		$filtro_sql_id_pessoa = " HistoricoMensagem.IdPessoa = Pessoa.IdPessoa and ";
		$where				 .=	" and HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem";
		$order_group		 = 	"";
	}
		
	if($IdMensagem != ''){
		$sql = "select
					HistoricoMensagem.IdLoja,					
					HistoricoMensagem.IdTipoMensagem,					
					HistoricoMensagem.Conteudo,					
					HistoricoMensagem.Email,
					HistoricoMensagem.MD5,
					HistoricoMensagem.IdPessoa,
					HistoricoMensagem.IdContaReceber,
					HistoricoMensagem.Titulo,
					HistoricoMensagem.IdReEnvio,
					TipoMensagem.IdContaEmail,
					TipoMensagem.Assunto,
					TipoMensagem.IdTipoMensagem,
					TipoMensagem.Assinatura,					
					TemplateMensagem.Estrutura,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa
				from
					HistoricoMensagem,
					TipoMensagem $from,
					TemplateMensagem,
					Pessoa
				where
					HistoricoMensagem.MD5 = '$IdMensagem' and
					HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and
					HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem and
					$filtro_sql_id_pessoa
					TipoMensagem.IdLoja = TemplateMensagem.IdLoja and
					TipoMensagem.IdTemplate = TemplateMensagem.IdTemplate
					$order_group";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);	
		
		$_UrlLogo = $UrlSistema."/img/personalizacao/logo_cab.gif";

		if($lin[IdContaReceber] != ""){
			$sql = "select
						LocalCobranca.IdLocalCobranca,
						LocalCobranca.ExtLogo
					from
						ContaReceber,
						LocalCobranca
					where
						ContaReceber.IdLoja = $lin[IdLoja] and
						ContaReceber.IdLoja = LocalCobranca.IdLoja and
						ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
						ContaReceber.IdContaReceber = $lin[IdContaReceber]";
			$resLocalCobranca = mysql_query($sql,$con);
			$linLocalCobranca = mysql_fetch_array($resLocalCobranca);
			
			if($linLocalCobranca[ExtLogo] != ''){
				$_UrlLogo = $UrlSistema."/modulos/administrativo/local_cobranca/personalizacao/".$lin[IdLoja]."/".$linLocalCobranca[IdLocalCobranca].".".$linLocalCobranca[ExtLogo];
			}
		}
		
		$sql = "select
					Pessoa.Nome,
					Pessoa.Site
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $lin[IdLoja] and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);

		if($linEmpresa[Site] == '' || trim($linEmpresa[Site]) == 'http://'){
			$linEmpresa[Site] = "#";
		}	
		
		$AltBody = 	"Atenção, ative a visualização de HTML ou clique no link para visualizar o e-mail. Visualizar e-mail: $UrlSistema/visualizar_mensagem.php?mensagem=$lin[MD5]";
			
		if($lin[TipoPessoa] == 1){
			$lin[NomeCliente] = $lin[NomeRepresentante]." (".$lin[Nome].")";
		}else{
			$lin[NomeCliente] = $lin[Nome];
		}
		$Conteudo = $lin[Estrutura];

		$lin[Titulo] = strtoupper($lin[Titulo]);

		$Conteudo = str_replace('$_Conteudo',$lin[Conteudo],$Conteudo);
		$Conteudo = str_replace('$_TituloMensagem',$lin[Titulo],$Conteudo);
		$Conteudo = str_replace('$_TituloPersonalizado',$lin[Titulo],$Conteudo);
		$Conteudo = str_replace('$_Assinatura',$lin[Assinatura],$Conteudo);
		$Conteudo = str_replace('$_NomeCliente',$lin[NomeCliente],$Conteudo);
		$Conteudo = str_replace('$_NomeEmpresa',$linEmpresa[Nome],$Conteudo);
		$Conteudo = str_replace('$_ClienteNomeEmpresa',$lin[NomeCliente],$Conteudo);
		$Conteudo = str_replace('$_SiteEmpresa',$linEmpresa[Site],$Conteudo);
		$Conteudo = str_replace('$_UrlLogo',$_UrlLogo,$Conteudo);

		$Conteudo = str_replace('$_UrlCDA','$_UrlSistema/central',$Conteudo);

		$sqlParametroMensagem = "select
										IdTipoMensagemParametro,
										ValorTipoMensagemParametro
								from
										TipoMensagemParametro
								where
										IdLoja = $lin[IdLoja] and
										IdTipoMensagem = $lin[IdTipoMensagem]";
		$resParametroMensagem = mysql_query($sqlParametroMensagem,$con);
		while($linParametroMensagem = mysql_fetch_array($resParametroMensagem)){
			$Conteudo = str_replace($linParametroMensagem[IdTipoMensagemParametro],$linParametroMensagem[ValorTipoMensagemParametro],$Conteudo);
		}
		
		$Conteudo = str_replace('$_UrlSistema',$UrlSistema,$Conteudo);
		
		if($lin[IdReEnvio] == ""){
			$IdHistoricoMensagemAnexo = $IdHistoricoMensagem;			
		}else{		
			$IdHistoricoMensagemAnexo = buscaIdHistoricoMensagemAnexo($lin[IdLoja], $lin[IdReEnvio]);
		}

		$Conteudo .= "\n<div style='text-align:center; color: #C0C0C0; font: normal 10px Verdana, Arial, Times; border-top: 1px #C0C0C0 solid; margin-top: 10px; padding-top: 5px; '><a href='http://www.cntsistemas.com.br' style='color: #C0C0C0; text-decoration: none;' target='_blank'>ConAdmin - Sistemas Adminstrativos de Qualidade - CNTSistemas</a></font>"; 		
		
		$i = 0;
		if($IdHistoricoMensagemAnexo != ''){

			$sql = "select 
						IdLoja,
						IdAnexo,
						NomeOriginal
					from 
						HistoricoMensagemAnexo 
					where 
						IdLoja = $lin[IdLoja] and 
						IdHistoricoMensagem = $IdHistoricoMensagemAnexo";		
			$resAnexo = mysql_query($sql,$con);
			while($linAnexo = mysql_fetch_array($resAnexo)){
				if($i == 0){
					$Conteudo .="<br><br><hr /><div style=''>";	
					$i=1;
				}
				$EndArquivo	= "modulos/administrativo/anexos/mensagem/".$linAnexo[IdLoja]."/".$IdHistoricoMensagemAnexo."/".$linAnexo[IdAnexo];	
				$TamArquivo = calculaTamanhoArquivo($EndArquivo);
				
				$Conteudo .= "<a style='margin: 0 5px 0 40px; color: #000; cursor:pointer; text-decoration: none; font-size:12px; font-weight: bold; border=0' href=modulos/administrativo/rotinas/mensagem_download_anexo.php?IdLoja=".$linAnexo[IdLoja]."&IdHistoricoMensagemAnexo=".$IdHistoricoMensagemAnexo."&IdAnexo=".$linAnexo[IdAnexo]."&NomeArquivo=".$linAnexo[NomeOriginal]." >".$linAnexo[NomeOriginal]."</a><span style='font-size: 9px; color:#000'>(".$TamArquivo.")</span>";
	//			$Conteudo .= "";
			}
			if($i == 1){
				 $Conteudo .= "</div>";
			}
		}
		echo $Conteudo;
	}
?>
