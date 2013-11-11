<?php
	$localModulo		=	1;
	$localOperacao		=	15;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	// include the php-excel class
	require ("../../classes/php-excel/class-excel-xml.inc.php");
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Filtro_IdContaReceber	= $_GET['Filtro_IdContaReceber'];
	$local_IdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
	$local_Filtro_IdPessoa 			= $_GET['Filtro_IdPessoa'];
	$local_IdLocalCobranca 			= $_GET['IdLocalCobranca'];
	$local_EnderecoCobranca			= $_GET['EnderecoCobranca'];
	$local_FormatoSaida 			= $_GET['FormatoSaida'];
	
	$filtro_sql 	= "";
	$filtro_url 	= "";
	$filtro_from 	= "";	
	
	if($local_EnderecoCobranca == 1){
		$filtro_url		.=	" Pessoa.IdPessoa";
		$filtro_url		.=	",Pessoa.Nome";
		$filtro_url		.=	",Pessoa.Cob_Endereco as 'Endereço'";
		$filtro_url		.=	",Pessoa.Cob_Complemento as 'Complemento'";
		$filtro_url		.=	",Pessoa.Cob_Numero as 'Número'";
		$filtro_url		.=	",Pessoa.Cob_Bairro as 'Bairro'";
		$filtro_url		.=	",Pessoa.Cob_CEP as 'CEP'";
		$filtro_url		.=	",Cidade.NomeCidade as 'Cidade'";
		$filtro_url		.=	",Estado.SiglaEstado as 'Estado'";
	
		$filtro_from 	 =	"LEFT JOIN Pais ON (Pais.IdPais = Pessoa.Cob_IdPais) LEFT JOIN Estado ON (Pais.IdPais = Estado.IdPais and Estado.IdEstado = Pessoa.Cob_IdEstado) LEFT JOIN Cidade ON (Pais.IdPais = Cidade.IdPais and Cidade.IdCidade = Pessoa.Cob_IdCidade and Estado.IdEstado = Cidade.IdEstado)";	
	}else{
		$filtro_url		.=	" Pessoa.IdPessoa";
		$filtro_url		.=	",Pessoa.Nome";
		$filtro_url		.=	",Pessoa.Endereco as 'Endereço'";
		$filtro_url		.=	",Pessoa.Complemento";
		$filtro_url		.=	",Pessoa.Numero as 'Número'";
		$filtro_url		.=	",Pessoa.Bairro";
		$filtro_url		.=	",Pessoa.CEP";
		$filtro_url		.=	",Cidade.NomeCidade as 'Cidade'";
		$filtro_url		.=	",Estado.SiglaEstado as 'Estado'";
		
		$filtro_from  	 =	"LEFT JOIN Pais ON (Pais.IdPais = Pessoa.IdPais) LEFT JOIN Estado ON (Pais.IdPais = Estado.IdPais and Estado.IdEstado = Pessoa.IdEstado) LEFT JOIN Cidade ON (Pais.IdPais = Cidade.IdPais and Cidade.IdCidade = Pessoa.IdCidade and Estado.IdEstado = Cidade.IdEstado)";
	}
	
	$ii		= 	0;
	
	$qtd		 =  9; 
	$doc[$ii][1] = 'Nome';
	$doc[$ii][2] = 'Endereço';
	$doc[$ii][3] = 'Complemento';
	$doc[$ii][4] = 'Número';
	$doc[$ii][5] = 'Bairro';
	$doc[$ii][6] = 'CEP';
	$doc[$ii][7] = 'Cidade';
	$doc[$ii][8] = 'Estado';
	
	$ii++;
	
	if($local_Filtro_IdPessoa!=""){
		$sql	=	"select $filtro_url from Pessoa $filtro_from where IdPessoa in (".$local_Filtro_IdPessoa.")";
		$res	=	mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			if($local_EnderecoCobranca == 1 && $lin['Endereço'] == ""){
				$filtro_url		 =	"";
				$filtro_from	 =  "";
				
				$filtro_url		.=	" Pessoa.IdPessoa";
				$filtro_url		.=	",Pessoa.Nome";
				$filtro_url		.=	",Pessoa.Endereco as 'Endereço'";
				$filtro_url		.=	",Pessoa.Complemento";
				$filtro_url		.=	",Pessoa.Numero as 'Número'";
				$filtro_url		.=	",Pessoa.Bairro";
				$filtro_url		.=	",Pessoa.CEP";
				$filtro_url		.=	",Cidade.NomeCidade as 'Cidade'";
				$filtro_url		.=	",Estado.SiglaEstado as 'Estado'";
				
				$filtro_from  	 =	"LEFT JOIN Pais ON (Pais.IdPais = Pessoa.IdPais) LEFT JOIN Estado ON (Pais.IdPais = Estado.IdPais and Estado.IdEstado = Pessoa.IdEstado) LEFT JOIN Cidade ON (Pais.IdPais = Cidade.IdPais and Cidade.IdCidade = Pessoa.IdCidade and Estado.IdEstado = Cidade.IdEstado)";
				
				$sql2	=	"select $filtro_url from Pessoa $filtro_from where IdPessoa = $lin[IdPessoa]";
				$res2	=	mysql_query($sql2,$con);
				$lin2	 =  mysql_fetch_array($res2);
				
				for($i = 1; $i < $qtd; $i++){
					$doc[$ii][$i] = $lin2[@mysql_field_name($res2,$i)];
				}
			}else{
				for($i = 1; $i < $qtd; $i++){
					$doc[$ii][$i] = $lin[@mysql_field_name($res,$i)];
				}
			}
			$ii++;
		}
	}
	
	if($local_Filtro_IdContaReceber!=""){
		$sql	=	"select $filtro_url from ContaReceberPAV,Pessoa $filtro_from where ContaReceberPAV.IdPessoa = Pessoa.IdPessoa and ContaReceberPAV.IdContaReceber in (".$local_Filtro_IdContaReceber.")";
		$res	=	mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			if($local_EnderecoCobranca == 1 && $lin['Endereço'] == ""){
				$filtro_url		 =	"";
				$filtro_from	 =  "";
				
				$filtro_url		.=	" Pessoa.IdPessoa";
				$filtro_url		.=	",Pessoa.Nome";
				$filtro_url		.=	",Pessoa.Endereco as 'Endereço'";
				$filtro_url		.=	",Pessoa.Complemento";
				$filtro_url		.=	",Pessoa.Numero as 'Número'";
				$filtro_url		.=	",Pessoa.Bairro";
				$filtro_url		.=	",Pessoa.CEP";
				$filtro_url		.=	",Cidade.NomeCidade as 'Cidade'";
				$filtro_url		.=	",Estado.SiglaEstado as 'Estado'";
				
				$filtro_from  	 =	"LEFT JOIN Pais ON (Pais.IdPais = Pessoa.IdPais) LEFT JOIN Estado ON (Pais.IdPais = Estado.IdPais and Estado.IdEstado = Pessoa.IdEstado) LEFT JOIN Cidade ON (Pais.IdPais = Cidade.IdPais and Cidade.IdCidade = Pessoa.IdCidade and Estado.IdEstado = Cidade.IdEstado)";
				
				$sql2	=	"select $filtro_url from ContaReceberPAV,Pessoa $filtro_from where ContaReceberPAV.IdPessoa = Pessoa.IdPessoa and Pessoa.IdPessoa = $lin[IdPessoa]";
				$res2	=	mysql_query($sql2,$con);
				$lin2	 =  mysql_fetch_array($res2);
				
				for($i = 1; $i < $qtd; $i++){
					$doc[$ii][$i] = $lin2[@mysql_field_name($res2,$i)];
				}
			}else{
				for($i = 1; $i < $qtd; $i++){
					$doc[$ii][$i] = $lin[@mysql_field_name($res,$i)];
				}
			}
			$ii++;
		}
		
	}
	if($local_IdProcessoFinanceiro!=""){
		$sql	=	"select $filtro_url from ContaReceberPAV,Pessoa $filtro_from where ContaReceberPAV.IdPessoa = Pessoa.IdPessoa and ContaReceberPAV.IdProcessoFinanceiro = ".$local_IdProcessoFinanceiro."";
		$res	=	mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			if($local_EnderecoCobranca == 1 && $lin['Endereço'] == ""){
				$filtro_url		 =	"";
				$filtro_from	 =  "";
				
				$filtro_url		.=	" Pessoa.IdPessoa";
				$filtro_url		.=	",Pessoa.Nome";
				$filtro_url		.=	",Pessoa.Endereco as 'Endereço'";
				$filtro_url		.=	",Pessoa.Complemento";
				$filtro_url		.=	",Pessoa.Numero as 'Número'";
				$filtro_url		.=	",Pessoa.Bairro";
				$filtro_url		.=	",Pessoa.CEP";
				$filtro_url		.=	",Cidade.NomeCidade as 'Cidade'";
				$filtro_url		.=	",Estado.SiglaEstado as 'Estado'";
				
				$filtro_from  	 =	"LEFT JOIN Pais ON (Pais.IdPais = Pessoa.IdPais) LEFT JOIN Estado ON (Pais.IdPais = Estado.IdPais and Estado.IdEstado = Pessoa.IdEstado) LEFT JOIN Cidade ON (Pais.IdPais = Cidade.IdPais and Cidade.IdCidade = Pessoa.IdCidade and Estado.IdEstado = Cidade.IdEstado)";
				
				$sql2	=	"select $filtro_url from ContaReceberPAV,Pessoa $filtro_from where ContaReceberPAV.IdPessoa = Pessoa.IdPessoa and Pessoa.IdPessoa = $lin[IdPessoa]";
				$res2	=	mysql_query($sql2,$con);
				$lin2	 =  mysql_fetch_array($res2);
				
				for($i = 1; $i < $qtd; $i++){
					$doc[$ii][$i] = $lin2[@mysql_field_name($res2,$i)];
				}
			}else{
				for($i = 1; $i < $qtd; $i++){
					$doc[$ii][$i] = $lin[@mysql_field_name($res,$i)];
				}
			}
			$ii++;
		}
	}
	if($local_IdLocalCobranca!=""){
		$sql	=	"select $filtro_url from ContaReceberPAV,Pessoa $filtro_from where ContaReceberPAV.IdPessoa = Pessoa.IdPessoa and ContaReceberPAV.IdLocalCobranca = ".$local_IdLocalCobranca."";
		$res	=	mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			if($local_EnderecoCobranca == 1 && $lin['Endereço'] == ""){
				$filtro_url		 =	"";
				$filtro_from	 =  "";
				
				$filtro_url		.=	" Pessoa.IdPessoa";
				$filtro_url		.=	",Pessoa.Nome";
				$filtro_url		.=	",Pessoa.Endereco as 'Endereço'";
				$filtro_url		.=	",Pessoa.Complemento";
				$filtro_url		.=	",Pessoa.Numero as 'Número'";
				$filtro_url		.=	",Pessoa.Bairro";
				$filtro_url		.=	",Pessoa.CEP";
				$filtro_url		.=	",Cidade.NomeCidade as 'Cidade'";
				$filtro_url		.=	",Estado.SiglaEstado as 'Estado'";
				
				$filtro_from  	 =	"LEFT JOIN Pais ON (Pais.IdPais = Pessoa.IdPais) LEFT JOIN Estado ON (Pais.IdPais = Estado.IdPais and Estado.IdEstado = Pessoa.IdEstado) LEFT JOIN Cidade ON (Pais.IdPais = Cidade.IdPais and Cidade.IdCidade = Pessoa.IdCidade and Estado.IdEstado = Cidade.IdEstado)";
				
				$sql2	=	"select $filtro_url from ContaReceberPAV,Pessoa $filtro_from where ContaReceberPAV.IdPessoa = Pessoa.IdPessoa and Pessoa.IdPessoa = $lin[IdPessoa]";
				$res2	=	mysql_query($sql2,$con);
				$lin2	 =  mysql_fetch_array($res2);
				
				for($i = 1; $i < $qtd; $i++){
					$doc[$ii][$i] = $lin2[@mysql_field_name($res2,$i)];
				}
			}else{
				for($i = 1; $i < $qtd; $i++){
					$doc[$ii][$i] = $lin[@mysql_field_name($res,$i)];
				}
			}
			$ii++;
		}
	}
	
	// generate excel file
	$xls = new Excel_XML;
	$xls->addArray ($doc);
	$xls->generateXML ("etiqueta");
?>


