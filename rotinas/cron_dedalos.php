<?
	$sqlLoja = "SELECT
					IdLoja
				FROM
					CodigoInterno
				WHERE
					IdGrupoCodigoInterno = 50 AND
					IdCodigoInterno = 1 AND
					ValorCodigoInterno = 1";
	$resLoja = mysql_query($sqlLoja,$con);
	while($linLoja = mysql_fetch_array($resLoja)){
		
		$IdLoja				= $linLoja[IdLoja];
		$IdPessoaAux		= 0;
		$IdContaReceberAux	= 0;
		$FechaTagReg		= 0;
		$FechaTagFinanceiro = 0;
		$GeraArquivo		= false;
		$linha 				= 0;
		$NomeArquivo		= "REM".date("YmdHi");
		$ExtensaoArquivo	= "XML";

		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;

		$sql = "select
					 Pessoa.IdPessoa, 
					 Pessoa.Nome,
					 Pessoa.RazaoSocial,
					 Pessoa.NomeRepresentante,
					 Pessoa.NomePai,
					 Pessoa.NomeMae,
					 Pessoa.CPF_CNPJ,
					 Pessoa.RG_IE,
					 Pessoa.InscricaoMunicipal,
					 Pessoa.EstadoCivil,
					 Pessoa.Sexo,
					 Pessoa.Telefone1,
					 Pessoa.Telefone2,
					 Pessoa.Telefone3,
					 Pessoa.Celular,
					 Pessoa.Fax,
					 Pessoa.ComplementoTelefone,
					 Pessoa.CPF_CNPJ,
					 Pessoa.Email,
					 Pessoa.Site,
					 Pessoa.Obs,
					 Pessoa.LoginCriacao,
					 Pessoa.DataCriacao,
					 Pessoa.LoginAlteracao,
					 Pessoa.DataAlteracao,
					 ContaReceberRecebimento.IdLoja,
					 ContaReceberRecebimento.IdContaReceber,
					 ContaReceberRecebimento.IdContaReceberRecebimento,
					 ContaReceberRecebimento.IdLocalCobranca
				from
					 ParametroRecebimento,
					 ContaReceberRecebimento,
					 ContaReceber,
					 Pessoa
				where
					 ParametroRecebimento.IdLoja = $IdLoja and
					 ParametroRecebimento.IdLoja = ContaReceberRecebimento.IdLoja and
					 ParametroRecebimento.IdLoja = ContaReceber.IdLoja and
					 ParametroRecebimento.IdParametroRecebimento = 'Dedalos' and				 
					 ParametroRecebimento.IdLocalCobranca = ContaReceberRecebimento.IdLocalCobranca and
					 ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber and
					 ContaReceber.IdPessoa = Pessoa.IdPessoa
				order by
					Pessoa.IdPessoa, ContaReceber.IdContaReceber";
		$res	=	mysql_query($sql,$con);
		while($lin	=	mysql_fetch_array($res)){	
			
			$sql = "select
						count(*) Qtd
					from
						ContaReceberRecebimentoParametro
					where
						IdLoja = $IdLoja and
						IdContaReceber = $lin[IdContaReceber] and
						IdLocalCobranca = $lin[IdLocalCobranca] and
						IdContaReceberRecebimento = $lin[IdContaReceberRecebimento] and
						IdParametroRecebimento = 'Dedalos'";
			$res_	=	mysql_query($sql,$con);
			$lin_	=	mysql_fetch_array($res_);	
			
			if($lin_[Qtd] == 0){
				if($IdPessoaAux != $lin[IdPessoa]){
					if($lin[EstadoCivil] != ''){
						$lin[EstadoCivil] = getParametroSistema(9,$lin[EstadoCivil]);
					}
						
					$dados[$linha] = "<reg>";
					$linha++;
					$dados[$linha] = 	"<IdPessoa>$lin[IdPessoa]</IdPessoa>";
					$linha++;
					$dados[$linha] = 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
					$linha++;
					$dados[$linha] = 	"<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
					$linha++;
					$dados[$linha] = 	"<NomeRepresentante><![CDATA[$lin[NomeRepresentante]]]></NomeRepresentante>";
					$linha++;
					$dados[$linha] = 	"<NomePai><![CDATA[$lin[NomePai]]]></NomePai>";
					$linha++;
					$dados[$linha] = 	"<NomeMae><![CDATA[$lin[NomeMae]]]></NomeMae>";
					$linha++;
					$dados[$linha] = 	"<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
					$linha++;
					$dados[$linha] = 	"<Telefone2><![CDATA[$lin[Telefone2]]]></Telefone2>";
					$linha++;
					$dados[$linha] = 	"<Telefone3><![CDATA[$lin[Telefone3]]]></Telefone3>";
					$linha++;
					$dados[$linha] = 	"<Celular><![CDATA[$lin[Celular]]]></Celular>";
					$linha++;
					$dados[$linha] = 	"<Fax><![CDATA[$lin[Fax]]]></Fax>";
					$linha++;
					$dados[$linha] = 	"<ComplementoTelefone><![CDATA[$lin[ComplementoTelefone]]]></ComplementoTelefone>";
					$linha++;
					$dados[$linha] = 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
					$linha++;
					$dados[$linha] = 	"<RG_IE><![CDATA[$lin[RG_IE]]]></RG_IE>";
					$linha++;
					$dados[$linha] = 	"<Sexo><![CDATA[$lin[Sexo]]]></Sexo>";	
					$linha++;
					$dados[$linha] = 	"<EstadoCivil><![CDATA[$lin[EstadoCivil]]]></EstadoCivil>";	
					$linha++;
					$dados[$linha] = 	"<Email><![CDATA[$lin[Email]]]></Email>";
					$linha++;
					$dados[$linha] = 	"<Site><![CDATA[$lin[Site]]]></Site>";
					$linha++;
					$dados[$linha] = 	"<Obs><![CDATA[$lin[Obs]]]></Obs>";
					$linha++;
					$dados[$linha] = 	"<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
					$linha++;
					$dados[$linha] = 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
					$linha++;
					$dados[$linha] = 	"<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
					$linha++;
					$dados[$linha] = 	"<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
					$linha++;
					$dados[$linha] =	"<Senha><![CDATA[$lin[Senha]]]></Senha>";
					$linha++;

					$sql	=  "select
									PessoaEndereco.Endereco,
									PessoaEndereco.Numero,
									PessoaEndereco.Bairro,
									PessoaEndereco.Telefone TelefoneEndereco,
									PessoaEndereco.Celular CelularEndereco,
									PessoaEndereco.Fax FaxEndereco,
									Cidade.NomeCidade,
									Estado.SiglaEstado,
									PessoaEndereco.EmailEndereco
								from
									PessoaEndereco left join (
										Pais, 
										Estado,
										Cidade
									) on (
										PessoaEndereco.IdPais = Pais.IdPais and
										Pais.IdPais = Estado.IdPais and
										PessoaEndereco.IdEstado = Estado.IdEstado and 
										Estado.IdPais = Cidade.IdPais and 
										Estado.IdEstado = Cidade.IdEstado and
										PessoaEndereco.IdCidade = Cidade.IdCidade 
									)	
								where
									PessoaEndereco.IdPessoa	= $lin[IdPessoa]";
					$res2	=	mysql_query($sql,$con);
					while($lin2	=	mysql_fetch_array($res2)){
						$dados[$linha] = "<PessoaEndereco>";
						$linha++;
						$dados[$linha] = 	"<Endereco><![CDATA[$lin2[Endereco]]]></Endereco>";	
						$linha++;
						$dados[$linha] = 	"<Numero><![CDATA[$lin2[Numero]]]></Numero>";	
						$linha++;
						$dados[$linha] = 	"<Bairro><![CDATA[$lin2[Bairro]]]></Bairro>";
						$linha++;
						$dados[$linha] = 	"<TelefoneEndereco><![CDATA[$lin2[TelefoneEndereco]]]></TelefoneEndereco>";
						$linha++;
						$dados[$linha] = 	"<CelularEndereco><![CDATA[$lin2[CelularEndereco]]]></CelularEndereco>";
						$linha++;
						$dados[$linha] = 	"<FaxEndereco><![CDATA[$lin2[FaxEndereco]]]></FaxEndereco>";
						$linha++;
						$dados[$linha] = 	"<NomeCidade><![CDATA[$lin2[NomeCidade]]]></NomeCidade>";
						$linha++;
						$dados[$linha] = 	"<SiglaEstado><![CDATA[$lin2[SiglaEstado]]]></SiglaEstado>";
						$linha++;
						$dados[$linha] = 	"<EmailEndereco><![CDATA[$lin2[EmailEndereco]]]></EmailEndereco>";
						$linha++;
						$dados[$linha] = "</PessoaEndereco>";
						$linha++;
					}
					$IdPessoaAux = $lin[IdPessoa];
					$FechaTagReg = 1;
				}
				
				if($IdContaReceberAux != $lin[IdContaReceber]){
					$dados[$linha] = "<Financeiro>";
					$linha++;
					
					$sql = "select
								ContaReceber.IdLoja,
								ContaReceber.IdPessoa,
								ContaReceber.IdContaReceber,
								ContaReceber.ValorLancamento,
								ContaReceber.ValorDespesas,
								ContaReceber.DataLancamento,
								ContaReceber.DataVencimento,
								ContaReceber.LimiteDesconto,
								ContaReceber.NumeroDocumento,
								ContaReceber.NumeroNF,
								ContaReceber.DataNF,
								ContaReceber.IdStatus,
								ContaReceber.Obs,
								ContaReceber.LoginCriacao,
								ContaReceber.DataCriacao,
								ContaReceber.LoginAlteracao,
								ContaReceber.DataAlteracao
							from
								ContaReceber
							where
								ContaReceber.IdLoja = $IdLoja and
								ContaReceber.IdContaReceber = $lin[IdContaReceber]";
					$res3	=	mysql_query($sql,$con);
					if($lin3	=	mysql_fetch_array($res3)){

						$lin3[Status] = getParametroSistema(35, $lin3[IdStatus]);

						$dados[$linha] = "<ContaReceber>";
						$linha++;
						$dados[$linha] = 	"<IdLoja><![CDATA[$lin3[IdLoja]]]></IdLoja>";
						$linha++;
						$dados[$linha] = 	"<IdPessoa><![CDATA[$lin3[IdPessoa]]]></IdPessoa>";
						$linha++;
						$dados[$linha] = 	"<IdContaReceber><![CDATA[$lin3[IdContaReceber]]]></IdContaReceber>";
						$linha++;
						$dados[$linha] = 	"<ValorLancamento><![CDATA[$lin3[ValorLancamento]]]></ValorLancamento>";
						$linha++;
						$dados[$linha] = 	"<ValorDespesas><![CDATA[$lin3[ValorDespesas]]]></ValorDespesas>";
						$linha++;
						$dados[$linha] = 	"<DataLancamento><![CDATA[$lin3[DataLancamento]]]></DataLancamento>";
						$linha++;
						$dados[$linha] = 	"<DataVencimento><![CDATA[$lin3[DataVencimento]]]></DataVencimento>";
						$linha++;
						$dados[$linha] = 	"<LimiteDesconto><![CDATA[$lin3[LimiteDesconto]]]></LimiteDesconto>";
						$linha++;
						$dados[$linha] = 	"<NumeroDocumento><![CDATA[$lin3[NumeroDocumento]]]></NumeroDocumento>";
						$linha++;
						$dados[$linha] = 	"<NumeroNF><![CDATA[$lin3[NumeroNF]]]></NumeroNF>";
						$linha++;
						$dados[$linha] = 	"<Status><![CDATA[$lin3[Status]]]></Status>";
						$linha++;
						$dados[$linha] = 	"<Obs><![CDATA[$lin3[Obs]]]></Obs>";
						$linha++;
						$dados[$linha] = 	"<LoginCriacao><![CDATA[$lin3[LoginCriacao]]]></LoginCriacao>";
						$linha++;
						$dados[$linha] = 	"<DataCriacao><![CDATA[$lin3[DataCriacao]]]></DataCriacao>";
						$linha++;
						$dados[$linha] = 	"<LoginAlteracao><![CDATA[$lin3[LoginAlteracao]]]></LoginAlteracao>";
						$linha++;
						$dados[$linha] = 	"<DataAlteracao><![CDATA[$lin3[DataAlteracao]]]></DataAlteracao>";
						$linha++;

						$sql = "select
									LancamentoFinanceiro.IdLoja,
									LancamentoFinanceiro.IdLancamentoFinanceiro,
									LancamentoFinanceiro.IdContrato,
									LancamentoFinanceiro.IdContaEventual,
									LancamentoFinanceiro.IdEstorno,
									LancamentoFinanceiro.NumParcelaEventual,
									LancamentoFinanceiro.IdOrdemServico,
									LancamentoFinanceiro.Valor,
									LancamentoFinanceiro.ValorDescontoAConceber,
									LancamentoFinanceiro.LimiteDesconto,
									LancamentoFinanceiro.ValorRepasseTerceiro,
									LancamentoFinanceiro.DataReferenciaInicial,
									LancamentoFinanceiro.DataReferenciaFinal,
									LancamentoFinanceiro.IdProcessoFinanceiro,
									LancamentoFinanceiro.ObsLancamentoFinanceiro,
									LancamentoFinanceiro.IdStatus,
									LancamentoFinanceiro.LoginCancelamento,
									LancamentoFinanceiro.DataCancelamento
								from
									LancamentoFinanceiro,
									LancamentoFinanceiroContaReceber
								where
									LancamentoFinanceiro.IdLoja = $IdLoja and
									LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
									LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
									LancamentoFinanceiroContaReceber.IdContaReceber = $lin[IdContaReceber]";
						$res4	=	mysql_query($sql,$con);
						while($lin4	=	mysql_fetch_array($res4)){

							$lin4[Status] = getParametroSistema(51, $lin4[IdStatus]);

							$dados[$linha] = "<LancamentoFinanceiro>";	
							$linha++;
							$dados[$linha] = 	"<IdLoja><![CDATA[$lin4[IdLoja]]]></IdLoja>";	
							$linha++;
							$dados[$linha] = 	"<IdContaReceber><![CDATA[$lin3[IdContaReceber]]]></IdContaReceber>";	
							$linha++;
							$dados[$linha] = 	"<IdLancamentoFinanceiro><![CDATA[$lin4[IdLancamentoFinanceiro]]]></IdLancamentoFinanceiro>";
							$linha++;
							$dados[$linha] = 	"<IdContrato><![CDATA[$lin4[IdContrato]]]></IdContrato>";
							$linha++;
							$dados[$linha] = 	"<IdContaEventual><![CDATA[$lin4[IdContaEventual]]]></IdContaEventual>";
							$linha++;
							$dados[$linha] = 	"<IdEstorno><![CDATA[$lin4[IdEstorno]]]></IdEstorno>";
							$linha++;
							$dados[$linha] = 	"<NumParcelaEventual><![CDATA[$lin4[NumParcelaEventual]]]></NumParcelaEventual>";
							$linha++;
							$dados[$linha] = 	"<IdOrdemServico><![CDATA[$lin4[IdOrdemServico]]]></IdOrdemServico>";
							$linha++;
							$dados[$linha] = 	"<Valor><![CDATA[$lin4[Valor]]]></Valor>";
							$linha++;
							$dados[$linha] = 	"<ValorDescontoAConceber><![CDATA[$lin4[ValorDescontoAConceber]]]></ValorDescontoAConceber>";
							$linha++;
							$dados[$linha] = 	"<LimiteDesconto><![CDATA[$lin4[LimiteDesconto]]]></LimiteDesconto>";
							$linha++;
							$dados[$linha] = 	"<ValorRepasseTerceiro><![CDATA[$lin4[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
							$linha++;
							$dados[$linha] = 	"<DataReferenciaInicial><![CDATA[$lin4[DataReferenciaInicial]]]></DataReferenciaInicial>";
							$linha++;
							$dados[$linha] = 	"<IdProcessoFinanceiro><![CDATA[$lin4[IdProcessoFinanceiro]]]></IdProcessoFinanceiro>";
							$linha++;
							$dados[$linha] = 	"<ObsLancamentoFinanceiro><![CDATA[$lin4[ObsLancamentoFinanceiro]]]></ObsLancamentoFinanceiro>";
							$linha++;
							$dados[$linha] = 	"<Status><![CDATA[$lin4[Status]]]></Status>";
							$linha++;
							$dados[$linha] = 	"<LoginCancelamento><![CDATA[$lin4[LoginCancelamento]]]></LoginCancelamento>";
							$linha++;
							$dados[$linha] = 	"<DataCancelamento><![CDATA[$lin4[DataCancelamento]]]></DataCancelamento>";
							$linha++;
							$dados[$linha] = "</LancamentoFinanceiro>";
							$linha++;
						}	
						
					}
					$dados[$linha] = "</ContaReceber>";
					$linha++;
					$IdContaReceberAux = $lin[IdContaReceber];
					$FechaTagFinanceiro = 1;
				}
				
				$sql = "select
							ContaReceberRecebimento.IdLoja,							
							ContaReceberRecebimento.IdContaReceber,
							ContaReceberRecebimento.IdContaReceberRecebimento,
							ContaReceberRecebimento.DataRecebimento,
							ContaReceberRecebimento.ValorDesconto,
							ContaReceberRecebimento.ValorOutrasDespesas,
							ContaReceberRecebimento.ValorMoraMulta,
							ContaReceberRecebimento.ValorRecebido,
							ContaReceberRecebimento.IdRecibo,
							ContaReceberRecebimento.CreditoFuturo,
							ContaReceberRecebimento.IdLocalCobranca,
							ContaReceberRecebimento.IdStatus,
							ContaReceberRecebimento.Obs,
							ContaReceberRecebimento.LoginCriacao,
							ContaReceberRecebimento.DataCriacao,
							ContaReceberRecebimento.LoginAlteracao,
							ContaReceberRecebimento.DataAlteracao
						from
							ContaReceberRecebimento
						where
							ContaReceberRecebimento.IdLoja = $IdLoja and
							ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and
							ContaReceberRecebimento.IdLocalCobranca = $lin[IdLocalCobranca]";
				$res5	=	mysql_query($sql,$con);
				while($lin5	=	mysql_fetch_array($res5)){
					
					$sql = "insert into ContaReceberRecebimentoParametro set
								IdLoja						= $IdLoja,
								IdContaReceber				= $lin[IdContaReceber],
								IdContaReceberRecebimento	= $lin5[IdContaReceberRecebimento],
								IdLocalCobranca				= $lin5[IdLocalCobranca],
								IdParametroRecebimento		= 'Dedalos',
								ValorParametro				= '$NomeArquivo.$ExtensaoArquivo'";
					$transaction[$tr_i]	=	mysql_query($sql,$con);
					if($transaction[$tr_i] == false){
						echo "<br><br>".$sql." ".mysql_error();
					}
					$tr_i++;

					$lin5[Status] = getParametroSistema(85, $lin5[IdStatus]);

					$dados[$linha] = "<ContaReceberRecebimento>";
					$linha++;
					$dados[$linha] = 	"<IdLoja><![CDATA[$lin5[IdLoja]]]></IdLoja>";
					$linha++;
					$dados[$linha] = 	"<IdContaReceber><![CDATA[$lin5[IdContaReceber]]]></IdContaReceber>";
					$linha++;
					$dados[$linha] = 	"<IdContaReceberRecebimento><![CDATA[$lin5[IdContaReceberRecebimento]]]></IdContaReceberRecebimento>";
					$linha++;
					$dados[$linha] = 	"<DataRecebimento><![CDATA[$lin5[DataRecebimento]]]></DataRecebimento>";
					$linha++;
					$dados[$linha] = 	"<ValorDesconto><![CDATA[$lin5[ValorDesconto]]]></ValorDesconto>";
					$linha++;
					$dados[$linha] = 	"<ValorOutrasDespesas><![CDATA[$lin5[ValorOutrasDespesas]]]></ValorOutrasDespesas>";
					$linha++;
					$dados[$linha] = 	"<ValorMoraMulta><![CDATA[$lin5[ValorMoraMulta]]]></ValorMoraMulta>";
					$linha++;
					$dados[$linha] = 	"<ValorRecebido><![CDATA[$lin5[ValorRecebido]]]></ValorRecebido>";
					$linha++;
					$dados[$linha] = 	"<IdRecibo><![CDATA[$lin5[IdRecibo]]]></IdRecibo>";
					$linha++;
					$dados[$linha] = 	"<CreditoFuturo><![CDATA[$lin5[CreditoFuturo]]]></CreditoFuturo>";
					$linha++;
					$dados[$linha] = 	"<Status><![CDATA[$lin5[Status]]]></Status>";
					$linha++;
					$dados[$linha] = 	"<Obs><![CDATA[$lin5[Obs]]]></Obs>";
					$linha++;
					$dados[$linha] = 	"<LoginCriacao><![CDATA[$lin5[LoginCriacao]]]></LoginCriacao>";
					$linha++;
					$dados[$linha] = 	"<DataCriacao><![CDATA[$lin5[DataCriacao]]]></DataCriacao>";
					$linha++;
					$dados[$linha] = 	"<LoginAlteracao><![CDATA[$lin5[LoginAlteracao]]]></LoginAlteracao>";
					$linha++;
					$dados[$linha] = 	"<DataAlteracao><![CDATA[$lin5[DataAlteracao]]]></DataAlteracao>";
					$linha++;
					$dados[$linha] = "</ContaReceberRecebimento>";
					$linha++;
				}
				if($FechaTagFinanceiro == 1){
					$dados[$linha] = "</Financeiro>";
					$linha++;
					$FechaTagFinanceiro = 0;
				}
				if($FechaTagReg == 1){
					$dados[$linha] = "</reg>";
					$linha++;
					$FechaTagReg = 0;
				}
			}			
		}// fim do primeiro while	

		for($i=0; $i<$tr_i; $i++){
			if($transaction[$i] == false){
				$transaction = false;
			}
		}
		
		if($transaction == true){		
			if(!is_dir($Path."modulos/administrativo/remessa/dedalos/$IdLoja")){
				mkdir($Path."modulos/administrativo/remessa/dedalos/$IdLoja");
			}		
			$FileName = $Path."modulos/administrativo/remessa/dedalos/$IdLoja/$NomeArquivo.$ExtensaoArquivo";
			if($File = fopen($FileName, 'a')) {
				fwrite($File, "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?> <db>");
				for($i=0;$i <= $linha;$i++){
					fwrite($File, $dados[$i]);
				}
				fwrite($File, "</db>");
			}
			fclose($File);
			
			$zip = new zipfile;
			$abre = fopen($FileName, "r" );
			$com  = fread($abre,filesize($FileName));
			fclose($abre);
			$zip->addFile($com,$FileName);  // Adiciona arquivos ao .zip
			$strzip=$zip->file();
			$abre = fopen($FileName.".ZIP", "w");
			$salva = fwrite($abre, $strzip);
			fclose($abre);
			@unlink($FileName);
			//header("Location: $FileName.zip");		

			$sql = "COMMIT;";
		}else{
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>