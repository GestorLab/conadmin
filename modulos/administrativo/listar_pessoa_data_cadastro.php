<?
	$localModulo		=	1;
	$localOperacao		=	103;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 	

	$IdLoja							= $_SESSION['IdLoja'];
	$IdPessoaLogin					= $_SESSION['IdPessoa'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_data_inicio				= $_POST['filtro_data_inicio'];
	$filtro_data_termino			= $_POST['filtro_data_termino'];
	$filtro_grupo_pessoa			= $_POST['filtro_grupo_pessoa'];
	$filtro_forma_cobranca			= $_POST['filtro_forma_cobranca'];
	$filtro_tipo_pessoa				= $_POST['filtro_tipo_pessoa'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_pessoa					= $_POST['IdPessoa'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro_pessoa == '' && $_GET['IdPessoa'] != ''){
		$filtro_pessoa		= $_GET['IdPessoa'];
	}
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_pessoa != ""){
		$filtro_url	.= "&IdPessoa=$filtro_pessoa";
		$filtro_sql .=	" and Pessoa.IdPessoa = '$filtro_pessoa'";
	}
	
	if($filtro_tipo_pessoa != ""){
		$filtro_url	.= "&TipoPessoa=$filtro_tipo_pessoa";
		$filtro_sql .=	" and Pessoa.TipoPessoa = $filtro_tipo_pessoa";
	}

	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
				
	if($filtro_grupo_pessoa!=""){
		$filtro_url .= "&GrupoPessoa=".$filtro_grupo_pessoa;
		$filtro_sql .= " and GrupoPessoa.IdGrupoPessoa = '$filtro_grupo_pessoa'";
	}
	
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio	=	dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(Pessoa.DataCriacao,1,10) >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_termino!=""){
		$filtro_url .= "&DataTermino=".$filtro_data_termino;
		$filtro_data_termino	=	dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(Pessoa.DataCriacao,1,10) <= '$filtro_data_termino'";
	}
		
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	if($_SESSION["RestringirAgenteCarteira"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado,
							Carteira
						where 
							AgenteAutorizado.IdLoja = $IdLoja  and 
							AgenteAutorizado.IdLoja = Carteira.IdLoja and
							AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
							Carteira.IdCarteira = '$IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	if($filtro_forma_cobranca!=""){
		$filtro_url .= "&FormaCobranca=".$filtro_forma_cobranca;
		
		switch($filtro_forma_cobranca){
			case 'C':
				$filtro_sql .= " and Pessoa.Cob_FormaCorreio = 'S'";
				break;
			case 'E':
				$filtro_sql .= " and Pessoa.Cob_FormaEmail = 'S'";
				break;
			case 'O':
				$filtro_sql .= " and Pessoa.Cob_FormaOutro = 'S'";
				break;
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_pessoa_data_cadastro_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit 0, $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql	=	"select 
					Pessoa.IdPessoa, 
					substr(Pessoa.Nome,1,32) Nome,
					substr(Pessoa.RazaoSocial,1,32) RazaoSocial,
					Pessoa.Telefone1,
					Pessoa.Telefone2,
					Pessoa.Telefone3,
					Pessoa.Celular,
					Pessoa.Fax,
					Pessoa.CPF_CNPJ,
					Pessoa.DataCriacao,
					Contrato.IdContrato,
					PessoaEndereco.Telefone,
					PessoaEndereco.Celular CelularEndereco,
					PessoaEndereco.Fax FaxEndereco,
					Cidade.NomeCidade,
					Estado.SiglaEstado,
					PessoaEndereco.EmailEndereco
				from 
					Pessoa left join (
						select 
							IdContrato,
							IdPessoa,
							DiaCobranca 
						from 
							Contrato 
						where 
							Contrato.IdLoja = $IdLoja and 
							DataTermino is NULL and 
							Contrato.IdStatus != 1 
						group by 
							IdPessoa
					) Contrato on (
						Pessoa.IdPessoa = Contrato.IdPessoa
					) left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					PessoaEndereco left join (
						Pais,
						Estado,
						Cidade
					) on (
						PessoaEndereco.IdPais = Pais.IdPais and
						Pais.IdPais = Estado.IdPais and 
						PessoaEndereco.IdEstado = Estado.IdEstado and 
						Estado.IdPais = Cidade.IdPais and
						Estado.IdEstado = Cidade.IdEstado and
						PessoaEndereco.IdCidade = Cidade.IdCidade 
					)	
				where
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault
					$filtro_sql
				order by
					Pessoa.IdPessoa desc
			    $Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[IdContrato] == ''){
			$Color	  =	getParametroSistema(15,2);
		}else{
			$Color	  =	'';
		}
		
		$lin[DataCriacaoTemp]	=	dataConv($lin[DataCriacao],'Y-m-d','d/m/Y');
		$lin[DataCriacao]		=	dataConv($lin[DataCriacao],'Y-m-d','Ymd');

		echo "<reg>";	
		echo 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
		echo 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		echo 	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
		echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
		echo 	"<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
		echo 	"<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
