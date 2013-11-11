<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION['IdLoja']; 
	$local_IdPessoaLogin			= $_SESSION["IdPessoa"];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_numero_documento		= $_POST['filtro_numero_documento'];
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_conta_receber			= $_POST['filtro_conta_receber'];
	$filtro_usuario_cadastro		= $_POST['filtro_usuario_cadastro'];
	$filtro_data_cadastro_inicio	= $_POST['filtro_data_cadastro_inicio'];
	$filtro_data_cadastro_fim		= $_POST['filtro_data_cadastro_fim'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	
	$filtro_idContaEventual			= $_GET['IdContaEventual'];
	$filtro_idContrato				= $_GET['IdContrato'];
	$filtro_idLancamentoFinanceiro	= $_GET['IdLancamentoFinanceiro'];
	$filtro_idProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
	$filtro_idOrdemServico			= $_GET['IdOrdemServico'];
	$filtro_pessoa					= $_GET['IdPessoa'];
	$filtro_idemail					= $_GET['IdEmail'];
	$filtro_erro					= $_GET['Erro'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .= " and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_numero_documento!=''){
		$filtro_url .= "&NumeroDocumento=$filtro_numero_documento";
		$filtro_sql .= " and NumeroDocumento.NumeroDocumento = '$filtro_numero_documento'";
	}
	
	if($filtro_local_cobranca!=''){
		$filtro_url .= "&IdLocalCobranca=$filtro_local_cobranca";
		$filtro_sql .= " and NumeroDocumento.IdLocalCobranca = '$filtro_local_cobranca'";
	}
	
	if($filtro_conta_receber!=''){
		$filtro_url .= "&IdContaReceber=$filtro_conta_receber";
		$filtro_sql .= " and NumeroDocumento.IdContaReceber = '$filtro_conta_receber'";
	}
	
	if($filtro_usuario_cadastro!=''){
		$filtro_url .= "&UsuarioCadastro=$filtro_usuario_cadastro";
		$filtro_sql .= " and NumeroDocumento.LoginCriacao like '%$filtro_usuario_cadastro%'";
	}
	
	if($filtro_data_cadastro_inicio!=''){
		$filtro_url .= "&DataCadastroInicio=$filtro_data_cadastro_inicio";
		$filtro_data_cadastro_inicio = dataConv($filtro_data_cadastro_inicio,"d/m/Y H:i:s","Y-m-d H:i:s");
		$filtro_sql .= " and NumeroDocumento.DataCriacao >= '$filtro_data_cadastro_inicio'";
	}
	
	if($filtro_data_cadastro_fim!=''){
		$filtro_url .= "&DataCadastroFim=$filtro_data_cadastro_fim";
		$filtro_data_cadastro_fim = dataConv($filtro_data_cadastro_fim,"d/m/Y H:i:s","Y-m-d H:i:s");
		$filtro_sql .= " and NumeroDocumento.DataCriacao <= '$filtro_data_cadastro_fim'";
	}
	
	if($filtro_erro!=''){
		$filtro_url .= "&Erro=".$filtro_erro;
	}
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_numero_documento_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "select 
				NumeroDocumento.IdContaReceber,
				NumeroDocumento.NumeroDocumento,
				NumeroDocumento.IdLocalCobranca,
				NumeroDocumento.ValorContaReceber,
				NumeroDocumento.IdPessoa,
				NumeroDocumento.DataCriacao,
				NumeroDocumento.LoginCriacao,
				Pessoa.TipoPessoa,
				substr(Pessoa.Nome,1,30) Nome,
				substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
				LocalCobranca.AbreviacaoNomeLocalCobranca
			from
				NumeroDocumento,
				Pessoa left join (
					PessoaGrupoPessoa, 
					GrupoPessoa
				) on (
					Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
					PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
					PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
					PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
				),
				LocalCobranca
			where
				NumeroDocumento.IdLoja = $local_IdLoja and
				NumeroDocumento.IdPessoa = Pessoa.IdPessoa and
				NumeroDocumento.IdLoja = LocalCobranca.IdLoja and
				NumeroDocumento.IdLocalCobranca = LocalCobranca.IdLocalCobranca
				$filtro_sql
			order by
				NumeroDocumento.NumeroDocumento desc
			$Limit;";
	$res = @mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
		if($lin[TipoPessoa] == '1'){
			$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
		}
		
		$lin[DataCriacaoTemp]	= dataConv($lin[DataCriacao],"Y-m-d H:i:s","d/m/Y H:i:s");
		$lin[DataCriacao]		= dataConv($lin[DataCriacao],"Y-m-d H:i:s","YmdHis");
		
		echo "<reg>";	
		echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
		echo 	"<NumeroDocumento>$lin[NumeroDocumento]</NumeroDocumento>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
		echo 	"<ValorContaReceber><![CDATA[$lin[ValorContaReceber]]]></ValorContaReceber>";
		echo 	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
		echo 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo 	"<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
