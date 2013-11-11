<?
	$localModulo		=	1;
	$localOperacao		=	88;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 	
	
	$local_IdLoja				= $_SESSION['IdLoja'];
	$local_IdPessoaLogin		= $_SESSION['IdPessoa'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_ordem2				= $_POST['filtro_ordem2'];
	$filtro_ordem_direcao2		= $_POST['filtro_ordem_direcao2'];
	$filtro_localTipoDado2		= $_POST['filtro_tipoDado2'];
	$filtro_pessoa				= url_string_xsl($_POST['filtro_pessoa'],'url',false);
	$filtro_descricao_servico	= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_estado				= $_POST['filtro_estado'];
	$filtro_cidade				= url_string_xsl($_POST['filtro_cidade'],'url',false);
	$filtro_status				= $_POST['filtro_status'];
	$filtro_IdPessoa			= $_GET['IdPessoa'];
	$filtro_IdContrato			= $_GET['IdContrato'];
	$filtro_IdServico			= $_GET['IdServico'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_pais				= getCodigoInterno(3,1);
	$filtro_id_servico			= $_POST['filtro_id_servico'];
	$filtro_coluna_telefone		= $_POST['filtro_coluna_telefone'];
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	
	LimitVisualizacao("listar");	
	if($filtro_IdServico == ''){
		$filtro_IdServico	= $_POST['IdServico'];
	}
	
	$filtro_cancelado				=	$_SESSION["filtro_contrato_cancelado"];
	$filtro_soma					=	$_SESSION["filtro_contrato_soma"];
	$filtro_QTDCaracterColunaPessoa	= $_SESSION["filtro_QTDCaracterColunaPessoa"];
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")				$filtro_url	.= "&Ordem=$filtro_ordem";
	if($filtro_ordem_direcao != "")		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	if($filtro_localTipoDado != "")		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_ordem2 != "")			$filtro_url	.= "&Ordem2=$filtro_ordem2";
	if($filtro_ordem_direcao2 != "")	$filtro_url .= "&OrdemDirecao2=$filtro_ordem_direcao2";
	if($filtro_localTipoDado2 != "")	$filtro_url .= "&TipoDado2=$filtro_localTipoDado2";
	
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_IdPessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_IdPessoa'";
	}
	
	if($filtro_IdContrato!=''){
		$filtro_url .= "&IdContrato=".$filtro_IdContrato;
		$filtro_sql .= " and Contrato.IdContrato = '$filtro_IdContrato'";
	}
	
	if($filtro_IdServico!=''){
		$filtro_url .= "&IdContrato=".$filtro_IdServico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_IdServico'";
	}
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&Pessoa=".$filtro_pessoa;
		$filtro_pessoa = str_replace("'", "\'", $filtro_pessoa);
		$filtro_sql .= " and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_pessoa%' or RazaoSocial like '%$filtro_pessoa%')";
	}
		
	if($filtro_descricao_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_estado!=""){
		$filtro_url  .= "&IdEstado=".$filtro_estado;
		$filtro_sql .= " and PessoaEndereco.IdPais = $filtro_pais and PessoaEndereco.IdEstado = $filtro_estado";
	}
	
	if($filtro_cidade!=""){
		$filtro_url  .= "&NomeCidade=".$filtro_cidade;
		$filtro_sql .= " and Cidade.NomeCidade like '$filtro_cidade'";
	}
	
	if($filtro_status!=''){
		$filtro_url .= "&IdStatus=".$filtro_status;
		
		$aux	=	explode("G_",$filtro_status);
		
		if($aux[1]!=""){
			switch($aux[1]){
				case '1':
					$filtro_sql .= " and (Contrato.IdStatus >= 1 and Contrato.IdStatus < 199)";
					break;
				case '2':
					$filtro_sql .= " and (Contrato.IdStatus >= 200 and Contrato.IdStatus < 300)";
					break;
				case '3':
					$filtro_sql .= " and (Contrato.IdStatus >= 300 and Contrato.IdStatus < 400)";
					break;
			}
		}else{
			$filtro_sql .= " and Contrato.IdStatus = '$filtro_status'";
		}
	}
	
	if($filtro_soma!=""){
		$filtro_url .= "&Soma=".$filtro_soma;
	}
	
	if($filtro_coluna_telefone=='' && $filtro_valor!=""){
		$filtro_valor	=	"";	
		$filtro_url .= "&Telefone=$filtro_valor";
	}else{
		if($filtro_valor!=""){
			$filtro_url .= "&Valor=".$filtro_valor;
		}
	}
	
	if($filtro_coluna_telefone!=''){
		$filtro_url .= "&Telefone=$filtro_coluna_telefone";
		if($filtro_valor != ""){
			switch($filtro_coluna_telefone){
				case 'Fone':
					$filtro_valor = str_replace(" ","",$filtro_valor);
					$filtro_valor = str_replace("-","",$filtro_valor);
					
					$filtro_sql .=	" and (Pessoa.Telefone1 like '%$filtro_valor%' or Pessoa.Telefone1 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Telefone2 like '%$filtro_valor%' or Pessoa.Telefone2 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Telefone3 like '%$filtro_valor%' or Pessoa.Telefone3 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Celular like '%$filtro_valor%' or Pessoa.Celular like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Fax like '%$filtro_valor%' or Pessoa.Fax like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or PessoaEndereco.Telefone like '%$filtro_valor%' or PessoaEndereco.Telefone like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or PessoaEndereco.Celular like '%$filtro_valor%' or PessoaEndereco.Celular like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or PessoaEndereco.Fax like '%$filtro_valor%' or PessoaEndereco.Fax like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."-".substr($filtro_valor,6,10)."%' or Pessoa.Telefone1 like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Telefone2 like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Telefone3 like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Celular like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Fax like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or PessoaEndereco.Celular like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or PessoaEndereco.Fax like '%(".substr($filtro_valor,1,2).")".substr($filtro_valor,4,4)."-".substr($filtro_valor,8,9)."%' or Pessoa.Telefone1 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Telefone2 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Telefone3 like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Celular like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Fax like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or PessoaEndereco.Telefone like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or PessoaEndereco.Celular like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or PessoaEndereco.Fax like '%(".substr($filtro_valor,0,2).")".substr($filtro_valor,2,4)."".substr($filtro_valor,6,10)."%' or Pessoa.Telefone1 like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or Pessoa.Telefone2 like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or Pessoa.Telefone3 like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or Pessoa.Celular like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or Pessoa.Fax like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or PessoaEndereco.Telefone like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or PessoaEndereco.Celular like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' or PessoaEndereco.Fax like '%".substr($filtro_valor,0,4)."-".substr($filtro_valor,4,8)."%' ) ";
					break;
			}
		}else{
			switch($filtro_coluna_telefone){
				case 'Fone':
					$filtro_sql .=	" and (Pessoa.Telefone1 = '' and Pessoa.Telefone2 = '' and Pessoa.Telefone3 = '' and Pessoa.Celular = '' and Pessoa.Fax = '' and PessoaEndereco.Telefone = '' and PessoaEndereco.Celular = '' and PessoaEndereco.Fax = '')";
					break;
			}
		}
		
	}
	
	if($filtro_cancelado != ""){
		$filtro_url .= "&Cancelado=".$filtro_cancelado;
		if($filtro_status == ""){
			if($filtro_cancelado == 2){
				switch($filtro_status){
					case 'G_1':
						$filtro_sql  .= " and (Contrato.IdStatus >0 and Contrato.IdStatus <=499)";
						break;
					case 102:
						$filtro_sql  .= " and (Contrato.IdStatus >0 and Contrato.IdStatus <=499)";
						break;
					case 101:
						$filtro_sql  .= " and (Contrato.IdStatus >0 and Contrato.IdStatus <=499)";
						break;
					case 1:
						$filtro_sql  .= " and (Contrato.IdStatus >0 and Contrato.IdStatus <=499)";
						break;
					default :
						$filtro_sql  .= " and (Contrato.IdStatus >199 and Contrato.IdStatus <=499)";
						break;
					
				}
			}
		}
	}
	if($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_id_servico'";
	}
	/*
	if($_SESSION["RestringirCarteira"] == true){
		$sqlAux		.=	",(select 
								AgenteAutorizadoPessoa.IdContrato 
						   from 
								AgenteAutorizadoPessoa,
								Carteira 
						   where 
								AgenteAutorizadoPessoa.IdLoja = $local_IdLoja and 
								AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
								AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
								Carteira.IdCarteira = $local_IdPessoaLogin and 
								Carteira.Restringir = 1 and 
								Carteira.IdStatus = 1
							) ContratoCarteira";
		$filtro_sql .=  " and  Contrato.IdContrato = ContratoCarteira.IdContrato";
	}else{
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado
								where 
									AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
									AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1
								) PessoaAgenteAutorizado";
			$filtro_sql .=  " and Contrato.IdContrato = PessoaAgenteAutorizado.IdContrato";
		}
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado,
									Carteira
								where 
									AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
									AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
									AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
									Carteira.IdCarteira = $local_IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1
								) ContratoAgenteCarteira";
			$filtro_sql .=  " and  Contrato.IdContrato = ContratoAgenteCarteira.IdContrato";
		}
	}*/
	$sqlAgente="select 
					Restringir
				from
					AgenteAutorizado 
				where 
					AgenteAutorizado.IdLoja = $local_IdLoja and
					AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and
					AgenteAutorizado.IdStatus = 1 ";
	$resAgente = mysql_query($sqlAgente,$con);
	if($linAgente = mysql_fetch_array($resAgente)){
		
		$sqlCarteira="	select 
							Restringir 
						from
							Carteira 
						where
							Carteira.IdLoja = $local_IdLoja and
							Carteira.IdCarteira = $local_IdPessoaLogin and
							Carteira.IdStatus = 1";
		$resCarteira = mysql_query($sqlCarteira,$con);
		$linCarteira = mysql_fetch_array($resCarteira);
		
		if($linAgente["Restringir"] == '1'){
			$restringirAgente = "AgenteAutorizado.Restringir = '$linAgente[Restringir]' and";
			if($linCarteira["Restringir"] == '1'){
				$restringirCarteira = "AgenteAutorizadoPessoa.IdCarteira = $local_IdPessoaLogin and";
			}else{
				$restringirCarteira = "";
			}
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado,
									Carteira
								where 
									AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
									AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and 
									$restringirAgente
									$restringirCarteira
									AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and 
									AgenteAutorizado.IdStatus = 1
								) ContratoAgenteAutorizado";
			$filtro_sql .=  " and Contrato.IdContrato = ContratoAgenteAutorizado.IdContrato";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_cidade_xsl.php$filtro_url\"?>";
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
				Pessoa.Celular,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Telefone3,
				Pessoa.Fax,
				PessoaEndereco.Telefone,
				PessoaEndereco.Celular CelularEndereco,
				PessoaEndereco.Fax FaxEndereco,
				Contrato.IdContrato,
				Contrato.IdServico,
				substring(Servico.DescricaoServico,1,15) DescricaoServico,
				Contrato.IdPessoa,				    
				Pessoa.TipoPessoa,
				substring(Pessoa.Nome,1,$filtro_QTDCaracterColunaPessoa) Nome,
				substring(Pessoa.RazaoSocial,1,$filtro_QTDCaracterColunaPessoa) RazaoSocial,
				Contrato.DataInicio,
				Contrato.DataTermino,
				LocalCobranca.AbreviacaoNomeLocalCobranca,
				ContratoVigenciaAtiva.Valor,
				ContratoVigenciaAtiva.ValorDesconto,
				(ContratoVigenciaAtiva.Valor - ContratoVigenciaAtiva.ValorDesconto) ValorFinal,
				ContratoVigenciaAtiva.IdTipoDesconto,
				Contrato.TipoContrato,
				Contrato.IdStatus,
				Contrato.VarStatus,
				Contrato.DiaCobranca
			from
				Loja,
				Contrato 
				left join (
					select 
						ContratoVigenciaAtiva.IdContrato, 
						ContratoVigenciaAtiva.DataInicio, 
						ContratoVigenciaAtiva.DataTermino, 
						ContratoVigenciaAtiva.Valor, 
						ContratoVigenciaAtiva.ValorDesconto, 
						ContratoVigenciaAtiva.IdTipoDesconto 
					from 
						Loja, 
						ContratoVigenciaAtiva, 
						Contrato 
					where 
						Loja.IdLoja = $local_IdLoja and 
						ContratoVigenciaAtiva.IdLoja = Loja.IdLoja and 
						Contrato.IdLoja = Loja.IdLoja and 
						ContratoVigenciaAtiva.IdContrato = Contrato.IdContrato) 
				ContratoVigenciaAtiva on Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato,
				Servico,
				Pessoa,
				PessoaEndereco,
				Pais,
				Estado,
				Cidade,
				LocalCobranca $filtro_from $sqlAux
			where
				Loja.IdLoja = $local_IdLoja and
				Contrato.IdLoja = Loja.IdLoja and
				Loja.IdLoja = Servico.IdLoja and
				Loja.IdLoja = LocalCobranca.IdLoja and
				Contrato.IdServico = Servico.IdServico and
				Contrato.IdPessoa = Pessoa.IdPessoa and
				Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				PessoaEndereco.IdPessoa = Pessoa.IdPessoa and
				PessoaEndereco.IdPessoaEndereco = Contrato.IdPessoaEndereco and
				Pais.IdPais = PessoaEndereco.IdPais and
				Estado.IdEstado = PessoaEndereco.IdEstado and
				Cidade.IdCidade = PessoaEndereco.IdCidade and
				Pais.IdPais = Estado.IdPais and
				Pais.IdPais = Cidade.IdPais and
				Estado.IdEstado = Cidade.IdEstado $filtro_sql
			group by
				Contrato.IdContrato
			order by
				Contrato.IdContrato desc
			$Limit";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$lin[ValorFinalTemp] = number_format($lin[ValorFinal],2,",","");
		
		if($lin[ValorFinal] == ''){
			$lin[ValorFinal] = 0;
		}
		
		$lin[ValorTemp] = number_format($lin[Valor],2,",","");
		
		if($lin[Valor] == ''){
			$lin[Valor] = 0;
		}
		
		$lin[ValorDescontoTemp] = number_format($lin[ValorDesconto],2,",","");
		
		if($lin[ValorDesconto] == ''){
			$lin[ValorDesconto]	=	0;
		}
		
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
		
		$lin[DataInicioTemp] 	= dataConv($lin[DataInicio],"Y-m-d","d/m/Y");
		$lin[DataTerminoTemp] 	= dataConv($lin[DataTermino],"Y-m-d","d/m/Y");
		
		$lin[DataInicio] 		= dataConv($lin[DataInicio],"Y-m-d","Ymd");
		$lin[DataTermino] 		= dataConv($lin[DataTermino],"Y-m-d","Ymd");
		
		$lin[DataTemporaria]	= dataConv($lin[VarStatus],"d/m/Y","Ymd");
		
		$sql2 = "select substr(ValorParametroSistema,1,3) ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema=$lin[TipoContrato]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$sql3 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		
		$sql10	=	"select IdContrato IdContratoPai from ContratoAutomatico where IdLoja = $local_IdLoja and IdContratoAutomatico = $lin[IdContrato] group by IdContrato";
		$res10 	= 	@mysql_query($sql10,$con);
		$lin10  = 	@mysql_fetch_array($res10);		
		
		if($lin10[IdContratoPai] != ""){
			$Img	  = "../../img/estrutura_sistema/ico_del_c.gif";
		}else{
			if($lin[IdStatus] == 1){
				$Img	  = "../../img/estrutura_sistema/ico_del.gif";
			}else{
				$Img	  = "../../img/estrutura_sistema/ico_del_c.gif";
			}
		}
		
		
		$lin3[StatusDesc]	=	$lin3[Status];
		
		
		if($lin[VarStatus] != ''){
			switch($lin[IdStatus]){
				case '201':
					$lin3[StatusDesc]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin3[Status]);	
					break;
			}					
		}
		
		$IdStatus	=	substr($lin[IdStatus],0,1);
		
		switch($IdStatus){
			case '1':
				$Color	  = "";
				break;
			case '2':
				$Color	  = getParametroSistema(15,3);
				break;
			case '3':
				$Color	  = getParametroSistema(15,2);
				break;
		}
		
		$DescricaoParametroServico	=	"";
		$DescricaoParametroServico = "Nome Serviço=".$lin[DescricaoServicoAlt]."\n".$DescricaoParametroServico;
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}	
		
		if($filtro_status != ''){//não todos
			if($filtro_status == 'G_1' || $filtro_status <= 199){//cancelados
				if($filtro_soma == 2){//não
					$lin[Valor]			=	$lin[Valor];
					$lin[ValorDesconto]	=	$lin[ValorDesconto];
					$lin[ValorFinal]	=	$lin[ValorFinal];
				}else{//sim
					$lin[Valor]			=	$lin[Valor];
					$lin[ValorDesconto]	=	$lin[ValorDesconto];
					$lin[ValorFinal]	=	$lin[ValorFinal];	
				}
			}else{
				$lin[Valor]			=	$lin[Valor];
				$lin[ValorDesconto]	=	$lin[ValorDesconto];
				$lin[ValorFinal]	=	$lin[ValorFinal];
			}
		}else{
			if($filtro_soma == 2){//não
				if($lin[IdStatus] <= 199){
					$lin[Valor]			=	0;
					$lin[ValorDesconto]	=	0;
					$lin[ValorFinal]	=	0;
				}else{
					$lin[Valor]			=	$lin[Valor];
					$lin[ValorDesconto]	=	$lin[ValorDesconto];
					$lin[ValorFinal]	=	$lin[ValorFinal];	
				}
			}else{//sim
				$lin[Valor]			=	$lin[Valor];
				$lin[ValorDesconto]	=	$lin[ValorDesconto];
				$lin[ValorFinal]	=	$lin[ValorFinal];
			}
		}							
		
		echo "<reg>";	
		echo 	"<IdContrato>$lin[IdContrato]</IdContrato>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<IdServico>$lin[IdServico]</IdServico>";	
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
		echo 	"<DiaCobranca><![CDATA[$lin[DiaCobranca]]]></DiaCobranca>";
		echo 	"<Status><![CDATA[$lin3[Status]]]></Status>";
		echo 	"<StatusDesc><![CDATA[$lin3[StatusDesc]]]></StatusDesc>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
		
		echo 	"<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
		echo 	"<DataInicioTemp><![CDATA[$lin[DataInicioTemp]]]></DataInicioTemp>";
		
		echo 	"<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
		echo 	"<DataTerminoTemp><![CDATA[$lin[DataTerminoTemp]]]></DataTerminoTemp>";
		
		echo 	"<DataTemporaria><![CDATA[$lin[DataTemporaria]]]></DataTemporaria>";
		
		echo 	"<Valor>$lin[Valor]</Valor>";
		echo 	"<ValorTemp><![CDATA[$lin[ValorTemp]]]></ValorTemp>";
		echo 	"<ValorFinal>$lin[ValorFinal]</ValorFinal>";
		echo 	"<ValorFinalTemp><![CDATA[$lin[ValorFinalTemp]]]></ValorFinalTemp>";
		echo 	"<ValorDesconto>$lin[ValorDesconto]</ValorDesconto>";
		echo 	"<ValorDescontoTemp><![CDATA[$lin[ValorDescontoTemp]]]></ValorDescontoTemp>";
		
		echo 	"<TipoContrato><![CDATA[$lin2[ValorParametroSistema]]]></TipoContrato>";		
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<Img><![CDATA[$Img]]></Img>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
