<?
	$localModulo		=	1;
	$localOperacao		=	45;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_IdPessoaLogin			= $_SESSION['IdPessoa'];
	$Erro							= $_GET['Erro'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_data					= $_POST['filtro_data'];
	$filtro_status					= $_POST['filtro_status'];
	$filtro_status_parcela			= $_POST['filtro_status_parcela'];
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_conta_receber			= $_POST['filtro_conta_receber'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	$filtro_cancelado				= $_SESSION["filtro_carne_cancelado"];	
	$filtro_quitado					= $_SESSION["filtro_carne_quitado"];
	$filtro_imprimir_conta_receber 	= $_SESSION["filtro_imprimir_conta_receber"];
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	
	LimitVisualizacao("listar");
	
	if($Erro != "")
		$filtro_url		.= "&Erro=$Erro";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
	
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaReceberDados.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_data!=""){
		$filtro_url  .= "&DataLancamento=".$filtro_data;
		$filtro_data  = dataConv($filtro_data,'d/m/Y','Y-m-d'); 	
		$filtro_sql  .= " and ContaReceberDados.DataLancamento = '".$filtro_data."'";
	}
	
	if($filtro_conta_receber!=""){
		$filtro_url  .= "&IdContaReceber=".$filtro_conta_receber;
		$filtro_sql .= " and ContaReceberDados.IdContaReceber = $filtro_conta_receber";
	}
	
	if($filtro_status!=""){
		$filtro_url  .= "&IdStatus=".$filtro_status;
	}
	
	if($filtro_status_parcela!=""){
		$filtro_url  .= "&IdStatusParcela=".$filtro_status_parcela;
		if($filtro_status_parcela == "200"){
			$filtro_sql  .= " and ContaReceberDados.DataVencimento < (concat(curdate(),' ',curtime()))";
		}else{
			$filtro_sql  .= " and ContaReceberDados.IdStatus = $filtro_status_parcela";
		}
	}
	
	if($filtro_imprimir_conta_receber !=""){
		$filtro_url  .= "&ImprimirContaReceber=".$filtro_imprimir_conta_receber;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert', false);
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_carne_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select distinct
						IdCarne,
						Pessoa.TipoPessoa,
						substr(Pessoa.Nome,1,30) Nome,
						substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
						AbreviacaoNomeLocalCobranca,
						ContaReceberDados.IdContaReceber,
						DataLancamento,
						count(ContaReceberDados.IdContaReceber) QtdTitulo,
						sum(if(ContaReceberDados.IdStatus=2,+1,0)-0) QtdTituloQuitado,
						sum(if(ContaReceberDados.IdStatus=1,+1,0)-0) QtdTituloEmAberto,
						sum(if(ContaReceberDados.IdStatus=0,+1,0)-0) Cancelado,
						LocalCobranca.IdLocalCobrancaLayout
					from
						Pessoa,
						ContaReceberDados,
						LocalCobranca
					where
						ContaReceberDados.IdLoja = $local_IdLoja and
						ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
						ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
						ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
						ContaReceberDados.IdCarne is not null
						$filtro_sql
					group by
						ContaReceberDados.IdCarne
					order by
						ContaReceberDados.IdCarne desc, DataLancamento DESC $Limit";
						
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$resultado = true;
		if($filtro_status!=''){
			switch($filtro_status){
				case '0':
					if($lin[QtdTitulo] != $lin[Cancelado]){
						$resultado = false;
					}
					break;
				case '1':
					if($lin[QtdTitulo] == $lin[QtdTituloQuitado] || $lin[QtdTitulo] == $lin[Cancelado]){
						$resultado = false;
					}
					break;
				case '2':
					if($lin[QtdTitulo] != $lin[QtdTituloQuitado]){
						$resultado = false;
					}
					break;
			}
		} else{
			if($filtro_cancelado!=1 && ($lin[QtdTitulo] == $lin[Cancelado])){
				$resultado = false;
			}
			if($filtro_quitado!=1 && ($lin[QtdTitulo] == $lin[QtdTituloQuitado])){
				$resultado = false;
			}
		}
		
		if($resultado == true){
			$lin[DataLancamentoTemp] = dataConv($lin[DataLancamento],'Y-m-d','d/m/Y');
			$lin[DataLancamento]	 = dataConv($lin[DataLancamento],'Y-m-d','Ymd');

			if($lin[QtdTitulo] == $lin[Cancelado]){
				$lin[Color]		=	getParametroSistema(15,2);
				$lin[MsgLink]	=	'Cancelado';
				$lin[Link]		=	'#';
				$lin[ImgExc]	=	'../../img/estrutura_sistema/ico_del_c.gif';
				$lin[IdStatus]  =	0;
				$Target			=	"";	
			}else if($lin[QtdTitulo] == $lin[QtdTituloQuitado]){
				$lin[Color]		=	getParametroSistema(15,3);
				$lin[MsgLink]	=	'Quitado';
				$lin[Link]		=	'#';
				$lin[ImgExc]	=	'../../img/estrutura_sistema/ico_del_c.gif';
				$lin[IdStatus]  =	2;
				$Target			=	 "";
			}else{
				if($lin[QtdTitulo] != $lin[QtdTituloQuitado]){
					$lin[Color]		=	"";
					$lin[MsgLink]	=	'Imprimir';
					$lin[Link]		=	"local_cobranca/$lin[IdLocalCobrancaLayout]/pdf_all.php?IdLoja=$local_IdLoja&IdCarne=$lin[IdCarne]&IdStatus=$filtro_status_parcela&ImprimirContaReceber=$filtro_imprimir_conta_receber";
					$lin[IdStatus]  =	1;
					$Target			=	"_self";
				}
				$lin[ImgExc]	=	'../../img/estrutura_sistema/ico_del.gif';
			}

			if($lin[TipoPessoa]=='1'){
				$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];
			}
			
			$Tipo = '';
			$sql0 = "
				SELECT DISTINCT 
					IdLoja,
					IdContaReceber 
				FROM 
					ContaReceberDados 
				WHERE 
					IdLoja = $local_IdLoja AND
					IdCarne = $lin[IdCarne];";
			$res0 = mysql_query($sql0, $con);
			while($lin0 = mysql_fetch_array($res0)){
				$sql1 = "
					SELECT DISTINCT
						Tipo
					FROM
						Demonstrativo
					WHERE 
						IdLoja = $lin0[IdLoja] AND
						IdContaReceber = $lin0[IdContaReceber];";
				$res1 = mysql_query($sql1,$con);
				while($lin1 = mysql_fetch_array($res1)){
					if(!strstr($Tipo, $lin1[Tipo])){
						$Tipo .= $lin1[Tipo].'/';
					}
				}
			}
			
			echo "<reg>";	
			echo 	"<IdCarne><![CDATA[$lin[IdCarne]]]></IdCarne>";
			echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
			echo 	"<DataLancamentoTemp><![CDATA[$lin[DataLancamentoTemp]]]></DataLancamentoTemp>";
			echo 	"<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";	
			echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
			echo 	"<QtdTitulo><![CDATA[$lin[QtdTitulo]]]></QtdTitulo>";
			echo 	"<QtdTituloEmAberto><![CDATA[$lin[QtdTituloEmAberto]]]></QtdTituloEmAberto>";
			echo 	"<QtdTituloQuitado><![CDATA[$lin[QtdTituloQuitado]]]></QtdTituloQuitado>";
			echo 	"<Link><![CDATA[$lin[Link]]]></Link>";
			echo 	"<MsgLink><![CDATA[$lin[MsgLink]]]></MsgLink>";
			echo 	"<Color><![CDATA[$lin[Color]]]></Color>";
			echo 	"<ImgExc><![CDATA[$lin[ImgExc]]]></ImgExc>";
			echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			echo 	"<Target><![CDATA[$Target]]></Target>";
			
			$Tipo = explode('/', $Tipo);
			
			for ($i = 0; $i <= count($Tipo); $i++) {
				$separado = '';
				
				if ($i > 0) {
					$separado = '/';
				}
				
				echo "
					<Tipo>
						<IdCarne><![CDATA[$lin[IdCarne]]]></IdCarne>
						<Separado><![CDATA[$separado]]></Separado>
						<Valor><![CDATA[$Tipo[$i]]]></Valor>
					</Tipo>";
			}
			
			echo "</reg>";	
		}
	}
	
	echo "</db>";
?>
