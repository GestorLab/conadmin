<?
	global $IdLoja;
	global $IdContaReceber;
	global $IdProcessoFinanceiro;
	global $IdPessoa;
	global $IdLocalCobranca;
	global $IdServico;

	$filtro_sql 	= "";
	$filtro_url 	= "";
	$filtro_from 	= "";
	
	$i = 0;

	$Fields =  "distinct
				Pessoa.IdPessoa,
				Pessoa.Nome,
				Pessoa.NomeRepresentante,
				PessoaEndereco.Endereco,
				PessoaEndereco.Complemento,
				PessoaEndereco.Numero,
				PessoaEndereco.Bairro,
				PessoaEndereco.CEP,
				Cidade.NomeCidade,
				Estado.SiglaEstado";
	
	if($IdPessoa!=""){
		
		$sql	=	"select 
						$Fields
					from 
						Pessoa,
						PessoaEndereco,
						Pais,
						Estado,
						Cidade 
					where 
						Pessoa.IdPessoa in (".$IdPessoa.") and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
						Pais.IdPais = PessoaEndereco.IdPais and
						Pais.IdPais = Estado.IdPais and 
						Estado.IdEstado = PessoaEndereco.IdEstado and
						Pais.IdPais = Cidade.IdPais and 
						Cidade.IdCidade = PessoaEndereco.IdCidade and 
						Estado.IdEstado = Cidade.IdEstado and 
						PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault
					order by
						Pessoa.TipoPessoa,Pessoa.Nome";
		$res	=	mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			$registro[$i][Nome]					= $lin[Nome];
			$registro[$i][NomeRepresentante]	= $lin[NomeRepresentante];
			$registro[$i][Endereco]				= $lin[Endereco];
			$registro[$i][Complemento]			= $lin[Complemento];
			$registro[$i][Numero]				= $lin[Numero];
			$registro[$i][Bairro]				= $lin[Bairro];
			$registro[$i][CEP]					= $lin[CEP];
			$registro[$i][NomeCidade]			= $lin[NomeCidade];
			$registro[$i][SiglaEstado]			= $lin[SiglaEstado];

			$i++;
		}
	}
	
	if($IdContaReceber!=""){
		$sql	=	"select 
						$Fields
					from 
						ContaReceberDados,
						Pessoa,
						PessoaEndereco,
						Pais,
						Estado,
						Cidade  
					where 
						ContaReceberDados.IdLoja = $IdLoja and 
						ContaReceberDados.IdPessoa = Pessoa.IdPessoa and 						
						ContaReceberDados.IdContaReceber in (".$IdContaReceber.") and
						ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Pais.IdPais = PessoaEndereco.IdPais and
						Pais.IdPais = Estado.IdPais and 
						Estado.IdEstado = PessoaEndereco.IdEstado and
						Pais.IdPais = Cidade.IdPais and 
						Cidade.IdCidade = PessoaEndereco.IdCidade and 
						Estado.IdEstado = Cidade.IdEstado
					order by
						Pessoa.TipoPessoa,
						Pessoa.Nome";
		$res	=	mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){

			$registro[$i][Nome]					= $lin[Nome];
			$registro[$i][NomeRepresentante]	= $lin[NomeRepresentante];
			$registro[$i][Endereco]				= $lin[Endereco];
			$registro[$i][Complemento]			= $lin[Complemento];
			$registro[$i][Numero]				= $lin[Numero];
			$registro[$i][Bairro]				= $lin[Bairro];
			$registro[$i][CEP]					= $lin[CEP];
			$registro[$i][NomeCidade]			= $lin[NomeCidade];
			$registro[$i][SiglaEstado]			= $lin[SiglaEstado];

			$i++;
		}
		
	}
	if($IdProcessoFinanceiro!=""){
		$sql	=	"select 
							$Fields
						from 
							ContaReceber,
							(select
								distinct
								LancamentoFinanceiroContaReceber.IdContaReceber
							from
								LancamentoFinanceiro,
								LancamentoFinanceiroContaReceber
							where
								LancamentoFinanceiro.IdLoja = $IdLoja and
								LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
								LancamentoFinanceiro.IdProcessoFinanceiro = $IdProcessoFinanceiro and
								LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro) ContaReceberProcesso,
							Pessoa,
							PessoaEndereco,
							Pais,
							Estado,
							Cidade 
						where 
							ContaReceber.IdLoja = $IdLoja and
							ContaReceber.IdPessoa = Pessoa.IdPessoa and 
							ContaReceber.IdContaReceber = ContaReceberProcesso.IdContaReceber and
							ContaReceber.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
							Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
							Pais.IdPais = PessoaEndereco.IdPais and
							Pais.IdPais = Estado.IdPais and 
							Estado.IdEstado = PessoaEndereco.IdEstado and
							Pais.IdPais = Cidade.IdPais and 
							Cidade.IdCidade = PessoaEndereco.IdCidade and 
							Estado.IdEstado = Cidade.IdEstado
						order by
							Pessoa.TipoPessoa,
							Pessoa.Nome";
		$res	=	mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){

			$registro[$i][Nome]					= $lin[Nome];
			$registro[$i][NomeRepresentante]	= $lin[NomeRepresentante];
			$registro[$i][Endereco]				= $lin[Endereco];
			$registro[$i][Complemento]			= $lin[Complemento];
			$registro[$i][Numero]				= $lin[Numero];
			$registro[$i][Bairro]				= $lin[Bairro];
			$registro[$i][CEP]					= $lin[CEP];
			$registro[$i][NomeCidade]			= $lin[NomeCidade];
			$registro[$i][SiglaEstado]			= $lin[SiglaEstado];

			$i++;
		}
	}

	if($IdLocalCobranca!=""){
		$sql	=	"select 
						$Fields
					from 
						ContaReceberDados,
						Pessoa,
						PessoaEndereco,
						Pais,
						Estado,
						Cidade  
					where 
						ContaReceberDados.IdLoja = $IdLoja and 
						ContaReceberDados.IdPessoa = Pessoa.IdPessoa and 
						ContaReceberDados.IdLocalCobranca = ".$IdLocalCobranca." and
						ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Pais.IdPais = PessoaEndereco.IdPais and
						Pais.IdPais = Estado.IdPais and 
						Estado.IdEstado = PessoaEndereco.IdEstado and
						Pais.IdPais = Cidade.IdPais and 
						Cidade.IdCidade = PessoaEndereco.IdCidade and 
						Estado.IdEstado = Cidade.IdEstado
					order by
						Pessoa.TipoPessoa,
						Pessoa.Nome";
		$res	=	mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){

			$registro[$i][Nome]					= $lin[Nome];
			$registro[$i][NomeRepresentante]	= $lin[NomeRepresentante];
			$registro[$i][Endereco]				= $lin[Endereco];
			$registro[$i][Complemento]			= $lin[Complemento];
			$registro[$i][Numero]				= $lin[Numero];
			$registro[$i][Bairro]				= $lin[Bairro];
			$registro[$i][CEP]					= $lin[CEP];
			$registro[$i][NomeCidade]			= $lin[NomeCidade];
			$registro[$i][SiglaEstado]			= $lin[SiglaEstado];

			$i++;
		}
	}
	
	if($IdServico!=""){
		
		$sql	=	"select 
						$Fields
					from 
						Contrato,
						Pessoa,
						PessoaEndereco,
						Pais,
						Estado,
						Cidade,
						Servico
					where 
						Contrato.IdPessoa = Pessoa.IdPessoa and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
						Pais.IdPais = PessoaEndereco.IdPais and
						Pais.IdPais = Estado.IdPais and 
						Estado.IdEstado = PessoaEndereco.IdEstado and
						Pais.IdPais = Cidade.IdPais and 
						Cidade.IdCidade = PessoaEndereco.IdCidade and 
						Estado.IdEstado = Cidade.IdEstado and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico and
						Servico.IdServico in (".$IdServico.") and
						Contrato.IdStatus >= 200 $filtro_sql
					order by
						Pessoa.TipoPessoa,Pessoa.Nome";
		$res	=	mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			$registro[$i][Nome]					= $lin[Nome];
			$registro[$i][NomeRepresentante]	= $lin[NomeRepresentante];
			$registro[$i][Endereco]				= $lin[Endereco];
			$registro[$i][Complemento]			= $lin[Complemento];
			$registro[$i][Numero]				= $lin[Numero];
			$registro[$i][Bairro]				= $lin[Bairro];
			$registro[$i][CEP]					= $lin[CEP];
			$registro[$i][NomeCidade]			= $lin[NomeCidade];
			$registro[$i][SiglaEstado]			= $lin[SiglaEstado];

			$i++;
		}
	}
	
	$ii=0;
	$iii=0;
	
	$vetor[$ii][$iii] = "Nome";
	$iii++;
	
	$vetor[$ii][$iii] = "Nome Representante";
	$iii++;
	
	$vetor[$ii][$iii] = "Endereco";
	$iii++;
	
	$vetor[$ii][$iii] = "Complemento";
	$iii++;
	
	$vetor[$ii][$iii] = "CEP";
	$iii++;
	$ii=1;
	for($i=0; $i < count($registro); $i++){

		$registro[$i][Nome]					= trim($registro[$i][Nome]);
		$registro[$i][NomeRepresentante]	= trim($registro[$i][NomeRepresentante]);
		$registro[$i][Endereco]				= trim($registro[$i][Endereco]);
		$registro[$i][Complemento]			= trim($registro[$i][Complemento]);
		$registro[$i][Numero]				= trim($registro[$i][Numero]);
		$registro[$i][Bairro]				= trim($registro[$i][Bairro]);
		$registro[$i][CEP]					= trim($registro[$i][CEP]);
		$registro[$i][NomeCidade]			= trim($registro[$i][NomeCidade]);
		$registro[$i][SiglaEstado]			= trim($registro[$i][SiglaEstado]);
		
		$iii=0;
			
		// Linha 1
		$vetor[$ii][$iii] = $registro[$i][Nome];
		$iii++;

		// Linha 2
		if($registro[$i][NomeRepresentante] != ''){
			$vetor[$ii][$iii] = $registro[$i][NomeRepresentante];
			$iii++;
		}else{
			$vetor[$ii][$iii] = " ";
			$iii++;
		}
		
		// Linha 3
		$vetor[$ii][$iii] = $registro[$i][Endereco];
		if($registro[$i][Numero] != ''){	
			$vetor[$ii][$iii] .= ", ".$registro[$i][Numero];	
		}
		$iii++;

		// Linha 4
		$vetor[$ii][$iii] = $registro[$i][Complemento];
		if($registro[$i][Complemento] != ''){	
			$vetor[$ii][$iii] .= " - ";
		}
		
		$vetor[$ii][$iii] .= $registro[$i][Bairro];
		$iii++;

		// Linha 5
		$vetor[$ii][$iii]	= $registro[$i][NomeCidade];
		$vetor[$ii][$iii]  .= "/".$registro[$i][SiglaEstado];
		$vetor[$ii][$iii]  .= " - ".$registro[$i][CEP];
		$iii++;

		$ii++;
	}
		
	$total_etiq	=	count($vetor); 	
?>
