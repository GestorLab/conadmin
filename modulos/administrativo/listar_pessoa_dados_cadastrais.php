<?
	$localModulo		=	1;
	$localOperacao		=	119;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION["IdLoja"]; 
	$IdPessoaLogin					= $_SESSION['IdPessoa'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];

	$filtro_campo					= $_POST['filtro_campo'];
	$filtro_valor					= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_grupo_pessoa			= $_POST['filtro_grupo_pessoa'];
	$filtro_forma_cobranca			= $_POST['filtro_forma_cobranca'];
	$filtro_tipo_pessoa				= $_POST['filtro_tipo_pessoa'];
	$filtro_status_contrato			= $_POST['filtro_status_contrato'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_pessoa					= $_POST['IdPessoa'];
	$filtro_bairro					= $_POST['Bairro'];
	$filtro_pessoa_situacao_cadastro= $_SESSION["filtro_pessoa_situacao_cadastro"];
	$filtro_ordenar_por				= $_SESSION["filtro_ordenar_por"];

	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	$sqlAux		 = "";
	$order_by	 = "";
	
	if($filtro_pessoa == '' && $_GET['IdPessoa'] != ''){
		$filtro_pessoa		= $_GET['IdPessoa'];
	}
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url	.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url	.= "&Ordem=$filtro_ordem";
		
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
	
	if($filtro_status_contrato != ""){
		$filtro_url	.= "&StatusContrato=$filtro_status_contrato";
		
		$aux	=	explode("G_",$filtro_status_contrato);
		
		if($aux[1]!=""){
			switch($aux[1]){
				case '1':
					$filtro_sql .= " and (Contrato.IdStatus >= 1 and Contrato.IdStatus < 100)";
					break;
				case '2':
					$filtro_sql .= " and (Contrato.IdStatus >= 200 and Contrato.IdStatus < 300)";
					break;
				case '3':
					$filtro_sql .= " and (Contrato.IdStatus >= 300 and Contrato.IdStatus < 400)";
					break;
			}
		}else{
			$filtro_sql .= " and Contrato.IdStatus = '$filtro_status_contrato'";
		}
	}

	switch($filtro_pessoa_situacao_cadastro){
		case 1: # Possui Contratos
			$filtro_sql .= " and Pessoa.IdPessoa in (
				select 
					Contrato.IdPessoa 
				from
					Contrato 
				where
					Contrato.Idloja = $local_IdLoja and
					Contrato.IdStatus > 199
			)";
			break;
		case 2: # Não possui Contratos
			$filtro_sql .= " and Pessoa.IdPessoa not in (
				select 
					Contrato.IdPessoa 
				from
					Contrato 
				where
					Contrato.Idloja = $local_IdLoja
			)";
			break;
		case 3: # Cliente Inativos
			$filtro_sql .= " and Pessoa.IdPessoa in (
				select 
					ContratoTemp.IdPessoa 
				from 
					(
						select 
							* 
						from 
							(
								select 
									Contrato.IdPessoa,
									Contrato.IdStatus
								from
									Pessoa,
									Contrato
								where
									Pessoa.IdPessoa = Contrato.IdPessoa and
									Contrato.IdLoja = $local_IdLoja and
									Contrato.IdPessoa not in (
										select
											OrdemServico.IdPessoa
										from 
											OrdemServico
										where
											OrdemServico.IdLoja = $local_IdLoja and
											OrdemServico.IdPessoa is not NULL
										group by
											OrdemServico.IdPessoa
									)
								order by
									Contrato.IdStatus desc
							) ContratoTemp
						group by 
							ContratoTemp.IdPessoa
					) ContratoTemp
				where
					ContratoTemp.IdStatus >= 0 and
					ContratoTemp.IdStatus < 200
			)";
			break;
		case 4: # Possui Contratos/Possui Ordem de Servico
			$filtro_sql .= "and(
								Pessoa.IdPessoa in(
									select 
										Contrato.IdPessoa 
									from
										Contrato 
									where 
										Contrato.Idloja = $local_IdLoja and
										Contrato.IdStatus > 199
								)or
								Pessoa.IdPessoa in(
									select 
										OrdemServico.IdPessoa 
									from
										OrdemServico 
									where 
										OrdemServico.Idloja = $local_IdLoja and
										(
											OrdemServico.IdStatus > 99 and 
											OrdemServico.IdStatus < 199 or 
											OrdemServico.IdStatus > 299 and 
											OrdemServico.IdStatus < 499
										)
								)
							)";
			break;
	}

	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
				
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		if($filtro_valor != ""){
			switch($filtro_campo){
				case 'CPF_CNPJ':
					$filtro_sql .=	" and (Pessoa.CPF_CNPJ like '%".inserir_mascara($filtro_valor)."%')";
					break;
				case 'Endereco':
					$filtro_sql .=	" and (PessoaEndereco.Endereco like '%$filtro_valor%' or PessoaEndereco.Complemento like '%$filtro_valor%' or PessoaEndereco.Bairro like '%$filtro_campo%')";
					break;
				case 'CEP':
					$filtro_sql .=	" and (PessoaEndereco.CEP like '%$filtro_valor%')";
					break;
				case 'Fone':
					$filtro_sql .=	" and (Pessoa.Telefone1 like '%$filtro_valor%' or Pessoa.Telefone1 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Telefone2 like '%$filtro_valor%' or Pessoa.Telefone2 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Telefone3 like '%$filtro_valor%' or Pessoa.Telefone3 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Celular like '%$filtro_valor%' or Pessoa.Celular like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Fax like '%$filtro_valor%' or Pessoa.Fax like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or PessoaEndereco.Telefone like '%$filtro_valor%' or PessoaEndereco.Telefone like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or PessoaEndereco.Celular like '%$filtro_valor%' or PessoaEndereco.Celular like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or PessoaEndereco.Fax like '%$filtro_valor%' or PessoaEndereco.Fax like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Telefone1 like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Telefone2 like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Telefone3 like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Celular like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Fax like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or PessoaEndereco.Celular like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or PessoaEndereco.Fax like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Telefone1 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Telefone2 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Telefone3 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Celular like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Fax like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or PessoaEndereco.Telefone like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or PessoaEndereco.Celular like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or PessoaEndereco.Fax like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Telefone1 like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or Pessoa.Telefone2 like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or Pessoa.Telefone3 like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or Pessoa.Celular like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or Pessoa.Fax like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or PessoaEndereco.Telefone like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or PessoaEndereco.Celular like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or PessoaEndereco.Fax like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' ) ";
					break;
				case 'NomeRepresentante':
					$filtro_sql .=	" and (Pessoa.NomeRepresentante like '%$filtro_valor%')";
					break;
				case 'Estado':
					$filtro_sql .=	" and PessoaEndereco.IdPais = '".getCodigoInterno(3,1)."' and (Estado.NomeEstado like '%$filtro_valor%' or Estado.SiglaEstado like '%$filtro_valor%')";
					break;
				case 'Cidade':
					$filtro_sql .=	" and PessoaEndereco.IdPais = '".getCodigoInterno(3,1)."' and (Cidade.NomeCidade like '%$filtro_valor%')";
					break;
				case 'Obs':
					$filtro_sql .=	" and (Pessoa.Obs like '%$filtro_valor%')";
					break;
				case 'Email':
					$filtro_sql .=	" and (Pessoa.Email like '%$filtro_valor%' or PessoaEndereco.EmailEndereco like '%$filtro_valor%')";
					break;
				case 'Bairro':
					$filtro_sql .=	" and (PessoaEndereco.Bairro like '%$filtro_valor%')";
					break;
				case 'Filiacao':
					$filtro_sql .=	" and (Pessoa.NomePai like '%$filtro_valor%' or Pessoa.NomeMae like '%$filtro_valor%')";
					break;
			}
		}else{
			switch($filtro_campo){
				case 'CPF_CNPJ':
					$filtro_sql .=	" and (Pessoa.CPF_CNPJ = '')";
					break;
				case 'Endereco':
					$filtro_sql .=	" and (PessoaEndereco.Endereco = '')";
					break;
				case 'CEP':
					$filtro_sql .=	" and (PessoaEndereco.CEP = '')";
					break;
				case 'Fone':
					$filtro_sql .=	" and (Pessoa.Telefone1 = '' and Pessoa.Telefone2 = '' and Pessoa.Telefone3 = '' and Pessoa.Celular = '' and Pessoa.Fax = '' and PessoaEndereco.Telefone = '' and PessoaEndereco.Celular = '' and PessoaEndereco.Fax = '')";
					break;
				case 'NomeRepresentante':
					$filtro_sql .=	" and (Pessoa.NomeRepresentante = '')";
					break;
				case 'Estado':
					$filtro_sql .=	" and PessoaEndereco.IdEstado = '')";
					break;
				case 'Cidade':
					$filtro_sql .=	" and PessoaEndereco.IdCidade = '')";
					break;
				case 'Obs':
					$filtro_sql .=	" and (Pessoa.Obs = '')";
					break;
				case 'Email':
					$filtro_sql .=	" and (Pessoa.Email = '' and PessoaEndereco.EmailEndereco = '')";
					break;
				case 'Bairro':
					$filtro_sql .=	" and (PessoaEndereco.Bairro like '')";
					break;
				case 'Filiacao':
					$filtro_sql .=	" and (Pessoa.NomePai like '' or Pessoa.NomeMae like '')";
					break;
			}
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
	
	if($filtro_grupo_pessoa!=""){
		$filtro_url .= "&GrupoPessoa=".$filtro_grupo_pessoa;
		$filtro_sql .= " and GrupoPessoa.IdGrupoPessoa = '$filtro_grupo_pessoa'";
	}
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
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
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
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
			
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit 0,".$filtro_limit;
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	switch($filtro_ordenar_por){	
		case 'Nome Pessoa/Razao Social': # Nome Pessoa/Razão Social
			$order_by = "Pessoa.Nome, Pessoa.RazaoSocial"; 
			break;
		case 'ID Pessoa': # ID Pessoa
			$order_by =  "Pessoa.IdPessoa";
			break;	
		case 'CPF/CNPJ': # CPF/CNPJ
			$order_by = "Pessoa.CPF_CNPJ";
			break;
		case 'Endereco': # Endereço
			$order_by =  "PessoaEndereco.DescricaoEndereco";
			break;		 
		case 'CEP': # CEP
			$order_by =  "PessoaEndereco.CEP";
			break;
		case 'E-mail': # E-mail
			$order_by =  "Pessoa.Email";
			break;
		case 'Estado': # Estado
			$order_by =  "Estado.SiglaEstado";
			break;
		case 'Cidade': # Cidade
			$order_by =  "Cidade.NomeCidade";
			break;
		case 'Fone': # Fone
			$order_by = "PessoaEndereco.Telefone";
			break;
		case 'Nome Representante': # Nome Representante
			$order_by = "Pessoa.NomeRepresentante";
			break;
		case 'Observacoes': # Observações
			$order_by = "Pessoa.Obs";
			break;
		case 'Bairro': # Bairro
			$order_by = "PessoaEndereco.Bairro";
		break;
		case 'Filiacao': # Filiação
			$order_by = "Pessoa.NomePai, Pessoa.NomeMae";
			break;
		case 'Inscricao Estadual': # Inscrição Estadual
			$order_by = "Pessoa.RG_IE";
			break;
	}		
    	
	//echo" Filtro > $filtro_ordenar_por Ordenar Por > $order_by";		
	header ("content-type: text/xml");

	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_pessoa_dados_cadastrais_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	$sql	=	"select	distinct															
					Pessoa.IdPessoa, 
					Pessoa.Nome, 
					Pessoa.RazaoSocial, 	
					Pessoa.RG_IE,
					Pessoa.CPF_CNPJ, 
					Pessoa.Telefone1, 
					Pessoa.Telefone2, 
					Pessoa.Telefone3,
					Pessoa.Celular,
					Pessoa.NomeMae,
					Pessoa.NomePai,
					Pessoa.Fax,
					Pessoa.ComplementoTelefone,
					Pessoa.Email, 	
					Pessoa.TipoPessoa, 	
					PessoaEndereco.Numero, 
					PessoaEndereco.Endereco, 
					PessoaEndereco.CEP, 
					PessoaEndereco.Bairro, 					
					Cidade.NomeCidade, 
					Estado.SiglaEstado			
				from 					
					Pessoa left join Contrato on (
						Pessoa.IdPessoa = Contrato.IdPessoa and 
						Contrato.IdLoja = $local_IdLoja
					) left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
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
					$order_by 	
				$Limit"; 
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoa] == 2){	
			$lin[Nome]		=	$lin[Nome];
			$lin[CampoNome]	=	dicionario(178);
		}else{
			$lin[Nome]		=	$lin[RazaoSocial];
			$lin[CampoNome]	=	dicionario(172);
		}
		$lin[Endereco]		=	$lin[Endereco].", ".$lin[Numero];
		if($lin[Complemento]!= '' )	$lin[Endereco] .=	" - ".$lin[Complemento];
		if($lin[Bairro]!= '' )	 	$lin[Endereco] .=	" - ".dicionario(160).": ".$lin[Bairro];
		$lin[Endereco]	.=	" - ".$lin[NomeCidade]."-".$lin[SiglaEstado]." - ".dicionario(156).": ".$lin[CEP];
		
		if($lin[TipoPessoa] == 1){
			$lin[cpCNPJ]	=	dicionario(179);
			$lin[cpIE]		=	dicionario(180);
			$lin[cpNomePai]	=	'';
			$lin[cpNomeMae]	=	'';
		}
		if($lin[TipoPessoa] == 2){
			$lin[cpCNPJ]	=	dicionario(179);
			$lin[cpIE]		=	dicionario(89);
			$lin[cpNomePai]	=	dicionario(181);
			$lin[cpNomeMae]	=	dicionario(182);
		}
		
		echo "<reg>";	
		echo 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<CampoNome><![CDATA[$lin[CampoNome]]]></CampoNome>";
		echo 	"<Endereco><![CDATA[$lin[Endereco]]]></Endereco>";
		echo 	"<RG_IE><![CDATA[$lin[RG_IE]]]></RG_IE>";
		echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
		echo 	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
		echo 	"<Telefone2><![CDATA[$lin[Telefone2]]]></Telefone2>";
		echo 	"<Telefone3><![CDATA[$lin[Telefone3]]]></Telefone3>";
		echo 	"<Celular><![CDATA[$lin[Celular]]]></Celular>";
		echo 	"<NomePai><![CDATA[$lin[NomePai]]]></NomePai>";
		echo 	"<NomeMae><![CDATA[$lin[NomeMae]]]></NomeMae>";
		echo 	"<ComplementoTelefone><![CDATA[$lin[ComplementoTelefone]]]></ComplementoTelefone>";
		echo 	"<Fax><![CDATA[$lin[Fax]]]></Fax>";
		echo 	"<Email><![CDATA[$lin[Email]]]></Email>";
		echo 	"<cpCNPJ><![CDATA[$lin[cpCNPJ]]]></cpCNPJ>";
		echo 	"<cpIE><![CDATA[$lin[cpIE]]]></cpIE>";
		echo 	"<cpNomeMae><![CDATA[$lin[cpNomeMae]]]></cpNomeMae>";
		echo 	"<cpNomePai><![CDATA[$lin[cpNomePai]]]></cpNomePai>";
		echo "</reg>";
	}

	echo "</db>";
?>
