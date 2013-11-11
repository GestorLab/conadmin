<?
	$localModulo		=	1;
	$localOperacao		=	1;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 	
	$IdLoja							= $_SESSION['IdLoja'];

	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_campo					= $_POST['filtro_campo'];
	$filtro_valor					= $_POST['filtro_valor'];
	$filtro_grupo_pessoa			= $_POST['filtro_grupo_pessoa'];
	$filtro_forma_cobranca			= $_POST['filtro_forma_cobranca'];
	$filtro_tipo_pessoa				= $_POST['filtro_tipo_pessoa'];
	$filtro_nome					= $_POST['filtro_nome'];
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_pessoa					= $_GET['IdPessoa'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_tipo_pessoa != ""){
		$filtro_url	.= "&TipoPessoa=$filtro_tipo_pessoa";
		$filtro_sql .=	" and TipoPessoa = $filtro_tipo_pessoa";
	}

	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}

	if($filtro_valor!=""){
		$filtro_url .= "&Valor=".$filtro_valor;
	}
				
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		switch($filtro_campo){
			case 'CPF_CNPJ':
				$filtro_sql .=	" and (CPF_CNPJ like '%$filtro_valor%')";
				break;
			case 'Endereco':
				$filtro_sql .=	" and (Endereco like '%$filtro_valor%' or Complemento like '%$filtro_valor%' or Bairro like '%$filtro_campo%')";
				break;
			case 'CEP':
				$filtro_sql .=	" and (CEP like '%$filtro_valor%')";
				break;
			case 'Fone':
				$filtro_sql .=	" and (Telefone1 like '%$filtro_valor%' or Telefone2 like '%$filtro_valor%' or Telefone3 like '%$filtro_valor%' or Celular like '%$filtro_valor%' or Fax like '%$filtro_valor%' or Cob_Telefone1 like '%$filtro_telefone%')";
				break;
			case 'DiaCobranca':
				$filtro_sql .=	" and (DiaCobranca = '$filtro_valor')";
				break;
			case 'Estado':
				$filtro_sql .=	" and Pessoa.IdPais = '".getCodigoInterno(3,1)."' and (Estado.NomeEstado like '%$filtro_valor%' or Estado.SiglaEstado like '%$filtro_valor%')";
				break;
			case 'Cidade':
				$filtro_sql .=	" and Pessoa.IdPais = '".getCodigoInterno(3,1)."' and (Cidade.NomeCidade like '%$filtro_valor%')";
				break;
			case 'Obs':
				$filtro_sql .=	" and (Obs like '%$filtro_valor%')";
				break;
			case 'Email':
				$filtro_sql .=	" and (Email like '%$filtro_valor%' or Cob_Email like '%$filtro_valor%')";
				break;
		}
		
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

	$sqlRestringirPessoa = "select
								*
							from
								Loja
							where
								IdLoja = $IdLoja and
								RestringirPessoa = 1";
	$resRestringirPessoa = @mysql_query($sqlRestringirPessoa,$con);
	if($linRestringirPessoa = @mysql_fetch_array($resRestringirPessoa)){
		$filtro_sql .=	" and (Pessoa.IdLoja = $IdLoja or Pessoa.IdLoja is NULL)";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_pessoa2_xsl.php?$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
	$sql	=	"select 
					IdPessoa, 
					substr(Nome,1,32) Nome,
					substr(RazaoSocial,1,32) RazaoSocial,
					Telefone1,
					Telefone2,
					Telefone3,
					Celular,
					substr(Cidade.NomeCidade,1,15) NomeCidade,
					Estado.SiglaEstado,
					Fax,
					Cob_Telefone1,
					CPF_CNPJ
				from 
					Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					Pais,
					Estado,
					Cidade
				where
					Pais.IdPais = Pessoa.IdPais and
					Estado.IdPais = Pais.IdPais and
					Pessoa.IdEstado = Estado.IdEstado and
					Cidade.IdEstado = Estado.IdEstado and
					Cidade.IdCidade = Pessoa.IdCidade
					$filtro_sql
				order by
					Pessoa.IdPessoa desc
			    $Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[Telefone1] == ''){
			if($lin[Telefone2] == ''){
				if($lin[Telefone3] == ''){
					if($lin[Celular] == ''){
						if($lin[Fax] == ''){
							if($lin[Cob_Telefone1] == ''){
								$lin[Telefone1]	=	'';
							}else{
								$lin[Telefone1]	=	$lin[Cob_Telefone1];
							}	
						}else{
							$lin[Telefone1]	=	$lin[Fax];
						}
					}else{
						$lin[Telefone1]	=	$lin[Celular];
					}
				}else{
					$lin[Telefone1]	=	$lin[Telefone3];
				}
			}else{
				$lin[Telefone1]	=	$lin[Telefone2];
			}
		}
		
		echo "<reg>";	
		echo 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
		echo 	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
		echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
		echo 	"<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
		echo 	"<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
