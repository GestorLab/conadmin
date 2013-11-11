<?
	$localModulo		=	1;
	$localOperacao		=	70;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 	

	$IdLoja								= $_SESSION['IdLoja'];
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_campo						= $_POST['filtro_campo'];
	$filtro_valor						= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_status						= $_POST['filtro_status'];
	$filtro_tipo_pessoa					= $_POST['filtro_tipo_pessoa'];
	$filtro_nome						= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_limit						= $_POST['filtro_limit'];
	$filtro_pessoa						= $_POST['IdPessoa'];
	$filtro_data_inicio_solicitacao		= $_POST['filtro_data_inicio_solicitacao'];
	$filtro_data_termino_solicitacao	= $_POST['filtro_data_termino_solicitacao'];
	$filtro_data_inicio_alteracao		= $_POST['filtro_data_inicio_alteracao'];
	$filtro_data_termino_alteracao		= $_POST['filtro_data_termino_alteracao'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($_GET['filtro_limit'] != ''){
		$filtro_limit		= $_GET['filtro_limit'];
	}
	
	if($filtro_pessoa == '' && $_GET['IdPessoa'] != ''){
		$filtro_pessoa		= $_GET['IdPessoa'];
	}
	
	if($filtro_status == '' && $_GET['IdStatus'] != ''){
		$filtro_status		= $_GET['IdStatus'];
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
		$filtro_sql .=	" and Pessoa.TipoPessoa = $filtro_tipo_pessoa";
	}
	
	if($filtro_status != ""){
		$filtro_url	.= "&Aprovada=$filtro_status";
		$filtro_sql .=	" and PessoaSolicitacao.IdStatus = '$filtro_status'";
	}

	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (PessoaSolicitacao.Nome like '%$filtro_nome%' or PessoaSolicitacao.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_data_inicio_solicitacao!=""){
		$filtro_url .= "&DataInicio_solicitacao=".$filtro_data_inicio_solicitacao;
		$filtro_data_inicio_solicitacao	=	dataConv($filtro_data_inicio_solicitacao,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(PessoaSolicitacao.DataCriacao,1,10) >= '$filtro_data_inicio_solicitacao'";
	}
	
	if($filtro_data_termino_solicitacao!=""){
		$filtro_url .= "&DataTermino_solicitacao=".$filtro_data_termino_solicitacao;
		$filtro_data_termino_solicitacao	=	dataConv($filtro_data_termino_solicitacao,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(PessoaSolicitacao.DataCriacao,1,10) <= '$filtro_data_termino_solicitacao'";
	}
	
	if($filtro_data_inicio_alteracao!=""){
		$filtro_url .= "&DataInicio_alteracao=".$filtro_data_inicio_alteracao;
		$filtro_data_inicio_alteracao	=	dataConv($filtro_data_inicio_alteracao,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(PessoaSolicitacao.DataAprovacao,1,10) >= '$filtro_data_inicio_alteracao'";
	}
	
	if($filtro_data_termino_alteracao!=""){
		$filtro_url .= "&DataTermino_alteracao=".$filtro_data_termino_alteracao;
		$filtro_data_termino_alteracao	=	dataConv($filtro_data_termino_alteracao,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(PessoaSolicitacao.DataAprovacao,1,10) <= '$filtro_data_termino_alteracao'";
	}
				
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		switch($filtro_campo){
			case 'CPF_CNPJ':
				$filtro_sql .=	" and Pessoa.CPF_CNPJ like '%$filtro_valor%'";
				break;
			case 'Endereco':
				$filtro_sql .=	" and (PessoaSolicitacaoEndereco.Endereco like '%$filtro_valor%' or PessoaSolicitacaoEndereco.Complemento like '%$filtro_valor%' or PessoaSolicitacaoEndereco.Bairro like '%$filtro_campo%')";
				break;
			case 'CEP':
				$filtro_sql .=	" and (PessoaSolicitacaoEndereco.CEP like '%$filtro_valor%')";
				break;
			case 'Fone':
				$filtro_sql .=	" and (PessoaSolicitacaoEndereco.Telefone1 like '%$filtro_valor%' or PessoaSolicitacaoEndereco.Telefone2 like '%$filtro_valor%' or PessoaSolicitacaoEndereco.Telefone3 like '%$filtro_valor%' or PessoaSolicitacaoEndereco.Celular like '%$filtro_valor%' or PessoaSolicitacaoEndereco.Fax like '%$filtro_valor%')";
				break;
			case 'Estado':
				$filtro_sql .=	" and PessoaSolicitacaoEndereco.IdPais = '".getCodigoInterno(3,1)."' and (Estado.NomeEstado like '%$filtro_valor%' or Estado.SiglaEstado like '%$filtro_valor%')";
				break;
			case 'Cidade':
				$filtro_sql .=	" and PessoaSolicitacaoEndereco.IdPais = '".getCodigoInterno(3,1)."' and (Cidade.NomeCidade like '%$filtro_valor%')";
				break;
			case 'Email':
				$filtro_sql .=	" and PessoaSolicitacao.EmailEndereco like '%$filtro_valor%'";
				break;
		}
		
	}
	
	if($filtro_campo=='' && $filtro_valor!=""){
		$filtro_valor	=	"";	
		$filtro_url .= "&Campo=$filtro_valor";
	}else{
		if($filtro_valor!=""){
			$filtro_url .= "&Valor=".$filtro_valor;
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_pessoa_solicitacao_xsl.php$filtro_url\"?>";
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
					PessoaSolicitacao.IdPessoaSolicitacao,
					PessoaSolicitacao.IdPessoa, 
					substr(PessoaSolicitacao.Nome,1,32) Nome,
					substr(PessoaSolicitacao.RazaoSocial,1,32) RazaoSocial,
					PessoaSolicitacao.Telefone1,
					PessoaSolicitacao.Telefone2,
					PessoaSolicitacao.Telefone3,
					PessoaSolicitacao.Celular,
					substr(Cidade.NomeCidade,1,15) NomeCidade,
					Estado.SiglaEstado,
					PessoaSolicitacao.Fax,
					PessoaSolicitacao.IdStatus,
					Pessoa.CPF_CNPJ,
					PessoaSolicitacao.DataCriacao,
					PessoaSolicitacao.DataAprovacao
				from 
					Pessoa
					LEFT JOIN 
						(SELECT 
						  IdContrato,
						  IdPessoa,
						  DiaCobranca 
						FROM
						  Contrato 
						WHERE Contrato.IdLoja = $IdLoja 
						  AND DataTermino IS NULL 
						  AND Contrato.IdStatus != 1 
						GROUP BY IdPessoa) Contrato 
						ON (
						  Pessoa.IdPessoa = Contrato.IdPessoa
						) 
						LEFT JOIN (PessoaGrupoPessoa, GrupoPessoa) 
						ON (
						  Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa 
						  AND PessoaGrupoPessoa.IdLoja = '$IdLoja' 
						  AND PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja 
						  AND PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						),
					PessoaSolicitacao,
					PessoaSolicitacaoEndereco,
					Pais,
					Estado,
					Cidade
				where
					Pessoa.IdPessoa = PessoaSolicitacao.IdPessoa and
					PessoaSolicitacao.IdPessoa = PessoaSolicitacaoEndereco.IdPessoa and
					PessoaSolicitacao.IdPessoaSolicitacao = PessoaSolicitacaoEndereco.IdPessoaSolicitacao and
					PessoaSolicitacao.IdEnderecoDefault = PessoaSolicitacaoEndereco.IdPessoaEndereco and
					Pais.IdPais = PessoaSolicitacaoEndereco.IdPais and
					Estado.IdPais = Pais.IdPais and
					PessoaSolicitacaoEndereco.IdEstado = Estado.IdEstado and
					Cidade.IdEstado = Estado.IdEstado and
					Cidade.IdCidade = PessoaSolicitacaoEndereco.IdCidade 
					$filtro_sql 
				order by
					PessoaSolicitacao.IdPessoaSolicitacao desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[Telefone1] == ''){
			if($lin[Telefone2] == ''){
				if($lin[Telefone3] == ''){
					if($lin[Celular] == ''){
						if($lin[Fax] == ''){
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
		
		$sql2	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema= 124 and IdParametroSistema=$lin[IdStatus]";
		$res2	=	@mysql_query($sql2,$con);
		$lin2	=	@mysql_fetch_array($res2);

		echo "<reg>";	
		echo 	"<IdPessoaSolicitacao>$lin[IdPessoaSolicitacao]</IdPessoaSolicitacao>";
		echo 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
		echo 	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
		echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
		echo 	"<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
		echo 	"<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
		echo 	"<Aprovada><![CDATA[$lin2[ValorParametroSistema]]]></Aprovada>";
		echo 	"<Color><![CDATA[$Cor]]></Color>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
