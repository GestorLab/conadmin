<?
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');	
	include('../../rotinas/verifica.php');
	include('../funcoes.php');	
	
	$local_Login						=	$_SESSION['LoginCDA'];
	$local_IdPessoa						=	$_SESSION['IdPessoaCDA'];
	$local_ContaReceber					=	$_POST['ContaReceber'];
	$local_DataVencimento				=	$_POST['DataVencimento'];
	$local_Vencimento					=	dataConv($_POST['Vencimento'],'d/m/Y','Y-m-d');
	$local_IdParametroSistema			=	$_POST['IdParametroSistema'];
	$local_ValorContaReceber			= 	str_replace(",", ".", $_POST['ValorContaReceber']);
	$local_ValorMulta					= 	str_replace(",", ".", $_POST['ValorMulta']);
	$local_ValorDesconto				= 	str_replace(",", ".", $_POST['ValorDesconto']);
	$local_ValorJuros					= 	str_replace(",", ".", $_POST['ValorJuros']);
	$local_ValorTaxaReImpressaoBoleto	=	str_replace(",", ".", $_POST['ValorTaxaReImpressaoBoleto']);
	$local_ValorFinal					=	$_POST['ValorFinal'];
			
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	
	$tr_i = 0;	
	
	$sqlObs	=	"select 
						IdLoja,
						IdContaReceber,
						Obs, 
						ValorLancamento 
				from 
					ContaReceber 
				where 
					MD5 = '$local_ContaReceber'";
	$resObs	=	mysql_query($sqlObs,$con);
	$linObs	=	mysql_fetch_array($resObs);

	$local_IdLoja			=	$linObs[IdLoja];
	$local_IdContaReceber	=	$linObs[IdContaReceber];
	
	$linObs[ValorLancamento] = str_replace(".", ",", $linObs[ValorLancamento]);
	$temp	=	dataConv($local_DataVencimento,'Y-m-d','d/m/Y'); // Data do vencimento anterior
	$temp2	=	dataConv($local_Vencimento,'Y-m-d','d/m/Y');
		
	$local_Obs	=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração da Data Vencimento [$temp > $temp2]\n";
	$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Valor (R$) Atualizado de: [R$ $linObs[ValorLancamento] > R$ $local_ValorFinal]";
		
	if($linObs[Obs]!="")	$local_Obs	.= "\n".$linObs[Obs];

	$sql	=	"UPDATE ContaReceber SET Obs = '".$local_Obs."'	WHERE IdLoja = $local_IdLoja and IdContaReceber = $local_IdContaReceber";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
	
	$DecontoAConceber = getCodigoInternoCDA(3,163);

	if($local_ValorDesconto == "") $local_ValorDesconto = '0.00'; 
	
	$sql	=	"
			INSERT INTO ContaReceberVencimento SET 
				IdLoja						= $local_IdLoja,
				IdContaReceber				= $local_IdContaReceber,
				DataVencimento				= '$local_Vencimento',
				ValorContaReceber			= '$local_ValorContaReceber',
				ValorMulta					= '$local_ValorMulta',
				ValorDesconto				= '$local_ValorDesconto',
				ValorJuros					= '$local_ValorJuros',
				ValorTaxaReImpressaoBoleto	= '$local_ValorTaxaReImpressaoBoleto',
				ValorOutrasDespesas			= '0.00',
				ManterDescontoAConceber		= '$DecontoAConceber',
				DataCriacao					= (concat(curdate(),' ',curtime())),
				LoginCriacao				= '$local_Login';";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;

	$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $local_IdContaReceber, 9, $local_Login);
	$tr_i++;
	
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;				
		}
	}
	
	if($local_transaction == true){
		$sql = "COMMIT;";
		$local_header = "../../menu.php?ctt=listar_segunda_via_boleto.php&IdPessoa=$local_IdPessoa&IdParametroSistema=$local_IdParametroSistema";
	}else{
		$sql = "ROLLBACK;";
		$local_header = "../../menu.php?ctt=atualizar_vencimento.php&IdContaReceber=$local_IdContaReceber&Erro=34&IdParametroSistema=$local_IdParametroSistema";
	}
	mysql_query($sql,$con);
	
	header("Location: $local_header");
?>