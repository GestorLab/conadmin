<?
	$localModulo		=	1;
	$localOperacao		=	130;
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
	
	$filtro_pessoa				= url_string_xsl($_POST['filtro_pessoa'],'url',false);
	$filtro_descricao_servico	= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_parametro			= $_POST['filtro_parametro'];
	$filtro_valor_parametro		= url_string_xsl($_POST['filtro_valor_parametro'],'url',false);
	$filtro_status				= $_POST['filtro_status'];
	$filtro_cancelado			= $_POST['filtro_contrato_cancelado'];
	$filtro_soma				= $_POST['filtro_contrato_soma'];
	$filtro_ordenar_por			= $_POST['filtro_ordenar_por'];	
	$filtro_contrato_parametro	= $_POST['filtro_contrato_parametro'];
	$filtro_consultar_por		= $_POST['filtro_consultar_por'];	
	$filtro_id_servico			= $_POST['filtro_id_servico'];
	$filtro_id_pessoa			= $_POST['filtro_id_pessoa'];
	
	$filtro_IdPessoa			= $_GET['IdPessoa'];
	$filtro_IdContrato			= $_GET['IdContrato'];
	$filtro_IdServico			= $_GET['IdServico'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	if($filtro_IdPessoa == ''){
		$filtro_IdPessoa		= $_POST['IdPessoa'];
	}
	if($filtro_IdContrato == ''){
		$filtro_IdContrato		= $_POST['IdContrato'];
	}
	if($filtro_IdServico == ''){
		$filtro_IdServico		= $_POST['IdServico'];
	}
	
	$filtro_cancelado			= $_SESSION["filtro_contrato_cancelado"];	
	
	$filtro_url		= "";
	$filtro_sql		= "";
	$filtro_sqlAux	= "";
	$filtro_from	= "";	
	$order_by		= "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")				$filtro_url	.= "&Ordem=$filtro_ordem";
	if($filtro_ordem_direcao != "")		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	if($filtro_localTipoDado != "")		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_ordem2 != "")			$filtro_url	.= "&Ordem2=$filtro_ordem2";
	if($filtro_ordem_direcao2 != "")	$filtro_url .= "&OrdemDirecao2=$filtro_ordem_direcao2";
	if($filtro_localTipoDado2 != "")	$filtro_url .= "&TipoDado2=$filtro_localTipoDado2";
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&Pessoa=".$filtro_pessoa;
		$filtro_pessoa = str_replace("'", "\'", $filtro_pessoa);
		$filtro_sql .= " and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_pessoa%' or RazaoSocial like '%$filtro_pessoa%')";
	}
	
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_IdPessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_IdPessoa'";
	}
	
	if($filtro_IdContrato!=''){
		$filtro_url .= "&IdContrato=".$filtro_IdContrato;
		$filtro_sql .= " and Contrato.IdContrato = '$filtro_IdContrato'";
	}
	
	if($filtro_IdServico!=''){
		$filtro_url .= "&IdServico=".$filtro_IdServico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_IdServico'";
	}
	
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_IdPessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_IdPessoa'";
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
	
		$filtro_from .= " , ContratoParametro";
		$filtro_url .= "&ValorParametroServico=".$filtro_valor_parametro;
		
		if($filtro_valor_parametro != ""){
			$filtro_sql .= " and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor like '%$filtro_valor_parametro%'";
		}else{
			$filtro_sql .= " and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor = '$filtro_valor_parametro'";		
		}
	}	
	
	if($filtro_ordenar_por!=''){
		switch($filtro_ordenar_por){
			case 1:
				$order_by .= ", Estado.NomeEstado";	
				if($filtro_consultar_por != ""){
					$filtro_url .= "&ConsultarPor=".$filtro_consultar_por;
					
					if(count($filtro_consultar_por) > 2){
						$filtro_sqlAux .= " and Estado.NomeEstado like '%$filtro_consultar_por%'";	
					}else{
						$filtro_sqlAux .= " and Estado.SiglaEstado like '$filtro_consultar_por'";
					}
				}
				break;
			case 2:
				$order_by .= ", Estado.NomeEstado, Cidade.NomeCidade";
				
				if($filtro_consultar_por != ""){
					$filtro_url .= "&ConsultarPor=".$filtro_consultar_por;
					$filtro_sqlAux .= " and Cidade.NomeCidade like '%$filtro_consultar_por%'";	
				}
				break;
			case 3: 
				$order_by .= ", Estado.NomeEstado, Cidade.NomeCidade, PessoaEndereco.Bairro";
				
				if($filtro_consultar_por != ""){
					$filtro_url .= "&ConsultarPor=".$filtro_consultar_por;
					$filtro_sqlAux .= " and PessoaEndereco.Bairro like '%$filtro_consultar_por%'";	
				}
				break;
			case 4:
				$order_by .= ", Estado.NomeEstado, Cidade.NomeCidade, PessoaEndereco.Bairro, PessoaEndereco.Endereco";
				
				if($filtro_consultar_por != ""){
					$filtro_url .= "&ConsultarPor=".$filtro_consultar_por;
					$filtro_sqlAux .= " and PessoaEndereco.Endereco like '%$filtro_consultar_por%'";
				}
				break;
			case 5:
				$order_by .= ", Pessoa.Nome, Pessoa.RazaoSocial";
				
				if($filtro_consultar_por != ""){
					$filtro_url .= "&ConsultarPor=".$filtro_consultar_por;
					$filtro_sqlAux .= " and Pessoa.Nome like '%$filtro_consultar_por%' and Pessoa.RazaoSocial like '%$filtro_consultar_por%'";
				}
				break;
			case 6:
				$order_by .= ", Pessoa.IdPessoa";
				
				if($filtro_consultar_por != ""){
					$filtro_url .= "&ConsultarPor=".$filtro_consultar_por;
					$filtro_sqlAux .= " and Pessoa.IdPessoa = '$filtro_consultar_por'";
				}
				break;
			case 7:
				$order_by .= ", Pessoa.CPF_CNPJ";
				
				if($filtro_consultar_por != ""){
					$filtro_url .= "&ConsultarPor=".$filtro_consultar_por;
					$filtro_sqlAux .= " and Pessoa.CPF_CNPJ like '%$filtro_consultar_por%'";
				}
				break;	
			case 8:
				$order_by .= ", Pessoa.Email";
				
				if($filtro_consultar_por != ""){
					$filtro_url .= "&ConsultarPor=".$filtro_consultar_por;
					$filtro_sqlAux .= " and Pessoa.Email like '%$filtro_consultar_por%'";
				}
				break;
		}			
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
	
	if($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_id_servico'";
	}
	if($filtro_id_pessoa!=""){
		$filtro_url .= "&IdPessoa=".$filtro_id_pessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_id_pessoa'";
	}
	
	if($filtro_soma!=""){
		$filtro_url .= "&Soma=".$filtro_soma;
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
	}*/
	$sqlAgente="select 
					Restringir
				from
					AgenteAutorizado 
				where 
					AgenteAutorizado.IdLoja = $local_IdLoja and
					AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_detalhe_xsl.php$filtro_url\"?>";
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
					distinct				    
				    Contrato.IdPessoa,
					Contrato.IdPessoaEndereco,
					Pessoa.Nome, 
					Pessoa.RazaoSocial, 	
					Pessoa.RG_IE,
					Pessoa.CPF_CNPJ, 
					Pessoa.Telefone1, 
					Pessoa.Telefone2, 
					Pessoa.Telefone3,
					Pessoa.Celular,
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
				    Servico,
				    Contrato,
				    Pessoa,
				    PessoaEndereco,
				    Pais,
				    Estado,
				    Cidade,
				    LocalCobranca $filtro_from $sqlAux
				where				    
				    Contrato.IdLoja = $local_IdLoja and
				  	Contrato.IdLoja = Servico.IdLoja and
				    Contrato.IdLoja = LocalCobranca.IdLoja and
				    Contrato.IdServico = Servico.IdServico and
				    Contrato.IdPessoa = Pessoa.IdPessoa and
				    Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				    Pessoa.IdPessoa = PessoaEndereco.IdPessoa and    
				    Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
				    PessoaEndereco.IdPais = Pais.IdPais and
				    PessoaEndereco.IdPais = Estado.IdPais and
				    PessoaEndereco.IdPais = Cidade.IdPais and
				    PessoaEndereco.IdEstado = Estado.IdEstado and
				    PessoaEndereco.IdEstado = Cidade.IdEstado and
				    PessoaEndereco.IdCidade = Cidade.IdCidade    
					$filtro_sql
					$filtro_sqlAux
				order by
					Contrato.IdContrato desc, Pais.NomePais 
					$order_by					
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoa] == 2){	
			$lin[Nome]		=	$lin[Nome];
			$lin[CampoNome]	=	'Nome';
		}else{
			$lin[Nome]		=	$lin[RazaoSocial];
			$lin[CampoNome]	=	'Razão Social';
		}
		
		$lin[Endereco]		=	$lin[Endereco].", ".$lin[Numero];
		
		if($lin[Complemento]!= '' )	$lin[Endereco] .=	" - ".$lin[Complemento];
		if($lin[Bairro]!= '' )	 	$lin[Endereco] .=	" - Bairro: ".$lin[Bairro];
		
		$lin[Endereco]	.=	" - ".$lin[NomeCidade]."-".$lin[SiglaEstado]." - Cep: ".$lin[CEP];
		
		if($lin[TipoPessoa] == 1){
			$lin[cpCNPJ]	=	'CNPJ';
			$lin[cpIE]		=	'IE';
		}
		
		if($lin[TipoPessoa] == 2){
			$lin[cpCNPJ]	=	'CPF';
			$lin[cpIE]		=	'RG';
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
		echo 	"<ComplementoTelefone><![CDATA[$lin[ComplementoTelefone]]]></ComplementoTelefone>";
		echo 	"<Fax><![CDATA[$lin[Fax]]]></Fax>";
		echo 	"<Email><![CDATA[$lin[Email]]]></Email>";
		echo 	"<cpCNPJ><![CDATA[$lin[cpCNPJ]]]></cpCNPJ>";
		echo 	"<cpIE><![CDATA[$lin[cpIE]]]></cpIE>";	
	
		$sql	=	"
				select
				    Contrato.IdContrato,
				    Contrato.IdServico,
				    Servico.DescricaoServico,
				    Contrato.IdPessoa,				    
				    LocalCobranca.AbreviacaoNomeLocalCobranca,
				    Contrato.IdStatus,
				    Contrato.VarStatus,
					Contrato.DataCriacao,
					Contrato.LoginCriacao,
					Contrato.IdPessoaEndereco,
					Contrato.IdPessoaEnderecoCobranca			
				from
				    Contrato,
				    Servico,
				    Pessoa,
				    LocalCobranca $filtro_from $sqlAux
				where
				    Contrato.IdLoja = $local_IdLoja and
				    Contrato.IdLoja = Servico.IdLoja and
				    Contrato.IdLoja = LocalCobranca.IdLoja and
				    Contrato.IdServico = Servico.IdServico and
				    Contrato.IdPessoa = Pessoa.IdPessoa and
					Contrato.IdPessoa = $lin[IdPessoa] and				   
				    Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca $filtro_sql
				$Limit";
		$res3	=	mysql_query($sql,$con);
		while($lin3	=	mysql_fetch_array($res3)){
			$sqlPessoaEndereco = "
				select
					PessoaEndereco.IdPessoaEndereco,
					PessoaEndereco.Endereco,
					PessoaEndereco.Numero,
					PessoaEndereco.Bairro,
					PessoaEndereco.Complemento,
					PessoaEndereco.CEP,
					Cidade.NomeCidade,
					Estado.SiglaEstado,
					PessoaEndereco.NomeResponsavelEndereco,
					PessoaEndereco.Fax,
					PessoaEndereco.Telefone,
					PessoaEndereco.Celular,
					PessoaEndereco.EmailEndereco
				from
					PessoaEndereco,
					Cidade,
					Estado
				where
					PessoaEndereco.IdPessoa = $lin3[IdPessoa] and
					Estado.IdPais = PessoaEndereco.IdPais and
					Cidade.IdPais = PessoaEndereco.IdPais and
					Estado.IdEstado = PessoaEndereco.IdEstado and
					Cidade.IdCidade	= PessoaEndereco.IdCidade and
					Cidade.IdEstado	= Estado.IdEstado";
			$resPessoaEndereco = mysql_query($sqlPessoaEndereco,$con);
			while($linPessoaEndereco = mysql_fetch_array($resPessoaEndereco)){
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Endereco]					= $linPessoaEndereco[Endereco];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Numero]					= $linPessoaEndereco[Numero];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Bairro]					= $linPessoaEndereco[Bairro];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Complemento]				= $linPessoaEndereco[Complemento];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][CEP]						= $linPessoaEndereco[CEP];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][NomeCidade]				= $linPessoaEndereco[NomeCidade];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][SiglaEstado]				= $linPessoaEndereco[SiglaEstado];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][NomeResponsavelEndereco]	= $linPessoaEndereco[NomeResponsavelEndereco];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Fax]						= $linPessoaEndereco[Fax];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Telefone]					= $linPessoaEndereco[Telefone];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Celular]					= $linPessoaEndereco[Celular];
				$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][EmailEndereco]			= $linPessoaEndereco[EmailEndereco];						
			}
			
			$lin3[StatusDesc]  = getParametroSistema(69,$lin3[IdStatus]);
			$lin3[DataCriacao] = dataConv($lin3[DataCriacao],'Y-m-d','d/m/Y'); 
			
			echo "<contrato>";
			echo 	"<IdContrato>$lin3[IdContrato]</IdContrato>";	
			echo 	"<IdServico>$lin3[IdServico]</IdServico>";	
			echo 	"<DescricaoServico><![CDATA[$lin3[DescricaoServico]]]></DescricaoServico>";
			echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin3[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
			echo 	"<Status><![CDATA[$lin3[Status]]]></Status>";
			echo 	"<StatusDesc><![CDATA[$lin3[StatusDesc]]]></StatusDesc>";
			echo 	"<IdStatus><![CDATA[$lin3[IdStatus]]]></IdStatus>";
			echo 	"<DataCriacao><![CDATA[$lin3[DataCriacao]]]></DataCriacao>";
			echo 	"<LoginCriacao><![CDATA[$lin3[LoginCriacao]]]></LoginCriacao>";
			
			$EnderecoPrincipal 					= $PessoaEndereco[$lin3[IdPessoaEndereco]][Endereco];
			$NumeroPrincipal					= $PessoaEndereco[$lin3[IdPessoaEndereco]][Numero];
			$BairroPrincipal					= $PessoaEndereco[$lin3[IdPessoaEndereco]][Bairro];
			$ComplementoPrincipal				= $PessoaEndereco[$lin3[IdPessoaEndereco]][Complemento];
			$CEPPrincipal						= $PessoaEndereco[$lin3[IdPessoaEndereco]][CEP];
			$NomeCidadePrincipal				= $PessoaEndereco[$lin3[IdPessoaEndereco]][NomeCidade];
			$SiglaEstadoPrincipal				= $PessoaEndereco[$lin3[IdPessoaEndereco]][SiglaEstado];
			$NomeResponsavelEnderecoPrincipal 	= $PessoaEndereco[$lin3[IdPessoaEndereco]][NomeResponsavelEndereco];
			$FaxPrincipal						= $PessoaEndereco[$lin3[IdPessoaEndereco]][Fax];
			$TelefonePrincipal					= $PessoaEndereco[$lin3[IdPessoaEndereco]][Telefone];
			$CelularPrincipal					= $PessoaEndereco[$lin3[IdPessoaEndereco]][Principal];
			$EmailEnderecoPrincipal				= $PessoaEndereco[$lin3[IdPessoaEndereco]][EmailEndereco];
			
			echo	"<IdPessoaEndereco>$lin3[IdPessoaEndereco]</IdPessoaEndereco>";		
			echo 	"<EnderecoPrincipal><![CDATA[$EnderecoPrincipal]]></EnderecoPrincipal>";		
			echo 	"<NumeroPrincipal><![CDATA[$NumeroPrincipal]]></NumeroPrincipal>";
			echo 	"<BairroPrincipal><![CDATA[$BairroPrincipal]]></BairroPrincipal>";
			echo 	"<ComplementoPrincipal><![CDATA[$ComplementoPrincipal]]></ComplementoPrincipal>";
			echo 	"<CEPPrincipal><![CDATA[$CEPPrincipal]]></CEPPrincipal>";
			echo 	"<NomeCidadePrincipal><![CDATA[$NomeCidadePrincipal]]></NomeCidadePrincipal>";			
			echo 	"<SiglaEstadoPrincipal><![CDATA[$SiglaEstadoPrincipal]]></SiglaEstadoPrincipal>";
			echo 	"<NomeResponsavelEnderecoPrincipal><![CDATA[$NomeResponsavelEnderecoPrincipal]]></NomeResponsavelEnderecoPrincipal>";
			echo 	"<FaxPrincipal><![CDATA[$FaxPrincipal]]></FaxPrincipal>";
			echo 	"<TelefonePrincipal><![CDATA[$TelefonePrincipal]]></TelefonePrincipal>";
			echo 	"<CelularPrincipal><![CDATA[$CelularPrincipal]]></CelularPrincipal>";
			echo 	"<EmailEnderecoPrincipal><![CDATA[$EmailEnderecoPrincipal]]></EmailEnderecoPrincipal>";
			
			$EnderecoCobranca 				= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][Endereco];
			$NumeroCobranca					= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][Numero];
			$BairroCobranca					= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][Bairro];
			$ComplementoCobranca			= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][Complemento];
			$CEPCobranca					= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][CEP];
			$NomeCobranca					= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][NomeCidade];
			$SiglaCobranca					= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][SiglaEstado];
			$NomeCobranca 					= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][NomeResponsavelEndereco];
			$FaxCobranca					= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][Fax];			
			$TelefoneCobranca				= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][Telefone];
			$CelularCobranca				= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][Celular];
			$EmailEnderecoCobranca			= $PessoaEndereco[$lin3[IdPessoaEnderecoCobranca]][EmailEndereco];			
			
			echo	"<IdPessoaEnderecoCobranca>$lin3[IdPessoaEnderecoCobranca]</IdPessoaEnderecoCobranca>";			
			echo	"<EnderecoCobranca><![CDATA[$EnderecoCobranca]]></EnderecoCobranca>";
			echo 	"<NumeroCobranca><![CDATA[$NumeroCobranca]]></NumeroCobranca>";
			echo 	"<BairroCobranca><![CDATA[$BairroCobranca]]></BairroCobranca>";
			echo 	"<ComplementoCobranca><![CDATA[$ComplementoCobranca]]></ComplementoCobranca>";
			echo 	"<CEPCobranca><![CDATA[$CEPCobranca]]></CEPCobranca>";
			echo 	"<NomeCidadeCobranca><![CDATA[$NomeCobranca]]></NomeCidadeCobranca>";
			echo 	"<SiglaEstadoCobranca><![CDATA[$SiglaCobranca]]></SiglaEstadoCobranca>";
			echo 	"<NomeResponsavelEnderecoCobranca><![CDATA[$NomeCobranca]]></NomeResponsavelEnderecoCobranca>";
			echo 	"<FaxCobranca><![CDATA[$FaxCobranca]]></FaxCobranca>";
			echo 	"<TelefoneCobranca><![CDATA[$TelefoneCobranca]]></TelefoneCobranca>";
			echo 	"<CelularCobranca><![CDATA[$CelularCobranca]]></CelularCobranca>";
			echo 	"<EmailEnderecoCobranca><![CDATA[$EmailEnderecoCobranca]]></EmailEnderecoCobranca>";
			echo 	"<VisualizarContratoParametro><![CDATA[$filtro_contrato_parametro]]></VisualizarContratoParametro>";
			echo 	"<Color><![CDATA[$Color]]></Color>";
			echo 	"<Img><![CDATA[$Img]]></Img>";
			
			echo 	"<contratoParametro>";
			
			$sql =
				"select	
					Contrato.IdContrato,
					ServicoParametro.DescricaoParametroServico,
					ContratoParametro.Valor
				from	
					Contrato,
					ServicoParametro,
					ContratoParametro
				where
					Contrato.IdLoja = $local_IdLoja and
					Contrato.IdLoja = ServicoParametro.IdLoja and
					Contrato.IdLoja = ContratoParametro.IdLoja and
					Contrato.IdContrato = $lin3[IdContrato] and
					Contrato.IdContrato = ContratoParametro.IdContrato and	
					Contrato.IdServico = ServicoParametro.IdServico and
					ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico and 
					ServicoParametro.VisivelOS = 1";
			$res4	=	mysql_query($sql,$con);			
			while($lin4	=	mysql_fetch_array($res4)){
			echo 	"<parametro>";
			echo 		"<IdContrato>$lin4[IdContrato]</IdContrato>";
			echo 		"<DescricaoParametroServico><![CDATA[$lin4[DescricaoParametroServico]:]]></DescricaoParametroServico>";	
			echo 		"<Valor><![CDATA[$lin4[Valor]]]></Valor>";
			echo 	"</parametro>";	
			}
			
			echo 	"</contratoParametro>";	
			echo "</contrato>";	}	
		echo "</reg>";	
	}
	
	echo "</db>";
?>