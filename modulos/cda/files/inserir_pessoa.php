<?
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');	
	include('funcoes.php');	
	
	$IdLoja							= getParametroSistema(95,6);
	$local_TipoPessoa				= formatText($_POST['TipoPessoa'],NULL);
	$local_Nome						= formatText($_POST['Nome'],NULL);
	$local_NomeFantasia				= formatText($_POST['NomeFantasia'],NULL);
	$local_NomeRepresentante		= formatText($_POST['NomeRepresentante'],NULL);
	$local_Sexo						= formatText($_POST['Sexo'],NULL);
	$local_RazaoSocial				= formatText($_POST['RazaoSocial'],NULL);
	$local_DataNascimento			= formatText($_POST['DataNascimento'],NULL);
	$local_IdPais					= formatText($_POST['IdPais'],NULL);
	$local_IdEstado					= formatText($_POST['IdEstado'],NULL);
	$local_IdCidade					= formatText($_POST['IdCidade'],NULL);
	$local_CPF_CNPJ					= formatText($_POST['CPF_CNPJ'],NULL);
	$local_RG_IE					= formatText($_POST['RG_IE'],NULL);
	$local_EstadoCivil				= formatText($_POST['EstadoCivil'],NULL);
	$local_CEP						= formatText($_POST['CEP'],NULL);
	$local_InscricaoMunicipal		= formatText($_POST['InscricaoMunicipal'],NULL);
	$local_OrgaoExpedidor			= formatText($_POST['OrgaoExpedidor'],NULL);	
	$local_Endereco					= formatText($_POST['Endereco'],NULL);
	$local_Complemento				= formatText($_POST['Complemento'],NULL);
	$local_Numero					= formatText($_POST['Numero'],NULL);
	$local_Bairro					= formatText($_POST['Bairro'],NULL);
	$local_Telefone1				= formatText($_POST['Telefone1'],NULL);
	$local_Telefone2				= formatText($_POST['Telefone2'],NULL);
	$local_Telefone3				= formatText($_POST['Telefone3'],NULL);
	$local_Celular					= formatText($_POST['Celular'],NULL);
	$local_Fax						= formatText($_POST['Fax'],NULL);
	$local_ComplementoTelefone		= formatText($_POST['ComplementoTelefone'],NULL);
	$local_Email					= formatText($_POST['Email'],getParametroSistema(4,5));
	$local_Site						= formatText($_POST['Site'],getParametroSistema(4,6));
	$local_CampoExtra1				= formatText($_POST['CampoExtra1'],NULL);
	$local_CampoExtra2				= formatText($_POST['CampoExtra2'],NULL);
	$local_CampoExtra3				= formatText($_POST['CampoExtra3'],NULL);
	$local_CampoExtra4				= formatText($_POST['CampoExtra4'],NULL);
	$local_Senha					= formatText($_POST['Senha'],NULL);
	$local_Cob_CobrarDespesaBoleto	= getCodigoInternoCDA(3,26);
	$local_AgruparContratos			= getCodigoInternoCDA(3,7);
	$local_IdGrupoPessoa			= getCodigoInternoCDA(3,71);
	
	if(getCodigoInternoCDA(3,32) == '1'){	$local_Cob_FormaCorreio 	= 'S';	}
	if(getCodigoInternoCDA(3,33) == '1'){	$local_Cob_FormaEmail 		= 'S';	}
	if(getCodigoInternoCDA(3,34) == '1'){	$local_Cob_FormaOutro 		= 'S';	}
	
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	
	$tr_i	=	0;
	$sql	=	"select (max(IdPessoa)+1) IdPessoa from Pessoa";
	$res	=	mysql_query($sql,$con);
	$lin	=	@mysql_fetch_array($res);
	
	if($lin[IdPessoa]!=NULL){
		$local_IdPessoa	=	$lin[IdPessoa];
	}else{
		$local_IdPessoa	=	1;
	}
	
	if($local_DataNascimento != ""){
		$local_DataNascimento	=	"'".dataConv($local_DataNascimento,'d/m/Y','Y-m-d')."'";
	}else{
		$local_DataNascimento	=	'NULL';
	}
	
	$sql	=	"INSERT INTO Pessoa SET 
					IdPessoa				=$local_IdPessoa, 
					TipoPessoa				='$local_TipoPessoa', 
					Nome					='$local_Nome', 
					NomeRepresentante		='$local_NomeRepresentante', 
					RazaoSocial				='$local_RazaoSocial', 
					DataNascimento			= $local_DataNascimento, 
					Sexo					='$local_Sexo', 
					IdPais					= $local_IdPais, 
					IdEstado				= $local_IdEstado, 
					IdCidade				= $local_IdCidade, 
					RG_IE					='$local_RG_IE', 
					CPF_CNPJ				='$local_CPF_CNPJ', 
					EstadoCivil				='$local_EstadoCivil', 
					InscricaoMunicipal		='$local_InscricaoMunicipal', 
					OrgaoExpedidor			='$local_OrgaoExpedidor', 
					CEP						='$local_CEP', 
					Endereco				='$local_Endereco', 
					Complemento				='$local_Complemento',
					Numero					='$local_Numero', 
					Bairro					='$local_Bairro', 
					Telefone1				='$local_Telefone1',
					Telefone2				='$local_Telefone2', 
					Telefone3				='$local_Telefone3', 
					Celular					='$local_Celular', 
					Fax						='$local_Fax', 
					ComplementoTelefone		='$local_ComplementoTelefone',
					Email					='$local_Email', 
					Site					='$local_Site', 
					CampoExtra1				='$local_CampoExtra1',
					CampoExtra2				='$local_CampoExtra2',
					CampoExtra3				='$local_CampoExtra3',
					CampoExtra4				='$local_CampoExtra4',
					Senha					='$local_Senha',
					Cob_CobrarDespesaBoleto ='$local_Cob_CobrarDespesaBoleto',
					IdGrupoPessoa			='$local_IdGrupoPessoa',
					Cob_FormaCorreio		='$local_Cob_FormaCorreio',
					Cob_FormaEmail			='$local_Cob_FormaEmail',
					Cob_FormaOutro			='$local_Cob_FormaOutro',
					DataCriacao				=(concat(curdate(),' ',curtime())),
					LoginCriacao			='automatico';";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
	
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;				
		}
	}
	
	if($local_transaction == true){
		$sql = "COMMIT;";
		
		if(getParametroSistema(95,2) == 1){
			$local_header = "../rotinas/autentica.php?CPF_CNPJ=$local_CPF_CNPJ&Senha=$local_Senha";
		}else{
			$local_header = "../rotinas/autentica.php?CPF_CNPJ=$local_CPF_CNPJ";
		}
	}else{
		$sql = "ROLLBACK;";
		$local_header = "../cadastrar_pessoa.php?CPF_CNPJ=$local_CPF_CNPJ&Erro=21";
	}
	
	mysql_query($sql,$con);
	
	header("Location: $local_header");
?>
