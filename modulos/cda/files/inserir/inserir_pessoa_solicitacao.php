<?
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../files/funcoes.php');	
	include('../../rotinas/verifica.php');
	
	$local_IdLoja				= $_SESSION["IdLojaCDA"];
	$local_IdPessoa				= $_SESSION["IdPessoaCDA"];
	$local_IdLogAcesso			= $_SESSION["IdLogAcessoCDA"];
	$local_IdParametroSistema	= $_POST['IdParametroSistema'];
	$local_Nome					= formatText($_POST['Nome'],NULL);
	$local_NomeFantasia			= formatText($_POST['NomeFantasia'],NULL);
	$local_NomeRepresentante	= formatText($_POST['NomeRepresentante'],NULL);
	$local_RazaoSocial			= formatText($_POST['RazaoSocial'],NULL);
	$local_DataNascimento		= formatText($_POST['DataNascimento'],NULL);
	$local_Sexo					= formatText($_POST['Sexo'],NULL);
	$local_RG_IE				= formatText($_POST['RG_IE'],NULL);
	$local_EstadoCivil			= formatText($_POST['EstadoCivil'],NULL);
	$local_InscricaoMunicipal	= formatText($_POST['InscricaoMunicipal'],NULL);
	$local_Telefone1			= formatText($_POST['Telefone1'],NULL);
	$local_Telefone2			= formatText($_POST['Telefone2'],NULL);
	$local_Telefone3			= formatText($_POST['Telefone3'],NULL);
	$local_Celular				= formatText($_POST['Celular'],NULL);
	$local_Fax					= formatText($_POST['Fax'],NULL);
	$local_ComplementoTelefone	= formatText($_POST['ComplementoTelefone'],NULL);
	$local_Email				= formatText($_POST['Email'],getParametroSistema(4,5));
	$local_Site					= formatText($_POST['Site'],getParametroSistema(4,6));
	$local_CampoExtra1			= formatText($_POST['CampoExtra1'],NULL);
	$local_CampoExtra2			= formatText($_POST['CampoExtra2'],NULL);
	$local_CampoExtra3			= formatText($_POST['CampoExtra3'],NULL);
	$local_CampoExtra4			= formatText($_POST['CampoExtra4'],NULL);
	$local_IdEnderecoDefault	= formatText($_POST['IdEnderecoDefault'],NULL);
	$local_QtdEndereco			= formatText($_POST['QtdEndereco'],NULL);
	$local_NomeConjugue			= formatText($_POST['NomeConjugue'],NULL);
	$local_OrgaoExpedidor		= formatText($_POST['OrgaoExpedidor'],NULL);
	
	$sql = "select CPF_CNPJ from Pessoa where IdPessoa = $local_IdPessoa";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
	
	$local_CPF_CNPJ = $lin['CPF_CNPJ'];
	
	$sql = "START TRANSACTION;";
	mysql_query($sql,$con);
	
	$tr_i = 0;
	
	$sql = "update PessoaSolicitacao set 
				IdStatus = '0'
			where
				IdPessoa = $local_IdPessoa and
				IdStatus = '1';";
	$local_transaction[$tr_i] = mysql_query($sql,$con);
	$tr_i++;
	
	$sql = "select (max(IdPessoaSolicitacao)+1) IdPessoaSolicitacao from PessoaSolicitacao";
	$res = mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);
	
	if($lin[IdPessoaSolicitacao] != NULL){
		$local_IdPessoaSolicitacao = $lin[IdPessoaSolicitacao];
	} else{
		$local_IdPessoaSolicitacao = 1;
	}
	
	if($local_DataNascimento != ""){
		$local_DataNascimento = "'".dataConv($local_DataNascimento,'d/m/Y','Y-m-d')."'";
	} else{
		$local_DataNascimento = 'NULL';
	}
	
	$sql = "update Pessoa set 
				ForcarAtualizarDadosCDA = 2
			where
				IdPessoa = $local_IdPessoa";
	$local_transaction[$tr_i] = mysql_query($sql,$con);
	$tr_i++;
	
	$sql = "insert into PessoaSolicitacao set 
				IdPessoaSolicitacao	= $local_IdPessoaSolicitacao,
				IdPessoa			= $local_IdPessoa, 
				Nome				= '$local_Nome', 
				NomeRepresentante	= '$local_NomeRepresentante', 
				RazaoSocial			= '$local_RazaoSocial', 
				DataNascimento		= $local_DataNascimento, 
				Sexo				= '$local_Sexo', 
				RG_IE				= '$local_RG_IE', 
				EstadoCivil			= '$local_EstadoCivil', 
				InscricaoMunicipal	= '$local_InscricaoMunicipal', 
				Telefone1			= '$local_Telefone1',
				Telefone2			= '$local_Telefone2', 
				Telefone3			= '$local_Telefone3', 
				Celular				= '$local_Celular', 
				Fax					= '$local_Fax', 
				ComplementoTelefone	= '$local_ComplementoTelefone',
				Email				= '$local_Email', 
				Site				= '$local_Site', 
				CampoExtra1			= '$local_CampoExtra1',
				CampoExtra2			= '$local_CampoExtra2',
				CampoExtra3			= '$local_CampoExtra3',
				CampoExtra4			= '$local_CampoExtra4',
				IdStatus			= '1', 
				IdLogAcesso			= '$local_IdLogAcesso',
				IdEnderecoDefault	= '$local_IdEnderecoDefault',
				DataCriacao			= (concat(curdate(),' ',curtime())),
				LoginCriacao		= 'cda';";
	$local_transaction[$tr_i] = mysql_query($sql,$con);
	echo mysql_error();
	$tr_i++;
	
	while($i <= $local_QtdEndereco){
		if($_POST['IdPessoaEndereco_'.$i]!=''){
			$local_IdPessoaEndereco	= $_POST['IdPessoaEndereco_'.$i];
			$local_IdPais			= $_POST['IdPais_'.$i];
			$local_IdEstado			= $_POST['IdEstado_'.$i];
			$local_IdCidade			= $_POST['IdCidade_'.$i];
			
			if($local_IdPais == ""){
				$local_IdPais = 'NULL';
			}
			
			if($local_IdEstado == ""){
				$local_IdEstado = 'NULL';
			}
			
			if($local_IdCidade == ""){
				$local_IdCidade = 'NULL';
			}
			
			$sql = "insert into PessoaSolicitacaoEndereco set 
						IdPessoa				= $local_IdPessoa, 
						IdPessoaSolicitacao		= $local_IdPessoaSolicitacao, 
						IdPessoaEndereco		= $local_IdPessoaEndereco,
						DescricaoEndereco		= '".$_POST['DescricaoEndereco_'.$i]."',
						NomeResponsavelEndereco	= '".$_POST['NomeResponsavelEndereco_'.$i]."',
						IdPais					= $local_IdPais, 
						IdEstado				= $local_IdEstado, 
						IdCidade				= $local_IdCidade, 
						CEP						= '".$_POST['CEP_'.$i]."', 
						Endereco				= '".$_POST['Endereco_'.$i]."', 
						Complemento				= '".$_POST['Complemento_'.$i]."',
						Numero					= '".$_POST['Numero_'.$i]."', 
						Bairro					= '".$_POST['Bairro_'.$i]."', 
						Telefone				= '".$_POST['Telefone_'.$i]."',
						Celular					= '".$_POST['Celular_'.$i]."', 
						Fax						= '".$_POST['Fax_'.$i]."', 
						ComplementoTelefone		= '".$_POST['ComplementoTelefone_'.$i]."',
						EmailEndereco			= '".$_POST['EmailEndereco_'.$i]."';";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			if($local_IdEnderecoDefault == $local_IdPessoaEndereco){
				$sql = "update PessoaSolicitacao set 
							IdEnderecoDefault = $local_IdPessoaEndereco 
						where 
							IdPessoa = $local_IdPessoa and 
							IdPessoaSolicitacao = $local_IdPessoaSolicitacao";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}
		} else{
			if($_POST['NomeResponsavelEndereco_'.$i]!='' || $_POST['CEP_'.$i]!='' || $_POST['Endereco_'.$i]!='' || $_POST['Numero_'.$i]!='' || $_POST['Complemento_'.$i]!='' || $_POST['Bairro_'.$i]!='' ||$_POST['IdPais_'.$i]!='' || $_POST['IdEstado_'.$i]!='' || $_POST['IdCidade_'.$i]!='' || $_POST['Telefone_'.$i]!='' || $_POST['Celular_'.$i]!="" || $_POST['Fax_'.$i]!="" || $_POST['ComplementoTelefone_'.$i]!="" || $_POST['EmailEndereco_'.$i]!=""){
				$sql2 = "select (max(IdPessoaEndereco)+1) IdPessoaEndereco from PessoaSolicitacaoEndereco where IdPessoa = $local_IdPessoa";
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
				
				$sql = "insert into PessoaSolicitacaoEndereco set 
							IdPessoa				= $local_IdPessoa, 
							IdPessoaSolicitacao		= $local_IdPessoaSolicitacao,
							IdPessoaEndereco		= $local_IdPessoaEndereco, 
							DescricaoEndereco		= '".$_POST['DescricaoEndereco_'.$i]."',
							NomeResponsavelEndereco	= '".$_POST['NomeResponsavelEndereco_'.$i]."',
							IdPais					= $local_IdPais, 
							IdEstado				= $local_IdEstado, 
							IdCidade				= $local_IdCidade, 
							CEP						= '".$_POST['CEP_'.$i]."', 
							Endereco				= '".$_POST['Endereco_'.$i]."', 
							Complemento				= '".$_POST['Complemento_'.$i]."',
							Numero					= '".$_POST['Numero_'.$i]."', 
							Bairro					= '".$_POST['Bairro_'.$i]."', 
							Telefone				= '".$_POST['Telefone_'.$i]."',
							Celular					= '".$_POST['Celular_'.$i]."', 
							Fax						= '".$_POST['Fax_'.$i]."', 
							ComplementoTelefone		= '".$_POST['ComplementoTelefone_'.$i]."',
							EmailEndereco			= '".$_POST['EmailEndereco_'.$i]."';";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
				
				if($local_IdEnderecoDefault == $i){
					$sql = "update PessoaSolicitacao set 
								IdEnderecoDefault =  $local_IdPessoaEndereco 
							where 
								IdPessoa = $local_IdPessoa and 
								IdPessoaSolicitacao = $local_IdPessoaSolicitacao";
					$local_transaction[$tr_i] = mysql_query($sql,$con);
					$tr_i++;
				}
			}
		}
		
		$i++;
	}
	
	if(!in_array(false, $local_transaction)){
		$sql = "COMMIT;";
		$local_header = "../../menu.php?ctt=tela_aviso.php&IdParametroSistema=$local_IdParametroSistema&Erro=22";			// Mensagem de Alteraчуo Positiva
	} else{
		$sql = "ROLLBACK;";
		$local_header = "../../menu.php?ctt=tela_aviso.php&IdParametroSistema=$local_IdParametroSistema&Erro=21";			// Mensagem de Alteraчуo Negativa
	}
	
	mysql_query($sql,$con);
	header("Location: $local_header");
?>