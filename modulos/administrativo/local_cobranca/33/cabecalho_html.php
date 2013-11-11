<div id='cabecalho' style='height: 38px;'>
	<img src='<?=$ExtLogoHTML?>' / style='float:left'>
	<?		
		$sql = "select
					IdNotaFiscalLayout
				from
					NotaFiscal
				where
					Idloja = $IdLoja and
					IdContaReceber = $IdContaReceber and
					IdStatus = 1";
		$resNotaFiscal = @mysql_query($sql,$con);
		if($linNotaFiscal = @mysql_fetch_array($resNotaFiscal)){	
			$sql = "select 
						Pessoa.IdPessoa, 
						Pessoa.TipoPessoa, 
						Pessoa.Nome, 
						Pessoa.RazaoSocial, 
						Pessoa.CPF_CNPJ, 
						Pessoa.RG_IE, 
						PessoaEndereco.Endereco, 
						PessoaEndereco.Numero, 
						PessoaEndereco.Complemento, 
						PessoaEndereco.Bairro,
						PessoaEndereco.CEP,
						Cidade.NomeCidade, 
						Estado.SiglaEstado,
						Pessoa.Telefone1, 
						Pessoa.Telefone2, 
						Pessoa.Fax
					from 
						Pessoa,
						PessoaEndereco,
						Cidade, 
						Estado,
						NotaFiscalTipo
					where 
						NotaFiscalTipo.IdLoja = $IdLoja and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Cidade.IdPais = PessoaEndereco.IdPais and 
						Cidade.IdEstado = PessoaEndereco.IdEstado and 
						Cidade.IdCidade = PessoaEndereco.IdCidade and 
						Cidade.IdPais = Estado.IdPais and 
						Cidade.IdEstado = Estado.IdEstado and
						Pessoa.IdPessoa = NotaFiscalTipo.IdPessoa and			
						NotaFiscalTipo.IdNotaFiscalLayout = $linNotaFiscal[IdNotaFiscalLayout] and
						NotaFiscalTipo.IdStatus = 1";
			$res = mysql_query($sql,$con);
			if($linDadosPessoaNotaFiscal = mysql_fetch_array($res)){
				
				if($linDadosPessoaNotaFiscal[Telefone1] != ''){
					$linDadosPessoaNotaFiscal[Telefone] = $linDadosPessoaNotaFiscal[Telefone1];
				}else{
					$linDadosPessoaNotaFiscal[Telefone] = $linDadosPessoaNotaFiscal[Telefone2];
				}
			
				if($linDadosPessoaNotaFiscal[TipoPessoa] == 1){
					$CPF_CNPJ = "CNPJ";
				}else{			
					$CPF_CNPJ = "CPF";
				}	
			
				$linDadosPessoaNotaFiscal[Endereco]	= $linDadosPessoaNotaFiscal[Endereco].", ".$linDadosPessoaNotaFiscal[Numero];
			
				if($linDadosPessoaNotaFiscal[Complemento] != ''){
					$linDadosPessoaNotaFiscal[Endereco] .= " - ".$linDadosPessoaNotaFiscal[Complemento];
				}
			
				$linDadosPessoaNotaFiscal[Endereco] .= " - ".$linDadosPessoaNotaFiscal[Bairro];
				
				$linDadosPessoaNotaFiscal[Cidade]	= $linDadosPessoaNotaFiscal[NomeCidade]."-".$linDadosPessoaNotaFiscal[SiglaEstado]." - Cep: ".$linDadosPessoaNotaFiscal[CEP];
				$linDadosPessoaNotaFiscal[Cedente]	= substr($linDadosPessoaNotaFiscal[RazaoSocial],0,65);
			
				if($linDadosPessoaNotaFiscal[Telefone] != ''){
					$linDadosPessoaNotaFiscal[Fone] 				= " - Fone / Fax: ".$linDadosPessoaNotaFiscal[Telefone];
				}
			
				if($linDadosPessoaNotaFiscal[RG_IE] != ''){
					$linDadosPessoaNotaFiscal[IE] 				= " - IE: ".$linDadosPessoaNotaFiscal[RG_IE];
				}
						
				echo"$linDadosPessoaNotaFiscal[Cedente]<br>$linDadosPessoaNotaFiscal[Endereco] - $linDadosPessoaNotaFiscal[Cidade]<br>$CPF_CNPJ: $linDadosPessoaNotaFiscal[CPF_CNPJ]".$linDadosPessoaNotaFiscal[IE].$linDadosPessoaNotaFiscal[Fone];					
			}
		}else{		
			echo "$dadosboleto[cedente]<br>$dadosboleto[endereco] - $dadosboleto[cidade]<br>$CPF_CNPJ: $dadosboleto[cpf_cnpj]".$dadosboleto[ie].$dadosboleto[fone];
		}
	?>
</div>
