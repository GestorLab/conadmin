<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$lin[Qtd]	=	0;
		
		if($local_CPF_CNPJ != ''){
			$sql	=	"select 
							*
						from 
							Loja
						where 							
							RestringirPessoa = 1 and
							IdLoja = $local_IdLoja";
			$res	=	mysql_query($sql,$con);
			if($lin	=	@mysql_fetch_array($res)){
				// Sql Busca CPJ_CNPJ
				$sql	=	"select 
								count(*) Qtd 
							from 
								Pessoa
							where 
								CPF_CNPJ='$local_CPF_CNPJ' and 
								CPF_CNPJ!='' and
								IdLoja = $local_IdLoja and
								IdPessoa != $local_IdPessoa";
			}else{
				$sql	=	"select 
								count(*) Qtd 
							from 
								Pessoa 
							where 
								CPF_CNPJ='$local_CPF_CNPJ' and 
								CPF_CNPJ!='' and 
								IdPessoa!=$local_IdPessoa;";

			}
			$res	=	mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
		}

		if(getCodigoInterno(11,4)==1){
			$lin[Qtd] = 0;
		}
		
		if($lin[Qtd] == 0){	
			if($local_Cob_CobrarDespesaBoleto == ''){	$local_Cob_CobrarDespesaBoleto 	= 'NULL';		}else{	$local_Cob_CobrarDespesaBoleto	=	"'$local_Cob_CobrarDespesaBoleto'";		}
			if($local_TipoUsuario == ''){				$local_TipoUsuario 				= 'NULL';		}
			if($local_TipoAgenteAutorizado == ''){		$local_TipoAgenteAutorizado 	= 'NULL';		}
			if($local_TipoFornecedor == ''){			$local_TipoFornecedor 			= 'NULL';		}
			if($local_TipoVendedor == ''){				$local_TipoVendedor 			= 'NULL';		}
			
			$sql = "select 
						TipoPessoa,
						Nome,
						RazaoSocial,
						NomeRepresentante,
						RG_IE,
						CPF_CNPJ,
						EstadoCivil,
						Sexo,
						OrgaoExpedidor,
						NomeConjugue,
						NomePai,
						NomeMae,
						Telefone1,
						Telefone2,
						Telefone3,
						Celular,
						Fax,
						Email,
						Cob_FormaCorreio,
						Cob_FormaEmail,
						Cob_FormaOutro,
						CampoExtra1,
						CampoExtra2,
						CampoExtra3,
						CampoExtra4
					from 
						Pessoa 
					where 
						IdPessoa = $local_IdPessoa";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			$Obs	=	"";
			
			if($lin[CPF_CNPJ]!= $local_CPF_CNPJ){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de CPF/CNPJ [$lin[CPF_CNPJ] > $local_CPF_CNPJ]";
			}
			
			if($lin[EstadoCivil]!= $local_EstadoCivil && $lin[EstadoCivil] != ''){
				$sqlECAnterior = "select ValorParametroSistema	from ParametroSistema where	IdGrupoParametroSistema = 9 and	IdParametroSistema = $lin[EstadoCivil]";
				$resECAnterior = mysql_query($sqlECAnterior,$con);
				$linECAnterior = mysql_fetch_array($resECAnterior);
				
				$sqlEC = "select ValorParametroSistema	from ParametroSistema where	IdGrupoParametroSistema = 9 and	IdParametroSistema = $local_EstadoCivil";
				$resEC = mysql_query($sqlEC,$con);
				$linEC = mysql_fetch_array($resEC);
				
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Estado Civil [$linECAnterior[ValorParametroSistema] > $linEC[ValorParametroSistema]]";
			}
			
			if($lin[TipoPessoa] == 2){ //Física
				if($lin[Nome]!= $local_Nome){
					if($Obs != '') { $Obs .= "\n"; }
					
					$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Nome Pessoa [$lin[Nome] > $local_Nome]";
				}
				
				if($lin[RG_IE]!= $local_RG_IE){
					if($Obs != '') { $Obs .= "\n"; }
					
					$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de RG [$lin[RG_IE] > $local_RG_IE]";
				}
				
			} else if($lin[TipoPessoa] == 1){ //Jurídica
				if($lin[RazaoSocial]!= $local_RazaoSocial){
					if($Obs != '') { $Obs .= "\n"; }
					
					$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Razão Social [$lin[RazaoSocial] > $local_RazaoSocial]";
				}
				
				if($lin[Nome]!= $local_NomeFantasia){
					if($Obs != '') { $Obs .= "\n"; }
					
					$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Nome Fantasia [$lin[Nome] > $local_NomeFantasia]";
				}
				
				if($lin[NomeRepresentante]!= $local_NomeRepresentante){
					if($Obs != '') { $Obs .= "\n"; }
					
					$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Nome Representante [$lin[NomeRepresentante] > $local_NomeRepresentante]";
				}
				
				if($lin[RG_IE]!= $local_InscricaoEstadual){
					if($Obs != '') { $Obs .= "\n"; }
					
					$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Inscrição Estadual [$lin[RG_IE] > $local_InscricaoEstadual]";
				}
			}
			//Campos Extras-------------------------------
			if($lin[CampoExtra4]!= $local_CampoExtra4){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de ".getParametroSistema(2,11)." [$lin[CampoExtra4] > $local_CampoExtra4]";
			}
			if($lin[CampoExtra3]!= $local_CampoExtra3){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de ".getParametroSistema(2,8)." [$lin[CampoExtra3] > $local_CampoExtra3]";
			}
			if($lin[CampoExtra2]!= $local_CampoExtra2){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de ".getParametroSistema(2,5)." [$lin[CampoExtra2] > $local_CampoExtra2]";
			}
			if($lin[CampoExtra1]!= $local_CampoExtra1){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de ".getParametroSistema(2,2)." [$lin[CampoExtra1] > $local_CampoExtra1]";
			}
			//--------------------------------------------
			if($lin[OrgaoExpedidor]!= $local_OrgaoExpedidor){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Orgao Expedidor [$lin[OrgaoExpedidor] > $local_OrgaoExpedidor]";
			}
			
			if($lin[NomeConjugue]!= $local_NomeConjugue){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Nome Conjugue [$lin[NomeConjugue] > $local_NomeConjugue]";
			}
			
			if($lin[Telefone1]!= $local_Telefone1){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Fone Residencial [$lin[Telefone1] > $local_Telefone1]";
			}
			
			if($lin[Telefone2]!= $local_Telefone2){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Fone Comercial (1) [$lin[Telefone2] > $local_Telefone2]";
			}
			
			if($lin[Telefone3]!= $local_Telefone3){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Fone Comercial (2) [$lin[Telefone3] > $local_Telefone3]";
			}
			
			if($lin[Celular]!= $local_Celular){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Celular [$lin[Celular] > $local_Celular]";
			}
			
			if($lin[Fax]!= $local_Fax){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Fax [$lin[Fax] > $local_Fax]";
			}

			if($lin[Sexo]!= $local_Sexo && ($lin[Sexo] != "" && $local_Sexo != "")){
				if($Obs != '') { $Obs .= "\n"; }
				$DescricaoSexo	= getParametroSistema(8,$lin[Sexo]);
				$DescricaoSexo2 = getParametroSistema(8,$local_Sexo);
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Sexo [$DescricaoSexo > $DescricaoSexo2]";
			}

			if($lin[NomePai]!= $local_NomePai){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Nome do Pai [$lin[NomePai] > $local_NomePai]";
			}	

			if($lin[NomeMae]!= $local_NomeMae){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Nome da Mãe [$lin[NomeMae] > $local_NomeMae]";
			}			
			
			if($lin[Cob_FormaCorreio] != $local_Cob_FormaCorreio || $lin[Cob_FormaEmail] != $local_Cob_FormaEmail || $lin[Cob_FormaOutro] != $local_Cob_FormaOutro){
				if($Obs != '') { $Obs .= "\n"; }
				
				$FormaCobrancaAnt = '*';
				
				if($lin[Cob_FormaCorreio] == 'S') {
					$FormaCobrancaAnt .= ", Correio";
				}
				
				if($lin[Cob_FormaEmail] == 'S') {
					$FormaCobrancaAnt .= ", E-mail";
				}
				
				if($lin[Cob_FormaOutro] == 'S') {
					$FormaCobrancaAnt .= ", Outros";
				}
				
				$FormaCobranca = '*';
				
				if($local_Cob_FormaCorreio == 'S') {
					$FormaCobranca .= ", Correio";
				}
				
				if($local_Cob_FormaEmail == 'S') {
					$FormaCobranca .= ", E-mail";
				}
				
				if($local_Cob_FormaOutro == 'S') {
					$FormaCobranca .= ", Outros";
				}
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Forma de Aviso de Cobrança [".str_replace("*,",'',$FormaCobrancaAnt)." > ".str_replace("*,",'',$FormaCobranca)."]";
			}
			
			if($lin[Email]!= $local_Email){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de E-mail [$lin[Email] > $local_Email]";
			}
			
			// Sql de Alteração de Pessoa
			$sql = "select TipoPessoa,Senha from Pessoa where IdPessoa=$local_IdPessoa";
			$res = mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			if($lin[TipoPessoa] == 2){ //Física
				if($local_DataNascimento != ''){ 	
					$local_DataNascimento	= 	"'$local_DataNascimento'";
				}else{
					$local_DataNascimento	=  	'NULL';
				}
				
				$local_NomePai				=	"'$local_NomePai'";
				$local_NomeMae				=	"'$local_NomeMae'";
				$local_NomeRepresentante	=	'';
				$local_RazaoSocial			=	'';
				$local_InscricaoMunicipal	=	'';
				
			} else if($lin[TipoPessoa] == 1){ //Jurídica
				if($local_DataFundacao != ''){ 	
					$local_DataNascimento	= 	"'$local_DataFundacao'";
				}else{
					$local_DataNascimento	=  	'NULL';
				}
				
				$local_Nome					=	$local_NomeFantasia;
				$local_NomePai				=	"NULL";
				$local_NomeMae				=	"NULL";
				$local_Sexo					=	'';
				$local_EstadoCivil			=	'';
				$local_RG_IE				=	$local_InscricaoEstadual;
			}
			
			if($lin[Senha]!= $local_Senha){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Senha CDA [$lin[Senha] > $local_Senha]";
			}
			
			$sql	=	"START TRANSACTION;";
			mysql_query($sql,$con);
			
			$tr_i = 0;
			$sql	=	"UPDATE Pessoa SET
							Nome						='$local_Nome', 
							NomeRepresentante			='$local_NomeRepresentante', 
							RazaoSocial					='$local_RazaoSocial', 
							DataNascimento				= $local_DataNascimento, 
							Sexo						='$local_Sexo', 
							RG_IE						='$local_RG_IE', 
							CPF_CNPJ					='$local_CPF_CNPJ', 
							EstadoCivil					='$local_EstadoCivil', 
							InscricaoMunicipal			='$local_InscricaoMunicipal', 
							Telefone1					='$local_Telefone1',
							Telefone2					='$local_Telefone2', 
							Telefone3					='$local_Telefone3', 
							Celular						='$local_Celular', 
							Fax							='$local_Fax', 
							ComplementoTelefone			='$local_ComplementoTelefone',
							Email						='$local_Email', 
							Site						='$local_Site', 
							Senha						='$local_Senha',
							AgruparContratos			= $local_AgruparContratos,
							ForcarAtualizarDadosCDA		= $local_ForcarAtualizar,
							Cob_CobrarDespesaBoleto 	= $local_Cob_CobrarDespesaBoleto,
							MonitorFinanceiro			= '$local_IdMonitorFinanceiro',
							Cob_FormaEmail				='$local_Cob_FormaEmail',
							Cob_FormaCorreio			='$local_Cob_FormaCorreio',
							Cob_FormaOutro				='$local_Cob_FormaOutro',
							TipoUsuario					= $local_TipoUsuario,
							TipoAgenteAutorizado		= $local_TipoAgenteAutorizado,
							TipoFornecedor				= $local_TipoFornecedor,
							TipoVendedor				= $local_TipoVendedor,
							CampoExtra1					= '$local_CampoExtra1',
							CampoExtra2					= '$local_CampoExtra2',
							CampoExtra3					= '$local_CampoExtra3',
							CampoExtra4					= '$local_CampoExtra4',
							NomePai						= $local_NomePai,
							NomeMae						= $local_NomeMae,
							Resumido					= 2,
							NomeConjugue				= '$local_NomeConjugue',
							OrgaoExpedidor				= '$local_OrgaoExpedidor',
							LoginAlteracao				= '$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdPessoa 					=$local_IdPessoa";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$sql = "SELECT IdPessoa FROM PessoaGrupoPessoa WHERE IdLoja = '$local_IdLoja' AND IdPessoa = '$local_IdPessoa';";
			$res = @mysql_query($sql,$con);
			if(@mysql_num_rows($res) > 0) {
				$sql = "UPDATE PessoaGrupoPessoa SET
							IdGrupoPessoa = $local_IdGrupoPessoa
						WHERE
							IdLoja = $local_IdLoja AND
							IdPessoa = $local_IdPessoa";
			} else {
				$sql = "INSERT INTO PessoaGrupoPessoa SET
							IdGrupoPessoa	= $local_IdGrupoPessoa,
							IdLoja			= $local_IdLoja,
							IdPessoa		= $local_IdPessoa";
			}
			
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			$i	=	1;
			while($i	<=	$local_QtdEndereco){				
				if($_POST['IdPessoaEndereco_'.$i]!=''){
					$local_IdPessoaEndereco	=	$_POST['IdPessoaEndereco_'.$i];
					$local_IdPais			=	$_POST['IdPais_'.$i];
					$local_IdEstado			=	$_POST['IdEstado_'.$i];
					$local_IdCidade			=	$_POST['IdCidade_'.$i];
					
					if($local_IdPais=="") 	$local_IdPais	=	'NULL';
					if($local_IdEstado=="") $local_IdEstado	=	'NULL';
					if($local_IdCidade=="") $local_IdCidade	=	'NULL';
					
					$sql = "select 
								DescricaoEndereco,
								CEP,
								Endereco,
								Numero,
								Complemento,
								Bairro,
								IdPais,
								IdEstado,
								IdCidade,
								Telefone,
								Celular, 
								Fax, 
								ComplementoTelefone,
								EmailEndereco
							from 
								PessoaEndereco 
							where 
								IdPessoa = $local_IdPessoa and 
								IdPessoaEndereco = $local_IdPessoaEndereco;";
					$res = mysql_query($sql,$con);
					$lin = mysql_fetch_array($res);
					/*$aux = $local_QtdEndereco;
					while($local_QtdEndereco == $local_QtdEnderecoAux){
						if($local_QtdEndereco > $aux){
							if()
							if($Obs != '') { $Obs .= "\n"; }
							
							$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Novo endereço adicionado";
						}
						$aux++;
					}
					if($local_QtdEndereco > $local_QtdEnderecoAux){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço $i Excluido";
					}*/
					
					$AtualizarContaReceber = false;
					
					if($lin[DescricaoEndereco] != $_POST['DescricaoEndereco_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Descrição Endereço [$lin[DescricaoEndereco] > ".$_POST['DescricaoEndereco_'.$i]."]";
					}
					
					if($lin[CEP] != $_POST['CEP_'.$i]){
						$AtualizarContaReceber = true;
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de CEP [$lin[CEP] > ".$_POST['CEP_'.$i]."]";
					}
					
					if($lin[Endereco] != $_POST['Endereco_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Endereço [$lin[Endereco] > ".$_POST['Endereco_'.$i]."]";
					}
					
					if($lin[Numero] != $_POST['Numero_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Nº [$lin[Numero] > ".$_POST['Numero_'.$i]."]";
					}
					
					if($lin[Complemento] != $_POST['Complemento_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Complemento [$lin[Complemento] > ".$_POST['Complemento_'.$i]."]";
					}
					
					if($lin[Bairro] != $_POST['Bairro_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Bairro [$lin[Bairro] > ".$_POST['Bairro_'.$i]."]";
					}
					
					if($lin[IdPais] != $local_IdPais){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de País [$lin[IdPais] > $local_IdPais]";
					}
					
					if($lin[IdEstado] != $local_IdEstado){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Estado [$lin[IdEstado] > $local_IdEstado]";
					}
					
					if($lin[IdCidade] != $local_IdCidade){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Cidade [$lin[IdCidade] > $local_IdCidade]";
					}

					if($lin[Telefone] != $_POST['Telefone_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Telefone [$lin[Telefone] > ".$_POST['Telefone_'.$i]."]";
					}

					if($lin[Celular] != $_POST['Celular_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Celular [$lin[Celular] > ".$_POST['Celular_'.$i]."]";
					}

					if($lin[Fax] != $_POST['Fax_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Fax [$lin[Fax] > ".$_POST['Fax_'.$i]."]";
					}

					if($lin[ComplementoTelefone] != $_POST['ComplementoTelefone_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Complemento Telefone [$lin[ComplementoTelefone] > ".$_POST['ComplementoTelefone_'.$i]."]";
					}

					if($lin[EmailEndereco] != $_POST['EmailEndereco_'.$i]){
						if($Obs != '') { $Obs .= "\n"; }
						
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Email Endereço [$lin[EmailEndereco] > ".$_POST['EmailEndereco_'.$i]."]";
					}					
					
					$_POST['Complemento_'.$i] = str_replace('"','\"',$_POST['Complemento_'.$i]);
					
					$sql	=	"UPDATE PessoaEndereco SET 
									DescricaoEndereco		='".$_POST['DescricaoEndereco_'.$i]."',
									NomeResponsavelEndereco	='".$_POST['NomeResponsavelEndereco_'.$i]."',
									IdPais					= $local_IdPais, 
									IdEstado				= $local_IdEstado, 
									IdCidade				= $local_IdCidade, 
									CEP						='".$_POST['CEP_'.$i]."', 
									Endereco				=\"".$_POST['Endereco_'.$i]."\", 
									Complemento				=\"".$_POST['Complemento_'.$i]."\",
									Numero					='".$_POST['Numero_'.$i]."', 
									Bairro					='".$_POST['Bairro_'.$i]."', 
									Telefone				='".$_POST['Telefone_'.$i]."',
									Celular					='".$_POST['Celular_'.$i]."', 
									Fax						='".$_POST['Fax_'.$i]."', 
									ComplementoTelefone		='".$_POST['ComplementoTelefone_'.$i]."',
									EmailEndereco			='".$_POST['EmailEndereco_'.$i]."',
									Latitude				=NULL, 
									Longitude				=NULL, 
									TentativaMapeamento		='0'
								WHERE
									IdPessoa				=$local_IdPessoa and 
									IdPessoaEndereco		=$local_IdPessoaEndereco;";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
					if($local_IdEnderecoDefault == $local_IdPessoaEndereco){
						$sql	=	"UPDATE Pessoa SET IdEnderecoDefault =  $local_IdPessoaEndereco	WHERE IdPessoa	= $local_IdPessoa";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}
					
					if($AtualizarContaReceber){
						$sqlContaReceber = "SELECT
												ContaReceber.IdContaReceber,
												ContaReceber.IdPessoa,
												ContaReceber.IdPessoaEndereco,
												ContaReceber.`IdLocalCobranca`
											FROM
												LocalCobranca,
												ContaReceber
											WHERE
												ContaReceber.IdLoja = $local_IdLoja AND
												LocalCobranca.IdLoja = ContaReceber.IdLoja AND
												LocalCobranca.`IdLocalCobranca` = ContaReceber.`IdLocalCobranca` AND
												LocalCobranca.IdTipoLocalCobranca IN (3,4,6) AND
												ContaReceber.`IdStatus` = 6 AND
												ContaReceber.IdPessoa = $local_IdPessoa AND 
												ContaReceber.`IdPessoaEndereco` = $local_IdPessoaEndereco";
						
						$resContaReceber = mysql_query($sqlContaReceber);
						while($dadosContaReceber = mysql_fetch_array($resContaReceber)){
							$sqlContaReceber2 = "Update ContaReceber set
													IdStatus = 3 
												where
													IdContaReceber =  $dadosContaReceber[IdContaReceber]";
													
							$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber2,$con);
							$tr_i++;
							
							$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $dadosContaReceber[IdContaReceber], 9, $local_Login);
							$tr_i++;
						}
					}
				}else{
					if($_POST['NomeResponsavelEndereco_'.$i]!='' || $_POST['CEP_'.$i]!='' || $_POST['Endereco_'.$i]!='' || $_POST['Numero_'.$i]!='' || $_POST['Complemento_'.$i]!='' || $_POST['Bairro_'.$i]!='' ||$_POST['IdPais_'.$i]!='' || $_POST['IdEstado_'.$i]!='' || $_POST['IdCidade_'.$i]!='' || $_POST['Telefone_'.$i]!='' || $_POST['Celular_'.$i]!="" || $_POST['Fax_'.$i]!="" || $_POST['ComplementoTelefone_'.$i]!="" || $_POST['EmailEndereco_'.$i]!=""){
						$sql2	=	"select (max(IdPessoaEndereco)+1) IdPessoaEndereco from PessoaEndereco where IdPessoa = $local_IdPessoa";
						$res2	=	mysql_query($sql2,$con);
						$lin2	=	@mysql_fetch_array($res2);
					
						if($lin2[IdPessoaEndereco]!=NULL){
							$local_IdPessoaEndereco	=	$lin2[IdPessoaEndereco];
						}else{
							$local_IdPessoaEndereco	=	1;
						}
						
						$local_IdPais	=	$_POST['IdPais_'.$i];
						$local_IdEstado	=	$_POST['IdEstado_'.$i];
						$local_IdCidade	=	$_POST['IdCidade_'.$i];
						
						if($local_IdPais=="") 	$local_IdPais	=	'NULL';
						if($local_IdEstado=="") $local_IdEstado	=	'NULL';
						if($local_IdCidade=="") $local_IdCidade	=	'NULL';
						
						$sql	=	"INSERT INTO PessoaEndereco SET 
										IdPessoa				=$local_IdPessoa, 
										IdPessoaEndereco		=$local_IdPessoaEndereco, 
										DescricaoEndereco		='".$_POST['DescricaoEndereco_'.$i]."',
										NomeResponsavelEndereco	='".$_POST['NomeResponsavelEndereco_'.$i]."',
										IdPais					= $local_IdPais, 
										IdEstado				= $local_IdEstado, 
										IdCidade				= $local_IdCidade, 
										CEP						='".$_POST['CEP_'.$i]."', 
										Endereco				=\"".$_POST['Endereco_'.$i]."\", 
										Complemento				=\"".$_POST['Complemento_'.$i]."\",
										Numero					='".$_POST['Numero_'.$i]."', 
										Bairro					='".$_POST['Bairro_'.$i]."', 
										Telefone				='".$_POST['Telefone_'.$i]."',
										Celular					='".$_POST['Celular_'.$i]."', 
										Fax						='".$_POST['Fax_'.$i]."', 
										ComplementoTelefone		='".$_POST['ComplementoTelefone_'.$i]."',
										EmailEndereco			='".$_POST['EmailEndereco_'.$i]."';";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
						
						if($local_IdEnderecoDefault == $i){
							$sql	=	"UPDATE Pessoa SET IdEnderecoDefault =  $local_IdPessoaEndereco	WHERE IdPessoa	= $local_IdPessoa";
							$local_transaction[$tr_i]	=	mysql_query($sql,$con);
							$tr_i++;
						}

						if($Obs != '') { $Obs .= "\n"; }
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Descrição Endereço - ".$_POST['DescricaoEndereco_'.$i]."]";
							
						if($Obs != '') { $Obs .= "\n"; }
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [CEP - ".$_POST['CEP_'.$i]."]";
						
						if($Obs != '') { $Obs .= "\n"; }
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Endereço - ".$_POST['Endereco_'.$i]."]";

						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Número - ".$_POST['Numero_'.$i]."]";

						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Complemento - ".$_POST['Complemento_'.$i]."]";

						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Bairro - ".$_POST['Bairro_'.$i]."]";
						
						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [IdPais - $local_IdPais]";
												
						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [IdEstado - $local_IdEstado]";
												
						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [IdCidade - $local_IdCidade]";						

						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Telefone - ".$_POST['Telefone_'.$i]."]";				

						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Celular - ".$_POST['Celular_'.$i]."]";				

						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Fax - ".$_POST['Fax_'.$i]."]";				

						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Complemento Telefone - ".$_POST['ComplementoTelefone_'.$i]."]";				

						if($Obs != '') { $Obs .= "\n"; }							
						$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Novo Adicionado [Email Endereço - ".$_POST['EmailEndereco_'.$i]."]";				
					}
				}
				$i++;
			}
			
			if($local_Obs!=""){
				if($Obs != '') { $Obs .= "\n"; }
				
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - ".trim($local_Obs);
			}
			
			$sql = "select 
						Obs
					from 
						Pessoa 
					where 
						IdPessoa = $local_IdPessoa;";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			if($lin[Obs]!=""){
				if($Obs!=""){
					$Obs .= "\n";
				}
				
				$Obs .= trim($lin[Obs]);
			}
			
			$QtdAspas = substr_count($Obs,"'"); // Busca a quantidade de aspas simples dentro da string
			
			if($QtdAspas%2 == 0){
				$Obs = str_replace("'",'"',$Obs);
			} else{
				$Obs = str_replace("'",'',$Obs);
			}
			
			$Obs = str_replace('"','\"',$Obs);
			
			$sql = "UPDATE Pessoa SET
						Obs = \"$Obs\"
					WHERE 
						IdPessoa = $local_IdPessoa;";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			$IdEcluirAnexos = explode(',', $local_EcluirAnexos);
			
			for($i = 1; $i < count($IdEcluirAnexos); $i++){
				$sql = "
					SELECT
						NomeOriginal
					FROM
						PessoaAnexo
					WHERE
						IdPessoa = '$local_IdPessoa' AND
						IdAnexo = '$IdEcluirAnexos[$i]';";
				$res = @mysql_query($sql,$con);
				if($lin = @mysql_fetch_array($res)){
					$ext = endArray(explode(".", $lin[NomeOriginal]));
					$url = "./anexos/pessoa/".$local_IdPessoa."/".$IdEcluirAnexos[$i].".".$ext;
					
					$sql = "
						DELETE FROM 
							PessoaAnexo 
						WHERE 
							IdPessoa = '$local_IdPessoa' AND
							IdAnexo = '$IdEcluirAnexos[$i]';";
					$local_transaction[$tr_i] = mysql_query($sql,$con);
					if($local_transaction[$tr_i] == true){
						@unlink($url);
					}
					
					$tr_i++;
				}
			}
			
			for($i = 1; $i <= $_POST["MaxUploads"]; $i++){
				if($_POST['fakeupload_'.$i] != '' && $_POST['DescricaoArquivo_'.$i] != ''){
					$sql = "
						SELECT 
							(MAX(IdAnexo)+1) IdAnexo
						FROM 
							PessoaAnexo
						WHERE 
							IdPessoa = '$local_IdPessoa';
					";
					$res = @mysql_query($sql,$con);
					$lin = @mysql_fetch_array($res);
					
					if($lin['IdAnexo'] == ''){
						$lin['IdAnexo'] = 1;
					}
					
					$local_NomeOriginal	= $_FILES['EndArquivo_'.$i]['name'];
					$local_ExtArquivo	= endArray(explode(".", $local_NomeOriginal));
					$local_MD5			= md5($local_IdPessoa.$lin[IdAnexo]);
					
					if(in_array(strtolower($local_ExtArquivo), $extensao_anexo)){
						$sql = "
							INSERT INTO
								PessoaAnexo
							SET
								IdPessoa		= '$local_IdPessoa',
								IdAnexo			= '$lin[IdAnexo]',
								DescricaoAnexo	= '".$_POST['DescricaoArquivo_'.$i]."',
								NomeOriginal	= '".$local_NomeOriginal."',
								MD5				= '$local_MD5',
								LoginCriacao	= '$local_Login', 
								DataCriacao		= (concat(curdate(),' ',curtime()));
						";
						$local_transaction[$tr_i] = @mysql_query($sql,$con);
						
						if($local_transaction[$tr_i]){
							if($local_ExtArquivo != ''){
								$local_ExtArquivo	= '.'.$local_ExtArquivo;
							}
							
							@mkdir("./anexos/pessoa/".$local_IdPessoa,0770);
							
							$EnderecoArquivo = "./anexos/pessoa/".$local_IdPessoa.'/'.$lin[IdAnexo].$local_ExtArquivo;
							
							if(!@copy($_FILES['EndArquivo_'.$i]['tmp_name'], $EnderecoArquivo)){
								@rmdir("./anexos/pessoa/".$local_IdPessoa);
								
								$local_transaction[$tr_i] = false;
							}
						}
					} else{
						$local_transaction[$tr_i] = false;
					}
					
					$tr_i++;
				}
			}
			
			$URLRotinasAlteracao = preg_split("/([\r\n]+)/", getCodigoInterno(56, 2));
			
			foreach($URLRotinasAlteracao as $URLRotinaAlteracao){
				if(!empty($URLRotinaAlteracao)){
					@include($URLRotinaAlteracao);
				}
			}
			
			for($i=0; $i<$tr_i; $i++){
				if($local_transaction[$i] == false){
					$local_transaction = false;				
				}
			}
			
			if($local_transaction == true){
				$sql = "COMMIT;";
				$local_Erro = 4;			
			}else{
				$sql = "ROLLBACK;";
				$local_Erro = 5;			
			}
			
			mysql_query($sql,$con);
		}else{
			$local_Erro 	= 26;			// Mensagem de CPF/CNPJ Duplicado
		}
	}
?>
