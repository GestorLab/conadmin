<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	} else{
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sql = "UPDATE PessoaSolicitacao SET 
					IdStatus 		= '$local_IdStatus',
					LoginAprovacao	= '$local_Login',
					DataAprovacao	= concat(curdate(),' ',curtime())
				where 
					IdPessoa = $local_IdPessoa and
					IdPessoaSolicitacao = $local_IdPessoaSolicitacao";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		if($local_IdStatus == '2'){
			$sql = "select 
						Pessoa.TipoPessoa,
						Obs 
					from 
						PessoaSolicitacao,
						Pessoa 
					where 
						PessoaSolicitacao.IdPessoa = Pessoa.IdPessoa and 
						Pessoa.IdPessoa = $local_IdPessoa and 
						PessoaSolicitacao.IdPessoaSolicitacao = $local_IdPessoaSolicitacao";
			$res = mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			if($lin[TipoPessoa] == 2){ //Fнsica
				if($local_DataNascimento != ''){
					$local_DataNascimento = "'$local_DataNascimento'";
				} else{
					$local_DataNascimento = 'NULL';
				}
				
				$local_NomeRepresentante	= '';
				$local_RazaoSocial			= '';
				$local_InscricaoMunicipal	= '';
				
				$sqlSol = "	select 
								Sexo,
								EstadoCivil 
							from
								PessoaSolicitacao 
							where
								PessoaSolicitacao.IdPessoa = $local_IdPessoa and 
								PessoaSolicitacao.IdPessoaSolicitacao = $local_IdPessoaSolicitacao";
				$resSol = mysql_query($sqlSol,$con);
				$linSol = mysql_fetch_array($resSol);
				
				if($local_Sexo == ''){
					$local_Sexo = $linSol['Sexo'];
				}
				if($local_EstadoCivil == ''){
					$local_EstadoCivil = $linSol['EstadoCivil'];
				}
				
			} else if($lin[TipoPessoa] == 1){ //Jurнdica
				if($local_DataFundacao != ''){
					$local_DataNascimento	= "'$local_DataFundacao'";
				} else{
					$local_DataNascimento	= 'NULL';
				}
				
				$local_Nome			= $local_NomeFantasia;
				$local_Sexo			= '';
				$local_EstadoCivil	= '';
				$local_RG_IE		= $local_InscricaoEstadual;
			}
			$sqlSol2 = "select 
							Bairro 
						from
							PessoaSolicitacaoEndereco 
						where
							IdPessoa = $local_IdPessoa and
							IdPessoaSolicitacao = $local_IdPessoaSolicitacao";
			$resSol2 = mysql_query($sqlSol2,$con);
			$linSol2 = mysql_fetch_array($resSol2);
			
			if($lin[Obs] != ""){
				$lin[Obs] = "\n".trim($lin[Obs]);
			}
			
			if($local_Obs != ""){
				$local_Obs = date("d/m/Y H:i:s")." [".$local_Login."] - Dados atualizados solicitaзгo nє $local_IdPessoaSolicitacao";
			} else{
				$local_Obs = trim($lin[Obs]);
			}
			
			$local_Obs = str_replace("'",'"',$local_Obs);
			
			$sql = "update Pessoa set
						Nome				='$local_Nome', 
						NomeRepresentante	='$local_NomeRepresentante', 
						RazaoSocial			='$local_RazaoSocial', 
						DataNascimento		= $local_DataNascimento, 
						Sexo				='$local_Sexo', 
						RG_IE				='$local_RG_IE', 
						EstadoCivil			='$local_EstadoCivil', 
						InscricaoMunicipal	='$local_InscricaoMunicipal', 
						Telefone1			='$local_Telefone1',
						Telefone2			='$local_Telefone2', 
						Telefone3			='$local_Telefone3', 
						Celular				='$local_Celular', 
						Fax					='$local_Fax', 
						ComplementoTelefone	='$local_ComplementoTelefone',
						Email				='$local_Email', 
						Site				='$local_Site', 
						Obs					='$local_Obs', 
						CampoExtra1			='$local_CampoExtra1',
						CampoExtra2			='$local_CampoExtra2',
						CampoExtra3			='$local_CampoExtra3',
						CampoExtra4			='$local_CampoExtra4',
						LoginAlteracao		='$local_Login',
						DataAlteracao		= concat(curdate(),' ',curtime())
					where 
						IdPessoa ='$local_IdPessoa'";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			$i = 1;
			$where = '';
			
			while($i <= $local_QtdEndereco){
				$local_IdPessoaEndereco = $_POST['IdPessoaEndereco_'.$i];
				
				$sql3 = "select IdPessoaEndereco from PessoaEndereco where IdPessoa = $local_IdPessoa and IdPessoaEndereco=$local_IdPessoaEndereco";
				$res3 = @mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);
				
				if($lin3[IdPessoaEndereco]!=''){
					$local_IdPais	= $_POST['IdPais_'.$i];
					$local_IdEstado	= $_POST['IdEstado_'.$i];
					$local_IdCidade	= $_POST['IdCidade_'.$i];
					
					if($local_IdPais==""){
						$local_IdPais = 'NULL';
					}
					
					if($local_IdEstado==""){
						$local_IdEstado = 'NULL';
					}
					
					if($local_IdCidade==""){
						$local_IdCidade = 'NULL';
					}
					if($local_QtdEndereco == 1){
						$local_Bairro = " ".$linSol2['Bairro'];
					}else{
						$local_Bairro = "".$_POST['Bairro_'.$i]."";
					}
					
					$sql = "update PessoaEndereco set 
								DescricaoEndereco		='".$_POST['DescricaoEndereco_'.$i]."',
								NomeResponsavelEndereco	='".$_POST['NomeResponsavelEndereco_'.$i]."',
								IdPais					= $local_IdPais, 
								IdEstado				= $local_IdEstado, 
								IdCidade				= $local_IdCidade, 
								CEP						='".$_POST['CEP_'.$i]."', 
								Endereco				='".$_POST['Endereco_'.$i]."', 
								Complemento				='".$_POST['Complemento_'.$i]."',
								Numero					='".$_POST['Numero_'.$i]."', 
								Bairro					='$local_Bairro', 
								Telefone				='".$_POST['Telefone_'.$i]."',
								Celular					='".$_POST['Celular_'.$i]."', 
								Fax						='".$_POST['Fax_'.$i]."', 
								ComplementoTelefone		='".$_POST['ComplementoTelefone_'.$i]."',
								EmailEndereco			='".$_POST['EmailEndereco_'.$i]."',
								Latitude				=NULL, 
								Longitude				=NULL, 
								TentativaMapeamento		='0'
							where
								IdPessoa = $local_IdPessoa and 
								IdPessoaEndereco = $local_IdPessoaEndereco;";
					$local_transaction[$tr_i] = mysql_query($sql,$con);
					$tr_i++;
					
					$where .= " and IdPessoaEndereco != $local_IdPessoaEndereco";
					
					if($local_IdEnderecoDefault == $local_IdPessoaEndereco){
						$sql = "update Pessoa set IdEnderecoDefault = $local_IdPessoaEndereco where IdPessoa = $local_IdPessoa";
						$local_transaction[$tr_i] = mysql_query($sql,$con);
						$tr_i++;
					}
					
					if($local_IdEnderecoCobrancaDefault == $local_IdPessoaEndereco){
						$sql = "update Pessoa set IdEnderecoCobrancaDefault = $local_IdPessoaEndereco where IdPessoa = $local_IdPessoa";
						$local_transaction[$tr_i] = mysql_query($sql,$con);
						$tr_i++;
					}
				} else{
					if($_POST['NomeResponsavelEndereco_'.$i]!='' || $_POST['CEP_'.$i]!='' || $_POST['Endereco_'.$i]!='' || $_POST['Numero_'.$i]!='' || $_POST['Complemento_'.$i]!='' || $_POST['Bairro_'.$i]!='' ||$_POST['IdPais_'.$i]!='' || $_POST['IdEstado_'.$i]!='' || $_POST['IdCidade_'.$i]!='' || $_POST['Telefone_'.$i]!='' || $_POST['Celular_'.$i]!="" || $_POST['Fax_'.$i]!="" || $_POST['ComplementoTelefone_'.$i]!="" || $_POST['EmailEndereco_'.$i]!=""){
						$sql2 = "select (max(IdPessoaEndereco)+1) IdPessoaEndereco from PessoaEndereco where IdPessoa = $local_IdPessoa";
						$res2 = mysql_query($sql2,$con);
						$lin2 = @mysql_fetch_array($res2);
						
						if($lin2[IdPessoaEndereco] != NULL){
							$local_IdPessoaEndereco = $lin2[IdPessoaEndereco];
						} else{
							$local_IdPessoaEndereco = 1;
						}
						
						$local_IdPais	= $_POST['IdPais_'.$i];
						$local_IdEstado	= $_POST['IdEstado_'.$i];
						$local_IdCidade	= $_POST['IdCidade_'.$i];
						
						if($local_IdPais == ""){
							$local_IdPais = 'NULL';
						}
						
						if($local_IdEstado == ""){
							$local_IdEstado = 'NULL';
						}
						
						if($local_IdCidade == ""){
							$local_IdCidade = 'NULL';
						}
						
						$sql = "insert into PessoaEndereco set 
									IdPessoa				=$local_IdPessoa, 
									IdPessoaEndereco		=$local_IdPessoaEndereco, 
									DescricaoEndereco		='".$_POST['DescricaoEndereco_'.$i]."',
									NomeResponsavelEndereco	='".$_POST['NomeResponsavelEndereco_'.$i]."',
									IdPais					= $local_IdPais, 
									IdEstado				= $local_IdEstado, 
									IdCidade				= $local_IdCidade, 
									CEP						='".$_POST['CEP_'.$i]."', 
									Endereco				='".$_POST['Endereco_'.$i]."', 
									Complemento				='".$_POST['Complemento_'.$i]."',
									Numero					='".$_POST['Numero_'.$i]."', 
									Bairro					='".$_POST['Bairro_'.$i]."', 
									Telefone				='".$_POST['Telefone_'.$i]."',
									Celular					='".$_POST['Celular_'.$i]."', 
									Fax						='".$_POST['Fax_'.$i]."', 
									ComplementoTelefone		='".$_POST['ComplementoTelefone_'.$i]."',
									EmailEndereco			='".$_POST['EmailEndereco_'.$i]."';";
						$local_transaction[$tr_i] = mysql_query($sql,$con);
						$tr_i++;
						
						$where .= " and IdPessoaEndereco != $local_IdPessoaEndereco";
						
						if($local_IdEnderecoDefault == $i){
							$sql = "update Pessoa set IdEnderecoDefault =  $local_IdPessoaEndereco where IdPessoa = $local_IdPessoa";
							$local_transaction[$tr_i] = mysql_query($sql,$con);
							$tr_i++;
						}
						
						if($local_IdEnderecoCobrancaDefault == $i){
							$sql = "update Pessoa set IdEnderecoCobrancaDefault = $local_IdPessoaEndereco where IdPessoa = $local_IdPessoa";
							$local_transaction[$tr_i] = mysql_query($sql,$con);
							$tr_i++;
						}
					}
				}
				
				$i++;
			}
			
			$sql = "delete from PessoaEndereco where IdPessoa = '$local_IdPessoa' $where";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			
			if($local_IdStatus == '2'){
				$local_Erro = 4;
			} else{
				$local_Erro = 119;
			}
		} else{
			$sql = "ROLLBACK;";
			
			if($local_IdStatus == '2'){
				$local_Erro = 5;			// Mensagem de Ediзгo Negativa
			} else{
				$local_Erro = 120;
			}
		}
		
		@mysql_query($sql,$con);
		
		if($local_Erro == 4){
			$sql = "select IdPessoaSolicitacao from PessoaSolicitacao where IdStatus = '1' limit 0,1";
			$res = mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			if($lin[IdPessoaSolicitacao] != ""){
				header("Location: listar_pessoa_solicitacao.php?IdStatus=1&Erro=$local_Erro");
			} else{
				if($local_Erro != 120){
					header("Location: conteudo.php");
				}
			}
		}
	}
?>