<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false && $local != "cda"){
		$local_Erro = 2;
	}else{
		if($local_AtivarCadastroResumido != 1){
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
									IdLoja = $local_IdLoja";
				}else{
					$sql	=	"select 
									count(*) Qtd 
								from 
									Pessoa
								where 
									CPF_CNPJ='$local_CPF_CNPJ' and 
									CPF_CNPJ!=''";
				}
				$res	=	mysql_query($sql,$con);
				$lin	=	@mysql_fetch_array($res);
			}

			if(getCodigoInterno(11,4)==1){
				$lin[Qtd] = 0;
			}
			
			if($lin[Qtd] == 0){	
				// Sql de Inserção de Pessoa
				$sql2	=	"select (max(IdPessoa)+1) IdPessoa from Pessoa";
				$res2	=	mysql_query($sql2,$con);
				$lin2	=	@mysql_fetch_array($res2);
				
				if($lin2[IdPessoa]!=NULL){
					$local_IdPessoa	=	$lin2[IdPessoa];
				}else{
					$local_IdPessoa	=	1;
				}
				
				if($local_TipoPessoa == 2){ //Física
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
					
				}else if($local_TipoPessoa == 1){ //Jurídica
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
				
				if($local_Cob_CobrarDespesaBoleto == ''){ if($local == 'cda'){$local_Cob_CobrarDespesaBoleto = "''";}else{$local_Cob_CobrarDespesaBoleto = 'NULL';} }else{	$local_Cob_CobrarDespesaBoleto	=	"'$local_Cob_CobrarDespesaBoleto'";		}
				if($local_Cob_IdPais == ''){				$local_Cob_IdPais = 'NULL';					}else{	$local_Cob_IdPais				=	"'$local_Cob_IdPais'";		}
				if($local_Cob_IdEstado == ''){				$local_Cob_IdEstado = 'NULL';				}else{	$local_Cob_IdEstado				=	"'$local_Cob_IdEstado'";	}
				if($local_Cob_IdCidade == ''){				$local_Cob_IdCidade = 'NULL';				}else{	$local_Cob_IdCidade				=	"'$local_Cob_IdCidade'";	}
				
				if($local_TipoUsuario == ''){				$local_TipoUsuario 			= 'NULL';		}
				if($local_TipoAgenteAutorizado == ''){		$local_TipoAgenteAutorizado = 'NULL';		}
				if($local_TipoFornecedor == ''){			$local_TipoFornecedor 		= 'NULL';		}
				if($local_TipoVendedor == ''){				$local_TipoVendedor 		= 'NULL';		}
				
				$sql	=	"START TRANSACTION;";
				mysql_query($sql,$con);
			
				$tr_i = 0;
				
				$local_Obs = str_replace('"','\"',$local_Obs);
				
				$sql	=	"INSERT INTO Pessoa SET 
								IdPessoa					= $local_IdPessoa, 
								TipoPessoa					='$local_TipoPessoa', 
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
								Obs							=\"$local_Obs\", 
								Senha						='$local_Senha', 
								AgruparContratos			= $local_AgruparContratos,
								ForcarAtualizarDadosCDA		= '$local_ForcarAtualizar',
								Cob_CobrarDespesaBoleto 	= $local_Cob_CobrarDespesaBoleto,
								MonitorFinanceiro			= $local_IdMonitorFinanceiro,
								Cob_FormaEmail				='$local_Cob_FormaEmail',
								Cob_FormaCorreio			='$local_Cob_FormaCorreio',
								Cob_FormaOutro				='$local_Cob_FormaOutro',
								TipoUsuario					= $local_TipoUsuario,
								TipoAgenteAutorizado		= $local_TipoAgenteAutorizado,
								TipoFornecedor				= $local_TipoFornecedor,
								TipoVendedor				= $local_TipoVendedor,
								CampoExtra1					='$local_CampoExtra1',
								CampoExtra2					='$local_CampoExtra2',
								CampoExtra3					='$local_CampoExtra3',
								CampoExtra4					='$local_CampoExtra4',
								NomePai						= $local_NomePai,
								NomeMae						= $local_NomeMae,
								NomeConjugue				= '$local_NomeConjugue',
								OrgaoExpedidor				= '$local_OrgaoExpedidor',
								IdLoja						= $local_IdLoja,
								LoginCriacao				='$local_Login', 
								DataCriacao					=(concat(curdate(),' ',curtime()))";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				
				$sql = "INSERT INTO PessoaGrupoPessoa SET
								IdGrupoPessoa	= $local_IdGrupoPessoa,
								IdLoja			= $local_IdLoja,
								IdPessoa		= $local_IdPessoa";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
				
				$i	=	1;
				while($i <= $local_QtdEndereco){				
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
						
						$_POST['Complemento_'.$i] = str_replace('"','\"',$_POST['Complemento_'.$i]);
						
						$sql	=	"INSERT INTO PessoaEndereco SET 
										IdPessoa				= $local_IdPessoa,
										IdPessoaEndereco		= $local_IdPessoaEndereco,
										DescricaoEndereco		='".$_POST['DescricaoEndereco_'.$i]."',
										NomeResponsavelEndereco	='".$_POST['NomeResponsavelEndereco_'.$i]."',
										IdPais					= $local_IdPais,
										IdEstado				= $local_IdEstado,
										IdCidade				= $local_IdCidade,
										CEP						='".$_POST['CEP_'.$i]."', 
										Endereco				='".$_POST['Endereco_'.$i]."', 
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
						
						switch($local_IdPessoaEndereco){
							case 1:
								// Altera os dois						
								$sql	=	"UPDATE Pessoa SET IdEnderecoDefault = $local_IdPessoaEndereco WHERE IdPessoa = $local_IdPessoa";
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);
								$tr_i++;
								break;
						}
					}
					
					$i++;
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
						$local_ExtArquivo	= endArray(explode(".",$local_NomeOriginal));
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
				
				$URLRotinasCriacao = preg_split("/([\r\n]+)/", getCodigoInterno(56, 1));
				
				foreach($URLRotinasCriacao as $URLRotinaCriacao){
					if(!empty($URLRotinaCriacao)){
						@include($URLRotinaCriacao);
					}
				}
				
				for($i=0; $i<$tr_i; $i++){
					if($local_transaction[$i] == false){
						$local_transaction = false;
					}
				}
				
				if($local_transaction == true){
					$sql = "COMMIT;";				
					mysql_query($sql,$con);
						enviarEmailBoasVindas($local_IdLoja, $local_Email, $local_IdPessoa);
					// Muda a ação para Inserir
					$local_Acao = 'alterar';
					$local_Erro = 3;			// Mensagem de Inserção Positiva
				}else{
					$sql = "ROLLBACK;";				
					mysql_query($sql,$con);

					// Muda a ação para Inserir
					$local_Acao = 'inserir';
					$local_Erro = 1;			// Mensagem de Inserção Negativa			
				}
			}else{
				// Muda a ação para Inserir
				$local_IdPessoa = "";
				$local_Acao 	= 'inserir';
				$local_Erro 	= 26;			// Mensagem de CPF/CNPJ Duplicado
			}
		}else{
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
									IdLoja = $local_IdLoja";
				}else{
					$sql	=	"select 
									count(*) Qtd 
								from 
									Pessoa
								where 
									CPF_CNPJ='$local_CPF_CNPJ' and 
									CPF_CNPJ!=''";
				}
				$res	=	mysql_query($sql,$con);
				$lin	=	@mysql_fetch_array($res);
			}

			if(getCodigoInterno(11,4)==1){
				$lin[Qtd] = 0;
			}
			
			if($lin[Qtd] == 0){	
			
				$sql2	=	"select (max(IdPessoa)+1) IdPessoa from Pessoa";
				$res2	=	mysql_query($sql2,$con);
				$lin2	=	@mysql_fetch_array($res2);
				
				if($lin2[IdPessoa]!=NULL){
					$local_IdPessoa	=	$lin2[IdPessoa];
				}else{
					$local_IdPessoa	=	1;
				}
				
				if($local_Nome_Resumido == ""){
					$local_Nome_Resumido = $local_NomeFantasia_Resumido; 
				}
				
				$sql	=	"INSERT INTO Pessoa SET 
									IdPessoa					= $local_IdPessoa, 
									TipoPessoa					='$local_TipoPessoa', 
									Nome						='$local_Nome_Resumido',
									RazaoSocial					='$local_RazaoSocial_Resumido',
									CPF_CNPJ					='$local_CPF_CNPJ',
									Telefone1					='$local_Telefone1_Resumido',
									Telefone2					='$local_Telefone2_Resumido', 
									Telefone3					='$local_Telefone3_Resumido', 
									Celular						='$local_Celular_Resumido', 
									Fax							='$local_Fax_Resumido', 
									ComplementoTelefone			='$local_ComplementoTelefone_Resumido',
									Email						='$local_Email_Resumido',
									Obs							='$local_Obs', 
									Cob_CobrarDespesaBoleto 	= 1,
									MonitorFinanceiro			= 1,
									Pessoa.IdEnderecoDefault	= 1,
									IdLoja						= $local_IdLoja,
									Resumido					= 1,
									LoginCriacao				='$local_Login', 
									DataCriacao					=(concat(curdate(),' ',curtime()))";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				
				$sql	=	"INSERT INTO PessoaEndereco SET 
										IdPessoa				= $local_IdPessoa, 
										IdPessoaEndereco		= 1,
										DescricaoEndereco		= 'Endereço Principal',
										IdPais					= $local_IdPais_Resumido, 
										IdEstado				= $local_IdEstado_Resumido, 
										IdCidade				= $local_IdCidade_Resumido";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				
				$sql = "INSERT INTO PessoaGrupoPessoa SET
								IdGrupoPessoa	= $local_IdGrupoPessoa_Resumido,
								IdLoja			= $local_IdLoja,
								IdPessoa		= $local_IdPessoa";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
				
				
				if($local_transaction == true){
					$sql = "COMMIT;";				
					mysql_query($sql,$con);

					if($local_Email != ''){						
						enviarEmailBoasVindas($local_IdLoja, $local_Email, $local_IdPessoa);
					}
					
					// Muda a ação para Inserir
					$local_Acao = 'alterar';
					$local_Erro = 3;			// Mensagem de Inserção Positiva
				}else{
					$sql = "ROLLBACK;";				
					mysql_query($sql,$con);

					// Muda a ação para Inserir
					$local_Acao = 'inserir';
					$local_Erro = 1;			// Mensagem de Inserção Negativa			
				}
			}else{
				// Muda a ação para Inserir
				$local_IdPessoa = "";
				$local_Acao 	= 'inserir';
				$local_Erro 	= 26;			// Mensagem de CPF/CNPJ Duplicado
			}
			
		}
	}
?>
