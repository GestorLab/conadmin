<?
	$localModulo		=	1;
	$localOperacao		=	97;
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
	$filtro_parametro_1			= url_string_xsl($_POST['filtro_parametro_1'],'url',false);
	$filtro_parametro_2			= url_string_xsl($_POST['filtro_parametro_2'],'url',false);
	$filtro_parametro_3			= url_string_xsl($_POST['filtro_parametro_3'],'url',false);
	$filtro_parametro_4			= url_string_xsl($_POST['filtro_parametro_4'],'url',false);
	$filtro_valor_parametro1	= url_string_xsl($_POST['filtro_valor_parametro1'],'url',false);
	$filtro_valor_parametro2	= url_string_xsl($_POST['filtro_valor_parametro2'],'url',false);
	$filtro_valor_parametro3	= url_string_xsl($_POST['filtro_valor_parametro3'],'url',false);
	$filtro_valor_parametro4	= url_string_xsl($_POST['filtro_valor_parametro4'],'url',false);
	$filtro_estado				= $_POST['filtro_estado'];
	$filtro_cidade				= $_POST['filtro_cidade'];
	$filtro_bairro				= url_string_xsl($_POST['filtro_bairro'],'url',false);
	$filtro_endereco			= url_string_xsl($_POST['filtro_endereco'],'url',false);
	$filtro_status				= $_POST['filtro_status'];
	$filtro_nome_servico		= url_string_xsl($_POST['filtro_nome_servico'],'url',false);
	$filtro_id_servico			= $_POST['filtro_id_servico'];
	$filtro_id_pessoa			= $_POST['filtro_id_pessoa'];
	$filtro_IdPessoa			= $_GET['IdPessoa'];
	$filtro_IdContrato			= $_GET['IdContrato'];
	$filtro_IdServico			= $_GET['IdServico'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_coluna_telefone		= $_POST['filtro_coluna_telefone'];
	
	$filtro_url	 = "";
	$filtro_sql	 = "";
	$filtro_sql2 = "";
	$filtro_sql3 = "";
	$filtro_sql4  = "";
	$filtro_from = "";
	
	LimitVisualizacao("listar");
	
	$filtro_cancelado				= $_SESSION["filtro_contrato_cancelado"];
	$filtro_QTDCaracterColunaPessoa	= $_SESSION["filtro_QTDCaracterColunaPessoa"];
	
	if($filtro_IdPessoa == ''){
		$filtro_IdPessoa	= $_POST['IdPessoa'];
	}
	
	if($filtro_IdServico == ''){
		$filtro_IdServico	= $_POST['IdServico'];
	}
	
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_IdPessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_IdPessoa'";
	}
	
	if($filtro_IdServico !=''){
		$filtro_url .= "&IdServico=".$filtro_IdServico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_IdServico'";
	}
	
	if($filtro_IdContrato!=''){
		$filtro_url .= "&IdContrato=".$filtro_IdContrato;
		$filtro_sql .= " and Contrato.IdContrato = '$filtro_IdContrato'";
	}
	
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
	
	if($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_id_servico'";
	}
	
	if($filtro_id_pessoa!=""){
		$filtro_url .= "&IdPessoa=".$filtro_id_pessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_id_pessoa'";
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
	
	if($filtro_nome_servico!=''){
		$filtro_url .= "&NomeServico=".$filtro_nome_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_nome_servico%'";
	}
	
	if($filtro_parametro_1!=""){			
		$filtro_url .= "&Parametro1=".$filtro_parametro_1;		
	}
	
	if($filtro_parametro_1!="" && $filtro_valor_parametro1!=""){
		$filtro_url .= "&ValorParametro1=".$filtro_valor_parametro1;
		$filtro_sql .= " and ContratoParametro.Valor like '%$filtro_valor_parametro1%'";
	}
	
	if($filtro_parametro_2!=""){
		$filtro_url .= "&Parametro2=".$filtro_parametro_2;
	}
	
	if($filtro_parametro_2!="" && $filtro_valor_parametro2!=""){
		$filtro_url .= "&ValorParametro2=".$filtro_valor_parametro2;
		$filtro_sql2 .= " and ContratoParametro.Valor like '%$filtro_valor_parametro2%'";
	}
	
	if($filtro_parametro_3!=""){
		$filtro_url .= "&Parametro3=".$filtro_parametro_3;
	}
	
	if($filtro_parametro_3!="" && $filtro_valor_parametro3!=""){
		$filtro_url .= "&ValorParametro3=".$filtro_valor_parametro3;
		$filtro_sql3 .= " and ContratoParametro.Valor like '%$filtro_valor_parametro3%'";
	}
	
	if($filtro_parametro_4!=""){
		$filtro_url .= "&Parametro4=".$filtro_parametro_4;
	}
	
	if($filtro_parametro_4!="" && $filtro_valor_parametro4!=""){
		$filtro_url .= "&ValorParametro4=".$filtro_valor_parametro4;
		$filtro_sql4 .= " and ContratoParametro.Valor like '%$filtro_valor_parametro4%'";
	}
	
	if($filtro_estado!=''){
		$filtro_url .= "&IdEstado=$filtro_estado";
		$sqlAux .= ",PessoaEndereco";
		$filtro_sql .=	" 	and PessoaEndereco.IdPessoa = Pessoa.IdPessoa
							and PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault 
							and PessoaEndereco.IdEstado = $filtro_estado";
	}
	
	if($filtro_cidade!=''){
		$filtro_url .= "&IdCidade=$filtro_cidade";
		$filtro_sql .=	" and PessoaEndereco.IdCidade = $filtro_cidade";
		
		$sql11	="	select 
						NomeCidade
					from
						Cidade 
					where
						Cidade.IdEstado = $filtro_estado and
						Cidade.IdCidade = $filtro_cidade";
		$res11	=	mysql_query($sql11,$con);
		$lin11	=	mysql_fetch_array($res11);
		
		$filtro_url .= "&NomeCidade=$lin11[NomeCidade]";
	}
	
	if($filtro_bairro!=''){
		$filtro_url .= "&Bairro=$filtro_bairro";
		
		if($filtro_estado == "" && $filtro_endereco == ""){
			$sqlAux .= ",PessoaEndereco";
			$filtro_sql .= "and PessoaEndereco.IdPessoa = Pessoa.IdPessoa";
		}
		$filtro_sql .=	" and PessoaEndereco.Bairro like '%$filtro_bairro%'";
	}
	
	if($filtro_endereco!=''){
		$filtro_url .= "&Endereco=$filtro_endereco";
		
		if($filtro_estado == "" && $filtro_bairro == ""){
			$sqlAux .= ",PessoaEndereco";
			$filtro_sql .= "and PessoaEndereco.IdPessoa = Pessoa.IdPessoa";
		}
		$filtro_sql .=	" and PessoaEndereco.Endereco like '%$filtro_endereco%'";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_parametro_xsl.php$filtro_url\"?>";
	echo "<db>";

	if($filtro != "s"){
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$i		=	0;
	if($filtro_parametro_1!=""){
		$sql	=	"select
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
					    substring(Servico.DescricaoServico,1,12) DescricaoServico,
					    Contrato.IdPessoa,				    
						Pessoa.TipoPessoa,
					    substring(Pessoa.Nome,1,$filtro_QTDCaracterColunaPessoa) Nome,
					    substring(Pessoa.RazaoSocial,1,$filtro_QTDCaracterColunaPessoa) RazaoSocial,
					    Contrato.DataInicio,
					    Contrato.DataTermino,
					    Contrato.IdStatus,
					    Contrato.VarStatus,
					    ServicoParametro.DescricaoParametroServico,
					    ContratoParametro.Valor,
						ServicoParametro.IdTipoTexto
					from
					    Contrato, 
						Servico,
					    Pessoa,
						PessoaEndereco,
						ServicoParametro, 
						ContratoParametro $sqlAux
					where
					    Contrato.IdLoja = $local_IdLoja and
					    Contrato.IdLoja = Servico.IdLoja and 
						PessoaEndereco.IdPessoa = Pessoa.IdPessoa and
						Contrato.IdLoja = ServicoParametro.IdLoja and
					    Contrato.IdServico = Servico.IdServico and
					    Contrato.IdPessoa = Pessoa.IdPessoa and 
					    ServicoParametro.IdServico = Servico.IdServico and 
						ServicoParametro.DescricaoParametroServico like '$filtro_parametro_1' and
						ContratoParametro.IdContrato = Contrato.IdContrato and 
						ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico $filtro_sql
					order by
						Contrato.IdContrato desc
					$Limit";
	}else{
		$sql	=	"select
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
					    substring(Servico.DescricaoServico,1,12) DescricaoServico,
					    Contrato.IdPessoa,
						Pessoa.TipoPessoa,
					    substring(Pessoa.Nome,1,$filtro_QTDCaracterColunaPessoa) Nome,
					    substring(Pessoa.RazaoSocial,1,$filtro_QTDCaracterColunaPessoa) RazaoSocial,
					    Contrato.DataInicio,
					    Contrato.DataTermino,
					    Contrato.IdStatus,
					    Contrato.VarStatus
					from
					    Contrato,
						Servico,
						PessoaEndereco,
					    Pessoa $sqlAux
					where
					    Contrato.IdLoja = $local_IdLoja and
					    Contrato.IdLoja = Servico.IdLoja and 
						PessoaEndereco.IdPessoa = Pessoa.IdPessoa and
					    Contrato.IdServico = Servico.IdServico and
					    Contrato.IdPessoa = Pessoa.IdPessoa $filtro_sql
					order by
						Contrato.IdContrato desc
					$Limit";
	}
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){

		$query	=	"true";
		
		$sql3 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		$sql9 = "select 
					Valor,
					ValorRepasseTerceiro,
					ValorDesconto,
					IdTipoDesconto 
				from 
					ContratoVigencia 
				where 
					DataInicio <= curdate() and 
					(
						DataTermino is Null or 
						DataTermino >= curdate()
					) and 
					IdLoja = $local_IdLoja and 
					IdContrato = $lin[IdContrato] 
				order by 
					DataInicio desc 
				limit 0,1";
		$res9 = @mysql_query($sql9, $con);
		$lin9 = @mysql_fetch_array($res9);
		
		if($lin9[IdTipoDesconto] == 1){
			$lin9[ValorFinal] = $lin9[Valor] - $lin9[ValorDesconto];
		} else{
			$lin9[ValorFinal] = $lin9[Valor];
		}
		
		$lin9[ValorFinalTemp] = number_format($lin9[ValorFinal], 2, ',', '');
		
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
		
		if($lin[VarStatus] != ''){
			switch($lin[IdStatus]){
				case '201':
					$lin3[Status]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin3[Status]);	
					break;
			}					
		}
		
		$IdStatus	=	substr($lin[IdStatus],0,1);
		
		$lin[Parametro1]	=	"";
		$lin[Parametro2]	=	"";
		$lin[Parametro3]	=	"";
		$lin[Parametro4]	=	"";

		$lin[ParametroOrdem1] = "";
		$lin[ParametroOrdem2] = "";
		$lin[ParametroOrdem3] = "";
		$lin[ParametroOrdem4] = "";

		$ValorAux			  = $lin[Valor];
	
		if($lin[IdTipoTexto] == 4){
			$IP = explode(".", $lin[Valor]);		
			for($j=1; $j<4; $j++){								
				$IP[$j] = str_pad(trim($IP[$j]), 3, "0", STR_PAD_LEFT);
			}
			$lin[Valor] = $IP[0].$IP[1].$IP[2].$IP[3];			
		}
		
		if($filtro_parametro_1!=""){
			$lin[ParametroOrdem1]	=	$lin[Valor];
			$lin[ParametroOrdem2]	=	"";
			$lin[ParametroOrdem3]	=	"";
			$lin[ParametroOrdem4]	=	"";
		}
		$lin[Valor] = $ValorAux;
		
		if($filtro_parametro_1!=""){
			$lin[Parametro1]	=	$lin[Valor];
			$lin[Parametro2]	=	"";
			$lin[Parametro3]	=	"";
			$lin[Parametro4]	=	"";
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
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}

		if($filtro_parametro_2!=""){
			$sql2	=	"select
						    ContratoParametro.Valor,
							ServicoParametro.IdTipoTexto
						from
						    Contrato, 
							Servico,
							ServicoParametro,
							ContratoParametro
						where
						    Contrato.IdLoja = $local_IdLoja and
						    Contrato.IdLoja = Servico.IdLoja and
						    Contrato.IdLoja = ServicoParametro.IdLoja and
						    Contrato.IdLoja = ContratoParametro.IdLoja and 
						    Contrato.IdContrato = $lin[IdContrato] and 
						    Contrato.IdServico	= $lin[IdServico] and
						    Contrato.IdServico = Servico.IdServico and 
							ServicoParametro.IdServico = Servico.IdServico and 
							ServicoParametro.DescricaoParametroServico like '$filtro_parametro_2' and
							ContratoParametro.IdContrato = Contrato.IdContrato and 
							ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico $filtro_sql2";	
			$res2	=	mysql_query($sql2,$con);
			
			if(mysql_num_rows($res2) == 0){
				$query	=	"false";
			}else{
				$lin2	=	mysql_fetch_array($res2);			
				$lin[Parametro2]	=	$lin2[Valor];

				if($lin2[IdTipoTexto] == 4){
					$IP = explode(".", $lin2[Valor]);		
					for($j=0; $j<4; $j++){								
						$IP[$j] = str_pad(trim($IP[$j]), 3, "0", STR_PAD_LEFT);
					}
					$lin[ParametroOrdem2] = (int) $IP[0].$IP[1].$IP[2].$IP[3]; 
				}else{
					$lin[ParametroOrdem2] = $lin2[Valor];
				}
			}			
		}
		
		if($filtro_parametro_3!=""){
			$sql4	=	"select
						    ContratoParametro.Valor,
							ServicoParametro.IdTipoTexto
						from
						    Contrato, 
							Servico,
							ServicoParametro,
							ContratoParametro
						where
						    Contrato.IdLoja = $local_IdLoja and
						    Contrato.IdLoja = Servico.IdLoja and
						    Contrato.IdLoja = ServicoParametro.IdLoja and
						    Contrato.IdLoja = ContratoParametro.IdLoja and 
						    Contrato.IdContrato = $lin[IdContrato] and 
						    Contrato.IdServico	= $lin[IdServico] and
						    Contrato.IdServico = Servico.IdServico and 
							ServicoParametro.IdServico = Servico.IdServico and 
							ServicoParametro.DescricaoParametroServico like '$filtro_parametro_3' and
							ContratoParametro.IdContrato = Contrato.IdContrato and 
							ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico $filtro_sql3";	
			$res4	=	mysql_query($sql4,$con);
			
			if(mysql_num_rows($res4) == 0){
				$query	=	"false";
			}else{
				$lin4	=	mysql_fetch_array($res4);
				$lin[Parametro3]	=	$lin4[Valor];

				if($lin4[IdTipoTexto] == 4){
					$IP = explode(".", $lin4[Valor]);		
					for($j=0; $j<4; $j++){								
						$IP[$j] = str_pad(trim($IP[$j]), 3, "0", STR_PAD_LEFT);
					}
					$lin[ParametroOrdem3] = (int) $IP[0].$IP[1].$IP[2].$IP[3]; 
				}else{
					$lin[ParametroOrdem3] = $lin4[Valor];
				}
			}
		}
		
		if($filtro_parametro_4!=""){
			$sql5	=	"select
						    ContratoParametro.Valor,
							ServicoParametro.IdTipoTexto
						from
						    Contrato, 
							Servico,
							ServicoParametro,
							ContratoParametro
						where
						    Contrato.IdLoja = $local_IdLoja and
						    Contrato.IdLoja = Servico.IdLoja and
						    Contrato.IdLoja = ServicoParametro.IdLoja and
						    Contrato.IdLoja = ContratoParametro.IdLoja and 
						    Contrato.IdContrato = $lin[IdContrato] and 
						    Contrato.IdServico	= $lin[IdServico] and
						    Contrato.IdServico = Servico.IdServico and 
							ServicoParametro.IdServico = Servico.IdServico and 
							ServicoParametro.DescricaoParametroServico like '$filtro_parametro_4' and
							ContratoParametro.IdContrato = Contrato.IdContrato and 
							ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico $filtro_sql4";	
			$res5	=	mysql_query($sql5,$con);
			
			if(mysql_num_rows($res5) == 0){
				$query	=	"false";
			}else{
				$lin5	=	mysql_fetch_array($res5);
				$lin[Parametro4]	=	$lin5[Valor];

				if($lin5[IdTipoTexto] == 4){
					$IP = explode(".", $lin5[Valor]);		
					for($j=0; $j<4; $j++){								
						$IP[$j] = str_pad(trim($IP[$j]), 3, "0", STR_PAD_LEFT);
					}
					$lin[ParametroOrdem4] = (int) $IP[0].$IP[1].$IP[2].$IP[3]; 
				}else{
					$lin[ParametroOrdem4] = $lin5[Valor];
				}
			}
		}

		if($query	==	"true"){	
			
			if($filtro_limit > 0 || $filtro_limit == ''){
				echo "<reg>";	
				echo 	"<IdContrato>$lin[IdContrato]</IdContrato>";	
				echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
				echo 	"<IdServico>$lin[IdServico]</IdServico>";	
				echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
				echo 	"<ValorFinal><![CDATA[$lin9[ValorFinal]]]></ValorFinal>";
				echo 	"<ValorFinalTemp><![CDATA[$lin9[ValorFinalTemp]]]></ValorFinalTemp>";
				echo 	"<Status><![CDATA[$lin3[Status]]]></Status>";
				echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				echo	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
				echo 	"<Parametro1><![CDATA[$lin[Parametro1]]]></Parametro1>";
				echo 	"<Parametro2><![CDATA[$lin[Parametro2]]]></Parametro2>";
				echo 	"<Parametro3><![CDATA[$lin[Parametro3]]]></Parametro3>";
				echo 	"<Parametro4><![CDATA[$lin[Parametro4]]]></Parametro4>";
				echo 	"<ParametroOrdem1><![CDATA[$lin[ParametroOrdem1]]]></ParametroOrdem1>";
				echo 	"<ParametroOrdem2><![CDATA[$lin[ParametroOrdem2]]]></ParametroOrdem2>";
				echo 	"<ParametroOrdem3><![CDATA[$lin[ParametroOrdem3]]]></ParametroOrdem3>";
				echo 	"<ParametroOrdem4><![CDATA[$lin[ParametroOrdem4]]]></ParametroOrdem4>";
				echo 	"<Color><![CDATA[$Color]]></Color>";
				echo 	"<Img><![CDATA[$Img]]></Img>";
				echo "</reg>";	
			}
			$i++;
			
			if($i >= $filtro_limit && $filtro_limit != ''){
				break;
			}
			
		}
	}
	
	echo "</db>";
?>
