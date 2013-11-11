<?
	$localModulo		=	1;
	$localOperacao		=	154;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$IdLoja										= $_SESSION['IdLoja'];
	$IdPessoaLogin								= $_SESSION['IdPessoa'];
	$filtro										= $_POST['filtro'];
	$filtro_ordem								= $_POST['filtro_ordem'];
	$filtro_ordem_direcao						= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado						= $_POST['filtro_tipoDado'];
	$filtro_campo								= $_POST['filtro_campo'];
	$filtro_valor								= $_POST['filtro_valor'];
	$filtro_grupo_pessoa						= $_POST['filtro_grupo_pessoa'];
	$filtro_forma_cobranca						= $_POST['filtro_forma_cobranca'];
	$filtro_tipo_pessoa							= $_POST['filtro_tipo_pessoa'];
	$filtro_nome								= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_contrato							= $_POST['filtro_contrato'];
	$filtro_limit								= $_POST['filtro_limit'];
	$filtro_pessoa								= $_POST['IdPessoa'];
	$filtro_bairro								= $_POST['Bairro'];
	
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_pessoa_sem_contrato_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select distinct
					Pessoa.IdPessoa,
					substr(Pessoa.Nome,1,32) Nome,
					substr(Pessoa.RazaoSocial,1,32) RazaoSocial,
					Pessoa.Telefone1,
					Pessoa.Telefone2,
					Pessoa.Telefone3,
					Pessoa.Celular,
					Pessoa.Fax,
					Pessoa.CPF_CNPJ,					
					PessoaEndereco.Telefone,
					PessoaEndereco.Celular CelularEndereco,
					PessoaEndereco.Fax FaxEndereco,
					Cidade.NomeCidade,
					Estado.SiglaEstado,
					PessoaEndereco.EmailEndereco
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
					Contrato,
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
					Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and 
					Pessoa.IdPessoa not in (
						select distinct
							IdPessoa
						from	
							Contrato
					)
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
						if($lin[Fax]==''){
							if($lin[Telefone] == ''){
								if($lin[CelularEndereco] == ''){
									if($lin[FaxEndereco] != ''){
										$lin[Telefone1]	=	$lin[FaxEndereco];
									}
								}else{
									$lin[Telefone1]	=	$lin[CelularEndereco];
								}
							}else{
								$lin[Telefone1]	=	$lin[Telefone];
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
		
		$AtivaCor 	 = false;
		$DesativaCor = false;
		
		$sql2 	= 	"select 						
						IdStatus
					from 
						Contrato 
					where 
						IdLoja = $IdLoja and 
						IdPessoa = $lin[IdPessoa]";					
		$res2	=	mysql_query($sql2,$con);		
		$Qtd  	= 	mysql_num_rows($res2);
		while($lin2	=	mysql_fetch_array($res2)){		
			if($lin2[IdStatus] >= 1 && $lin2[IdStatus] <= 199){
				$AtivaCor = true;
			}else{
				$DesativaCor = true;	
			}
		}
		
		if($AtivaCor == true){
			if($DesativaCor == false){
				$Color	  =	getParametroSistema(15,2);
			}else{
				$Color	  =	'';
			}
		}else{						
			$Color	  =	'';					
		}
		
		if($Qtd == 0){
			$Color	  =	getParametroSistema(15,2);
		}
		
		echo "<reg>";	
		echo 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
		echo 	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
		echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
		echo 	"<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
		echo 	"<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";	
	}
	echo "</db>";
?>