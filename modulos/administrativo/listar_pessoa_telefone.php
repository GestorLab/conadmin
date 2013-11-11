<?
	$localModulo		=	1;
	$localOperacao		=	1;
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
	$filtro_cliente_ativo			= $_POST['filtro_cliente_ativo'];
	$filtro_grupo_pessoa			= $_POST['filtro_grupo_pessoa'];
	$filtro_forma_cobranca			= $_POST['filtro_forma_cobranca'];
	$filtro_tipo_pessoa				= $_POST['filtro_tipo_pessoa'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_pessoa					= $_POST['IdPessoa'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro_pessoa == '' && $_GET['IdPessoa'] != ''){
		$filtro_pessoa		= $_GET['IdPessoa'];
	}
	
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
		$filtro_sql .=	" and TipoPessoa = $filtro_tipo_pessoa";
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
	
	if($filtro_forma_cobranca!=""){
		$filtro_url .= "&FormaCobranca=".$filtro_forma_cobranca;
		
		switch($filtro_forma_cobranca){
			case 'C':
				$filtro_sql .= " and Cob_FormaCorreio = 'S'";
				break;
			case 'E':
				$filtro_sql .= " and Cob_FormaEmail = 'S'";
				break;
			case 'O':
				$filtro_sql .= " and Cob_FormaOutro = 'S'";
				break;
		}
	}
	
	if($filtro_cliente_ativo!=''){
		$filtro_url .= "&ClienteAtivo=$filtro_cliente_ativo";
		
		if($filtro_cliente_ativo == 1){
			$filtro_sql .=	" and Contrato.IdContrato is not null";
		}else{
			$filtro_sql .=	" and Contrato.IdContrato is null";
		}
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
							AgenteAutorizado.IdStatus = 1";
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
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_pessoa_telefone_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select 
					Pessoa.IdPessoa, 
					substr(Nome,1,15) Nome,
					substr(RazaoSocial,1,15) RazaoSocial,
					Telefone1,
					Telefone2,
					Telefone3,
					Celular,
					Fax,
					CPF_CNPJ,
					Contrato.IdContrato
				from 
					Pessoa LEFT JOIN (
						select 
							IdContrato,
							IdPessoa 
						from 
							Contrato 
						where 
							Contrato.IdLoja = $IdLoja and 
							DataTermino is NULL and 
							Contrato.IdStatus != 1 
						group by 
							IdPessoa
					) Contrato ON (
						Pessoa.IdPessoa = Contrato.IdPessoa
					) LEFT JOIN (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) ON (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					)
				where
					1
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

		echo "<reg>";	
		echo 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
		echo 	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
		echo 	"<Telefone2><![CDATA[$lin[Telefone2]]]></Telefone2>";
		echo 	"<Telefone3><![CDATA[$lin[Telefone3]]]></Telefone3>";
		echo 	"<Cob_Telefone1><![CDATA[$lin[Cob_Telefone1]]]></Cob_Telefone1>";
		echo 	"<Celular><![CDATA[$lin[Celular]]]></Celular>";
		echo 	"<Fax><![CDATA[$lin[Fax]]]></Fax>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
