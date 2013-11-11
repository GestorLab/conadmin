<?
	
	if($local_TipoPessoa == 2){ //Física
		$local_NomeRepresentante	=	'';
		$local_RazaoSocial			=	'';
		$local_InscricaoMunicipal	=	'';
	}else if($local_TipoPessoa == 1){ //Jurídica
		$local_Sexo					=	'';
		$local_EstadoCivil			=	'';
		$local_RG_IE				=	$local_InscricaoEstadual;
	}
	
	if($local_DataNascimento != ''){ 	
		$local_DataNascimento	= 	"'".dataConv($local_DataNascimento,'d/m/Y','Y-m-d')."'";
	}else{
		$local_DataNascimento	=  	'NULL';
	}
	
	$sql	=	"UPDATE Pessoa SET 				
					Nome					='$local_Nome', 
					NomeRepresentante		='$local_NomeRepresentante', 
					RazaoSocial				='$local_RazaoSocial', 
					DataNascimento			= $local_DataNascimento, 
					Sexo					='$local_Sexo', 
					IdPais					= $local_IdPais, 
					IdEstado				= $local_IdEstado, 
					IdCidade				= $local_IdCidade, 
					RG_IE					='$local_RG_IE', 
					EstadoCivil				='$local_EstadoCivil', 
					InscricaoMunicipal		='$local_InscricaoMunicipal', 
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
					LoginAlteracao			='$local_Login',
					DataAlteracao			= concat(curdate(),' ',curtime()),
					ForcarAtualizarDadosCDA	='2'
				WHERE
					IdPessoa				='$local_IdPessoa';";
	if(mysql_query($sql,$con) == true){
		$local_Erro = 4;		
	}else{
		$local_Erro = 5;
	}

?>
