<?
	$localModulo		=	1;
	$localOperacao		=	58;
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
	$filtro_pessoa				= url_string_xsl($_POST['filtro_pessoa'],'url',false);
	$filtro_parametro			= $_POST['filtro_parametro'];
	$filtro_descricao_servico	= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_data_inicio			= $_POST['filtro_data_inicio'];
	$filtro_data_termino		= $_POST['filtro_data_termino'];
	$filtro_valor_parametro		= url_string_xsl($_POST['filtro_valor_parametro'],'url',false);
	$filtro_status				= $_POST['filtro_status'];
	$filtro_IdPessoa			= $_GET['IdPessoa'];
	$filtro_IdContrato			= $_GET['IdContrato'];
	$filtro_IdServico			= $_GET['IdServico'];
	$filtro_contratos			= $_GET['Contratos'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&Pessoa=".$filtro_pessoa;
		$filtro_pessoa = str_replace("'", "\'", $filtro_pessoa);
		$filtro_sql .= " and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_pessoa%' or RazaoSocial like '%$filtro_pessoa%')";
	}
	
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_IdPessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_IdPessoa'";
	}
	
	if($filtro_contratos!=''){
		$filtro_url .= "&Contratos=".$filtro_contratos;
	}
	
	if($filtro_IdContrato!=''){
		$filtro_url .= "&IdContrato=".$filtro_IdContrato;
		$filtro_sql .= " and Contrato.IdContrato = '$filtro_IdContrato'";
	}
	
	if($filtro_IdServico!=''){
		$filtro_url .= "&IdContrato=".$filtro_IdServico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_IdServico'";
	}
		
	if($filtro_descricao_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_parametro!=""){
		$filtro_from .= " , ServicoParametro";
		$filtro_url .= "&DescricaoParametroServico=".$filtro_parametro;
		$filtro_sql .= " and ServicoParametro.IdServico = Servico.IdServico and ServicoParametro.DescricaoParametroServico = '$filtro_parametro'";
		$filtro_sql .= " and Contrato.IdLoja = ServicoParametro.IdLoja";
	}
	
	if($filtro_valor_parametro!=""){
		$filtro_from .= " , ContratoParametro";
		$filtro_url .= "&ValorParametroServico=".$filtro_valor_parametro;
		$filtro_sql .= " and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor like '%$filtro_valor_parametro%'";
	}	
	
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContratoVigencia.DataInicio >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_termino!=""){
		$filtro_url .= "&DataTermino=".$filtro_data_termino;
		$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContratoVigencia.DataTermino <= '$filtro_data_termino'";
	}
	
	if($filtro_valor!=''){
		$filtro_url .= "&Valor=".$filtro_valor;
		$filtro_sql .= " and ContratoVigencia.Valor like '%$filtro_valor%'";
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
								) ContratoAgenteAutorizado";
			$filtro_sql .=  " and Contrato.IdContrato = ContratoAgenteAutorizado.IdContrato";
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
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_mudar_status_xsl.php$filtro_url\"?>";
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
	$filtro_sql = "";
	$sql = "SELECT 
				ContaReceberDados.IdContaReceber,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				Pessoa.TipoPessoa,
				LocalCobranca.DescricaoLocalCobranca,
				LocalCobranca.AbreviacaoNomeLocalCobranca,
				ContaReceberDados.NumeroDocumento,
				Cidade.NomeCidade,
				Estado.NomeEstado,
				Estado.SiglaEstado,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.IdStatus, 
				ContaReceberRecebimento.IdRecibo,
				ContaReceberRecebimento.DataRecebimento,
				ContaReceberRecebimento.ValorRecebido,
				ContaReceberRecebimento.IdLocalCobranca IdLocalCobrancaRecebimento
			FROM
				ContaReceberDados LEFT JOIN ContaReceberRecebimento ON(
					ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja AND 
					ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber
				),
				Pessoa,
				PessoaEndereco,
				Pais,
				Estado,
				Cidade,
				LocalCobranca 
			WHERE 
				ContaReceberDados.IdLoja = $local_IdLoja AND 
				ContaReceberDados.IdPessoa = Pessoa.IdPessoa AND 
				ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND 
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa AND 
				PessoaEndereco.IdPais = Pais.IdPais AND 
				Pais.IdPais = Estado.IdPais AND 
				PessoaEndereco.IdEstado = Estado.IdEstado AND 
				Estado.IdPais = Cidade.IdPais AND 
				Estado.IdEstado = Cidade.IdEstado AND 
				PessoaEndereco.IdCidade = Cidade.IdCidade AND 
				ContaReceberDados.IdLoja = LocalCobranca.IdLoja AND 
				ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca
				$filtro_sql
			GROUP BY
				ContaReceberDados.IdContaReceber DESC
			$Limit";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		if(empty($lin[ValorRecebido])) {
			$lin[ValorRecebido] = 0.00;
		}
		
		$lin[ValorFinalTemp] = number_format($lin[ValorFinal],2,",","");
		$lin[ValorRecebidoTemp] = number_format($lin[ValorRecebido],2,",","");
		$lin[DataVencimento] = dataConv($lin[DataVencimento],"Y-m-d","Ymd");
		$lin[DataVencimentoTemp] = dataConv($lin[DataVencimento],"Ymd","d/m/Y");
		$lin[DataRecebimento] = dataConv($lin[DataRecebimento],"Y-m-d","Ymd");
		$lin[DataRecebimentoTemp] = dataConv($lin[DataRecebimento],"Ymd","d/m/Y");
		
		$sql_tmp = "SELECT 
						LocalCobranca.DescricaoLocalCobranca, 
						LocalCobranca.AbreviacaoNomeLocalCobranca 
					FROM 
						LocalCobranca 
					where 
						LocalCobranca.IdLoja = '$local_IdLoja' AND 
						LocalCobranca.IdLocalCobranca = '$lin[IdLocalCobrancaRecebimento]'";
		$res_tmp = mysql_query($sql_tmp, $con);
		$lin_tmp = mysql_fetch_array($res_tmp);
		
		$lin[DescricaoLocalCobrancaRecebimento] = $lin_tmp[DescricaoLocalCobranca];
		$lin[AbreviacaoNomeLocalCobrancaRecebimento] = $lin_tmp[AbreviacaoNomeLocalCobranca];
		
		
		/*$lin[ValorFinalTemp] = number_format($lin[ValorFinal],2,",","");
		
		if($lin[ValorFinal] == ''){
			$lin[ValorFinal] = 0;
		}
		
		
		if($lin[Valor] == ''){
			$lin[Valor] = 0;
		}
		
		$lin[ValorDescontoTemp] = number_format($lin[ValorDesconto],2,",","");
		
		if($lin[ValorDesconto] == ''){
			$lin[ValorDesconto]	=	0;
		}
		
		
		$lin[DataInicio] 		= dataConv($lin[DataInicio],"Y-m-d","Ymd");
		$lin[DataTermino] 		= dataConv($lin[DataTermino],"Y-m-d","Ymd");
		
		$sql2 = "select substr(ValorParametroSistema,1,3) ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema=$lin[TipoContrato]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$sql3 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		
		if($lin[VarStatus] != ''){
			switch($lin[IdStatus]){
				case '201':
					$lin3[Status]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin3[Status]);	
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
		
		$sql4 = "select DescricaoParametroServico from Contrato,Servico,ServicoParametro where Contrato.IdLoja=$local_IdLoja and Contrato.IdLoja = Servico.IdLoja and Contrato.IdLoja = ServicoParametro.IdLoja and Contrato.IdServico = Servico.IdServico and Servico.IdServico = ServicoParametro.IdServico and Contrato.IdContrato=$lin[IdContrato] and ServicoParametro.ParametroDemonstrativo = 1";
		$res4 = @mysql_query($sql4,$con);
		$lin4 = @mysql_fetch_array($res4);
		*/
		
		$sql3 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		
		if($lin[VarStatus] != ''){
			switch($lin[IdStatus]){
				case '201':
					$lin3[Status]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin3[Status]);	
					break;
			}					
		}
		
		
		switch($lin[IdStatus]){
			case '0': # Cancelado
				/*$Color	  =	getParametroSistema(15,2);
				$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
				$lin[Recebido]  = "N";
				$lin[MsgLink]	= "Canc.";
				$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";	
				$Target			= "_self";*/
				break;
			case '1': # Aguardando Pagamento
				/*if($filtro_impressao == 1){	//Html
					$lin[Link]		= "boleto.php";
					$lin[Tipo]		= "html";
				} else{					//Pdf
					$lin[Link]		= "boleto.php";
					$lin[Tipo]		= "pdf";
				}
				
				if(file_exists($lin[Link])){
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Boleto";
					$Target			= "_blank";	
					$lin[Link]		.= "?Tipo=$lin[Tipo]&ContaReceber=$lin[MD5]";
				} else{	
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "";
					$lin[Link]		= "";
					$Target			= "";	
				}
				
				$lin[DataRecebimento] 		= "";
				$lin[DataRecebimentoTemp] 	= "";	
				
				$Color	  = "";		
				$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";		
				
				
				// Vencimento alterado
				$sql6 = "select
							count(*) > 1 VencimentoAlterado
							from ContaReceberVencimento
							where IdLoja = $local_IdLoja and  
							IdContaReceber = $lin[IdContaReceber]";
				$res6 = @mysql_query($sql6,$con);
				$lin6 = @mysql_fetch_array($res6);
				if ($lin6[VencimentoAlterado]){
					$Color	 	=  	getCodigoInterno(45,1);
				}
				*/
				break;
			case '2': # Quitado
				/*$lin[Recebido] 	= "S";
				$lin[MsgLink]	= "Recibo";
				$lin[Link]		= "recibo.php?Recibo=$lin[Recibo]";
				$Color	  		= getParametroSistema(15,3);		
				$ImgExc	  		= "../../img/estrutura_sistema/ico_del.gif";
				$Target			= "_blank";	
				
				if($lin[ValorRecebido] != '' && $lin[ValorRecebido] < $lin[Valor]){
					$Color	  		= getParametroSistema(15,7);		
				} 
				
				$sql3 = "select 
							ContaReceberRecebimentoTemp.QtdReciboAtivo,
							ContaReceberRecebimento.IdCaixa,
							LocalCobranca.AbreviacaoNomeLocalCobranca 
						from
							(
								select 
									count(*) QtdReciboAtivo,
									ContaReceberRecebimentoTemp.IdLocalCobranca 
								from
									(
										select 
											ContaReceberRecebimento.IdLoja,
											ContaReceberRecebimento.IdContaReceber,
											ContaReceberRecebimento.IdLocalCobranca,
											ContaReceberRecebimento.IdStatus 
										from
											ContaReceberRecebimento 
										where 
											ContaReceberRecebimento.IdLoja = $local_IdLoja and 
											ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and 
											ContaReceberRecebimento.IdStatus = 1 
										order by ContaReceberRecebimento.IdContaReceberRecebimento desc 
										limit 1
									) ContaReceberRecebimentoTemp,
									ContaReceberRecebimento 
								where ContaReceberRecebimentoTemp.IdLoja = ContaReceberRecebimento.IdLoja and
									ContaReceberRecebimentoTemp.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
									ContaReceberRecebimentoTemp.IdStatus = ContaReceberRecebimento.IdStatus
							) ContaReceberRecebimentoTemp,
							ContaReceberRecebimento,
							LocalCobranca 
						where 
							ContaReceberRecebimento.IdLoja = $local_IdLoja and 
							ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and 
							ContaReceberRecebimento.IdLoja = LocalCobranca.IdLoja and 
							case
								when ContaReceberRecebimento.IdLocalCobranca is null && ContaReceberRecebimentoTemp.IdLocalCobranca is null 
								then true
								when ContaReceberRecebimentoTemp.IdLocalCobranca is null 
								then ContaReceberRecebimento.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
								else ContaReceberRecebimentoTemp.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
							end 
						order by ContaReceberRecebimento.IdContaReceberRecebimento desc 
						limit 1;";
				$res3 = @mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);
			
				if($lin3[IdCaixa] != ''){
					$lin3[AbreviacaoNomeLocalCobranca] = "Caixa ".$lin3[IdCaixa];
				} else {
					if($lin3[QtdReciboAtivo] > 1){
						$lin3[AbreviacaoNomeLocalCobranca]	= '***';
						$lin[ValorRecebido]					= '***';
						$lin[DataRecebimento] 				= '***';
						$lin[DataRecebimentoTemp] 			= '***';
						$lin[ValorRecebidoTemp]				= '***';
						
					}else{
						$lin3[AbreviacaoNomeLocalCobranca]	=	$lin3[AbreviacaoNomeLocalCobranca];
					}
				}
				
				// Pagamento em atraso
				$sql7 = "select
							ContaReceberRecebimento.DataRecebimento,
							ContaReceberVencimento.DataVencimento 
						from
							ContaReceberVencimento,
							ContaReceberRecebimento 
						where
							ContaReceberRecebimento.IdLoja = $local_IdLoja and
							ContaReceberRecebimento.IdLoja = ContaReceberVencimento.IdLoja and
							ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and
							ContaReceberRecebimento.IdContaReceber = ContaReceberVencimento.IdContaReceber";
				$res7 = @mysql_query($sql7,$con);
				$lin7 = @mysql_fetch_array($res7);
				
				if ($lin7[DataRecebimento] > $lin7[DataVencimento]){
					$Color	 	=  getCodigoInterno(44,1);
				}
				*/
				break;
			case 3: # Aguardando Envio
				/*$lin[Recebido]  = "N";
				$lin[MsgLink]	= "";
				$lin[Link]		= "";
				$Target			= "";	
				
				$lin[DataRecebimento] 		= "";
				$lin[DataRecebimentoTemp] 	= "";	
				
				$Color	  = "";		
				$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";		
				
				// Vencimento alterado
				$sql6 = "select
							count(*) > 0 VencimentoAlterado
							from ContaReceberVencimento
							where IdLoja = $local_IdLoja and  
							IdContaReceber = $lin[IdContaReceber]";
				$res6 = @mysql_query($sql6,$con);
				$lin6 = @mysql_fetch_array($res6);
				if ($lin6[VencimentoAlterado]){
					$Color	 	= 	getCodigoInterno(45,1);
				}
				*/
				break;
			case 4:	# Em Arquivo de Remessa
				/*$sql6 = "select
							count(*) > 0 VencimentoAlterado
							from ContaReceberVencimento
							where IdLoja = $local_IdLoja and  
							IdContaReceber = $lin[IdContaReceber]";
				$res6 = @mysql_query($sql6,$con);
				$lin6 = @mysql_fetch_array($res6);
				if ($lin6[VencimentoAlterado]){
					$Color	 =   getCodigoInterno(45,1);
				}
				*/
				break;
			case 5:	# Compromisso Agendado
				/*$sql6 = "select
							count(*) > 0 VencimentoAlterado
							from ContaReceberVencimento
							where IdLoja = $local_IdLoja and  
							IdContaReceber = $lin[IdContaReceber]";
				$res6 = @mysql_query($sql6,$con);
				$lin6 = @mysql_fetch_array($res6);
				if ($lin6[VencimentoAlterado]){
					$Color	 	=  	getCodigoInterno(45,1);
				}
				*/
				break;	
			case 6:	# Devolvido
				/*$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";
				$lin[Recebido]  = "N";
				$lin[MsgLink]	= "Devolvido";
				$Color			= "";	
				$Target			= "";		
				$ImgExc			= "../../img/estrutura_sistema/ico_del_c.gif";*/
				break;
			case '7': # Excluído
				/*$Color	  =	getParametroSistema(15,2);
				$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
				$lin[Recebido]  = "N";
				$lin[MsgLink]	= "Exc.";
				$lin[Link]		= "";	
				$Target			= "";*/
				break;
			case '8': # Agrupado
				/*$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";
				$lin[Recebido]  = "N";
				$lin[MsgLink]	= "Agrupado";
				$Color			= "";	
				$Target			= "";		
				$ImgExc			= "../../img/estrutura_sistema/ico_del_c.gif";*/
				break;
		}
		
		if($lin[TipoPessoa] == '1'){
			$lin[Nome] = $lin[trim(getCodigoInterno(3,24))];	
		}							
		
		echo "<reg>";	
		echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";	
		echo 	"<IdRecibo><![CDATA[$lin[IdRecibo]]]></IdRecibo>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
		echo 	"<NumeroDocumento><![CDATA[$lin[NumeroDocumento]]]></NumeroDocumento>";
		echo 	"<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
		echo 	"<NomeEstado><![CDATA[$lin[NomeEstado]]]></NomeEstado>";
		echo 	"<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
		echo 	"<ValorFinal><![CDATA[$lin[ValorFinal]]]></ValorFinal>";
		echo 	"<ValorFinalTemp><![CDATA[$lin[ValorFinalTemp]]]></ValorFinalTemp>";
		echo 	"<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
		echo 	"<DataVencimentoTemp><![CDATA[$lin[DataVencimentoTemp]]]></DataVencimentoTemp>";
		echo 	"<ValorRecebido><![CDATA[$lin[ValorRecebido]]]></ValorRecebido>";
		echo 	"<ValorRecebidoTemp><![CDATA[$lin[ValorRecebidoTemp]]]></ValorRecebidoTemp>";
		echo 	"<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
		echo 	"<DataRecebimentoTemp><![CDATA[$lin[DataRecebimentoTemp]]]></DataRecebimentoTemp>";
		echo 	"<DescricaoLocalCobrancaRecebimento><![CDATA[$lin[DescricaoLocalCobrancaRecebimento]]]></DescricaoLocalCobrancaRecebimento>";
		echo 	"<AbreviacaoNomeLocalCobrancaRecebimento><![CDATA[$lin[AbreviacaoNomeLocalCobrancaRecebimento]]]></AbreviacaoNomeLocalCobrancaRecebimento>";
		echo 	"<Status><![CDATA[$lin3[Status]]]></Status>";
		
		
		
		echo 	"<IdServico>$lin[IdServico]</IdServico>";	
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
		echo 	"<DataInicioTemp><![CDATA[$lin[DataInicioTemp]]]></DataInicioTemp>";
		echo 	"<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
		echo 	"<DataTerminoTemp><![CDATA[$lin[DataTerminoTemp]]]></DataTerminoTemp>";
		echo 	"<Valor>$lin[Valor]</Valor>";
		echo 	"<ValorTemp><![CDATA[$lin[ValorTemp]]]></ValorTemp>";
		echo 	"<ValorDesconto>$lin[ValorDesconto]</ValorDesconto>";
		echo 	"<ValorDescontoTemp><![CDATA[$lin[ValorDescontoTemp]]]></ValorDescontoTemp>";
		echo 	"<TipoContrato><![CDATA[$lin2[ValorParametroSistema]]]></TipoContrato>";
		echo 	"<DescricaoParametroServico><![CDATA[$lin4[DescricaoParametroServico]]]></DescricaoParametroServico>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>