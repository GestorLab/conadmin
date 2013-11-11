<?
	include("../files/conecta.php");

	$IdLoja					= $_GET['IdLoja'];
	$IdServicoModelo		= $_GET['IdServicoModelo'];
	$IdServicoConfigurar	= $_GET['IdServicoConfigurar'];

	$IdServicoConfigurarArray = explode(',',$IdServicoConfigurar);
		
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;	
	###########################################################

	// Passo 1 - Salvar as configurações de Tipo NF, Categoria Tributária e CFOP
	$sql = "select
				IdNotaFiscalTipo,
				IdCategoriaTributaria,
				CFOP
			from
				Servico
			where
				IdLoja = $IdLoja and
				IdServico = $IdServicoModelo";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$sql = "update Servico set 
				IdNotaFiscalTipo = '$lin[IdNotaFiscalTipo]', 
				IdCategoriaTributaria = '$lin[IdCategoriaTributaria]', 
				CFOP = '$lin[CFOP]' 
			where 
				IdLoja = '$IdLoja' and 
				IdServico in ($IdServicoConfigurar)";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	if($local_transaction[$tr_i] == false){ echo mysql_error()."<BR>"; }
	$tr_i++;	
	// FIM - Passo

	for($i=0; $i<count($IdServicoConfigurarArray); $i++){

		$IdServicoConfigurarArray[$i] = trim($IdServicoConfigurarArray[$i]);

		if($IdServicoConfigurarArray[$i] != $IdServicoModelo){

			// Passo 2 - Copiar e salvar aliquotas
			$sql = "select
						IdLoja,
						IdServico,
						IdPais,
						IdEstado,
						IdAliquotaTipo,
						Aliquota,
						FatorBaseCalculoAliquota
					from
						ServicoAliquota
					where
						IdLoja = $IdLoja and
						IdServico = $IdServicoModelo";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){

				if($lin[Aliquota] == ''){	$lin[Aliquota] = 'NULL';	}


				$sql = "insert into `ServicoAliquota` values ($lin[IdLoja], $IdServicoConfigurarArray[$i], $lin[IdPais], $lin[IdEstado], $lin[IdAliquotaTipo], $lin[Aliquota], $lin[FatorBaseCalculoAliquota])";
				
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				if($local_transaction[$tr_i] == false){ echo mysql_error()."<BR>"; }
				$tr_i++;	
			}
			// FIM - Passo	
		}
		
		// Passo 3 - Salvar os parametros do layout

		$sql = "insert into ServicoNotaFiscalLayoutParametro values ($IdLoja,$IdServicoConfigurarArray[$i],1,2,'0104');";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		if($local_transaction[$tr_i] == false){ echo mysql_error()."<BR>"; }
		$tr_i++;

		$sql = "insert into `ServicoNotaFiscalLayoutParametro` values ($IdLoja,$IdServicoConfigurarArray[$i],1,3,'');";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		if($local_transaction[$tr_i] == false){ echo mysql_error()."<BR>"; }
		$tr_i++;

		// FIM - Passo 3
	}
	############################################################

	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;				
		}
	}
	
	if($local_transaction == true){
		$sql = "COMMIT;";
	}else{
		$sql = "ROLLBACK;";
	}
	echo $sql;
	$sql = "ROLLBACK;";
	mysql_query($sql,$con);
?>