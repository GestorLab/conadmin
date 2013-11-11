<?php
	$local_Modulo			= 1;
	$local_Operacao			= 143;
	$local_Suboperacao		= "V";
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_IdPessoaContrato		= $_GET['IdPessoaContrato'];
	$local_IdTipoPessoa			= $_GET['IdTipoPessoa'];
	$local_IdStatusContrato		= $_GET['IdStatusContrato'];
	$local_IdPaisEstadoCidade	= $_GET['IdPaisEstadoCidade'];
	$sql_From					= '';
	$sql_Where					= '';
	$temp						= '';
	
	if($local_IdStatusContrato != ''){
		$temp = " and Contrato.IdStatus in ($local_IdStatusContrato)";
	}
	
	if($local_IdPessoaContrato == 1){
		$sql_From = ", Contrato";
		$sql_Where .= " 
			and Pessoa.IdPessoa = Contrato.IdPessoa
			and Contrato.IdLoja = '$local_IdLoja'".$temp;
	} elseif($local_IdPessoaContrato == 2){
		$sql_Where .= " 
			and Pessoa.IdPessoa not in( select
											IdPessoa
										from 
											Contrato
										where 
											IdLoja = '$local_IdLoja')";
	} else{
		$sql_From = ",Contrato";
		$sql_Where .= "	and Contrato.IdLoja = '$local_IdLoja'
						".$temp." and
						Pessoa.IdPessoa = Contrato.IdPessoa";
	}
	
	if($local_IdTipoPessoa != ''){
		$sql_Where .= " and TipoPessoa = '$local_IdTipoPessoa'";
	}
	
	if($local_IdPaisEstadoCidade != ''){
		$IdPais		= '#';
		$IdEstado	= '#';
		$IdCidade	= '#';
		
		foreach(explode('^', $local_IdPaisEstadoCidade) as $IdPaisEstadoCidade){
			$arrayTemp	 = explode(',', $IdPaisEstadoCidade);
			$IdPais		.= ','.$arrayTemp[0];
			$IdEstado	.= ','.$arrayTemp[1];
			$IdCidade	.= ','.$arrayTemp[2];
		}
		
		$sql_Where .= " 
			and PessoaEndereco.IdPais in (".str_replace("#,", '', $IdPais).")
			and PessoaEndereco.IdEstado in (".str_replace("#,", '', $IdEstado).")
			and PessoaEndereco.IdCidade in (".str_replace("#,", '', $IdCidade).")";
	}
	
	$sql = "
		select
			Email
		from
			Pessoa
			$sql_From,
			PessoaEndereco
		where
			Pessoa.Email != '' and
			Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
			Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco
			$sql_Where
		group by
			Pessoa.IdPessoa;";
	$res = @mysql_query($sql, $con);
	while($lin = @mysql_fetch_array($res)){
		$relacaoEmail .= $lin[Email]."\r\n";
	}
	
	header("Content-type: application/txt");
	header("Content-Disposition: attachment; filename=relacao_e-mail(".date("d-m-Y").").txt");
	die($relacaoEmail);
?>
