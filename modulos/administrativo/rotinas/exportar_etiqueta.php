<?
	include ('../../../files/conecta.php');
	include ('../../../rotinas/verifica.php');
	include ('../../../files/funcoes.php');
	
	$local_IdLoja			= $_SESSION["IdLoja"];
	$IdProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
	$DuplicarEtiquetas		= 'N';
	$OrdenarPor				= "Nome";

	$where = "";

	if($IdProcessoFinanceiro!=''){
		$sqlProcessoFinanceiro = "select 
					 Pessoa.IdPessoa
				from 
					 ProcessoFinanceiro,
					 LancamentoFinanceiro, 
					 Contrato, 
					 Pessoa, 
					 Cidade, 
					 Estado 
				where 
					 LancamentoFinanceiro.IdLoja = $local_IdLoja and 
					 LancamentoFinanceiro.IdLoja = Contrato.IdLoja and
					 LancamentoFinanceiro.IdLoja = ProcessoFinanceiro.IdLoja and 
					 LancamentoFinanceiro.IdProcessoFinanceiro = $IdProcessoFinanceiro and 
					 LancamentoFinanceiro.IdProcessoFinanceiro = ProcessoFinanceiro.IdProcessoFinanceiro and 
					 LancamentoFinanceiro.IdContrato = Contrato.IdContrato and 
					 Contrato.IdPessoa = Pessoa.IdPessoa and 
					 Pessoa.IdPais = Cidade.IdPais and 
					 Cidade.IdPais = Estado.IdPais and 
					 Pessoa.IdEstado = Cidade.IdEstado and 
					 Cidade.IdEstado = Estado.IdEstado and 
					 Pessoa.IdCidade = Cidade.IdCidade and
					 Pessoa.Cob_FormaCorreio = 'S'";

		$where .= " and Pessoa.IdPessoa in ($sqlProcessoFinanceiro)";
	}

	if($DuplicarEtiquetas == 'N'){
		$groupBy = "group by Pessoa.IdPessoa";
	}

	if($OrdenarPor != ''){
		$orderBy = " order by Pessoa.RazaoSocial, Pessoa.Nome";
	}

	$i = 0;

	$sql	=	"select 
				   distinct Pessoa.Nome, 
				   Pessoa.NomeRepresentante, 
				   Pessoa.Endereco, 
				   Pessoa.Complemento, 
				   Pessoa.Numero, 
				   Pessoa.Bairro, 
				   Cidade.NomeCidade, 
				   Estado.SiglaEstado, 
				   Pessoa.CEP,							   
				   Pessoa.Cob_NomeResponsavel, 
				   Pessoa.Cob_Endereco, 
				   Pessoa.Cob_Complemento, 
				   Pessoa.Cob_Numero, 
				   Pessoa.Cob_Bairro, 
				   Pessoa.Cob_IdPais, 
				   Pessoa.Cob_IdEstado, 
				   Pessoa.Cob_IdCidade, 
				   Pessoa.Cob_CEP
			from 
				 LancamentoFinanceiro, 
				 Contrato, 
				 Pessoa, 
				 Cidade, 
				 Estado 
			where 
				  LancamentoFinanceiro.IdContrato = Contrato.IdContrato and 
				  Contrato.IdPessoa = Pessoa.IdPessoa and 
				  Pessoa.IdPais = Cidade.IdPais and 
				  Cidade.IdPais = Estado.IdPais and 
				  Pessoa.IdEstado = Cidade.IdEstado and 
				  Cidade.IdEstado = Estado.IdEstado and 
				  Pessoa.IdCidade = Cidade.IdCidade and
				  Pessoa.Cob_FormaCorreio = 'S'
				  $where
				  $groupBy
				  $orderBy";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[Cob_Endereco]!='' && $lin[Cob_IdPais] != '' && $lin[Cob_IdEstado] != '' && $lin[Cob_IdCidade]){
			$Dados[$i][NomeRepresentante]	=	$lin[Cob_NomeResponsavel];
			$Dados[$i][Nome]				=	$lin[Nome];
			$Dados[$i][Endereco]			=	$lin[Cob_Endereco];
			$Dados[$i][Complemento]		=	$lin[Cob_Complemento];
			$Dados[$i][Numero]				=	$lin[Cob_Numero];
			$Dados[$i][Bairro]				=	$lin[Cob_Bairro];
			$Dados[$i][CEP]				=	$lin[Cob_CEP];

			$sql = "select
						Cidade.NomeCidade, 
						Estado.SiglaEstado
					from
						Cidade,
						Estado
					where
						Cidade.IdPais=$lin[Cob_IdPais] and
						Cidade.IdPais = Estado.IdPais and
						Cidade.IdEstado=$lin[Cob_IdEstado] and
						Cidade.IdEstado = Estado.IdEstado and
						Cidade.IdCidade=$lin[Cob_IdCidade]";
			$res2	=	mysql_query($sql,$con);
			$lin2	=	mysql_fetch_array($res2);

			$Dados[$i][NomeCidade]			=	$lin2[NomeCidade];
			$Dados[$i][SiglaEstado]		=	$lin2[SiglaEstado];
		}else{		
			$Dados[$i][NomeRepresentante]	=	$lin[NomeRepresentante];
			$Dados[$i][Nome]				=	$lin[Nome];
			$Dados[$i][Endereco]			=	$lin[Endereco];
			$Dados[$i][Complemento]		=	$lin[Complemento];
			$Dados[$i][Numero]				=	$lin[Numero];
			$Dados[$i][Bairro]				=	$lin[Bairro];
			$Dados[$i][NomeCidade]			=	$lin[NomeCidade];
			$Dados[$i][SiglaEstado]		=	$lin[SiglaEstado];
			$Dados[$i][CEP]				=	$lin[CEP];
		}

		$i++;
	}
	###########################################
	# Concatena e armazena em uma string
	for($ii = 0; $ii<$i; $ii++){
		
		# Linha 1 
		# Razão Social ou Nome Cliente
		$Dados[$ii][Dados][0]	=	$Dados[$ii][Nome];
		
		# Linha 2
		# Representante
		if($Dados[$ii][NomeRepresentante] != $Dados[$ii][Nome]){
			$Dados[$ii][Dados][1]	=	$Dados[$ii][NomeRepresentante];
		}
		
		# Linha 3
		# Endereço
		$Dados[$ii][Dados][2]	=	$Dados[$ii][Endereco];
		if($Dados[$ii][Numero] != ''){
			$Dados[$ii][Dados][2] .= ", ".$Dados[$ii][Numero];
		}

		# Linha 4
		# Complemento e Bairro
		$Dados[$ii][Dados][3]	=	$Dados[$ii][Complemento];
		if($Dados[$ii][Dados][3] != ''){
			$Dados[$ii][Dados][3] .= " - ";
		}
		$Dados[$ii][Dados][3] .= $Dados[$ii][Bairro];
		
		# Linha 5
		# Cidade e Estado
		$Dados[$ii][Dados][4]	=	$Dados[$ii][NomeCidade]." - ".$Dados[$ii][SiglaEstado];
		
		# Linha 6
		# CEP
		if($Dados[$ii][CEP] != ''){
			$Dados[$ii][Dados][5]	=	"CEP: ".$Dados[$ii][CEP];
		}
		
		for($iii=0;$iii<count($Dados[$ii][Dados]);$iii++){
			if($Dados[$ii][Dados][$iii] != ''){
				$Dados[$ii][Dados][$iii]	= str_replace('"', "'",$Dados[$ii][Dados][$iii]);
				$Dados[$ii][Dados][$iii]	= str_replace(';', "",$Dados[$ii][Dados][$iii]);
				$Dados[$ii][Dados][$iii]	= str_replace('\n', "",$Dados[$ii][Dados][$iii]);
				$Dados[$ii][Dados][$iii]	= str_replace('^', "",$Dados[$ii][Dados][$iii]);
					
				$Dados[$ii][Dados][$iii]	= "\"".$Dados[$ii][Dados][$iii]."\"";
				$Dados[$ii][Dados][$iii]	= $Dados[$ii][Dados][$iii].";";
				$Dados[$ii][Dados][$iii]	= formatText($Dados[$ii][Dados][$iii],'MA');
			}
		}

		if($ii == 0){
			echo "\"L1\";\"L2\";\"L3\";\"L4\";\"L5\";\"L6\"^";
		}			

		for($iii=0;$iii<count($Dados[$ii][Dados]);$iii++){
			echo $Dados[$ii][Dados][$iii];
		}
		echo "^";
	}
?>
