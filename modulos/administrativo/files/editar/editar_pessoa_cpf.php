<?	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$ObrigatoriedadeCPF_CNPJ = getCodigoInterno(11,4);
		
		if($local_CPF_CNPJ != ''){
			// Sql Busca CPF_CNPJ
			$sql	=	"select count(*) Qtd from Pessoa where CPF_CNPJ='$local_CPF_CNPJ' and IdPessoa!=$local_IdPessoa";
			$res	=	mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
		}
		
		$DuplicadoCPF_CNPJ = 1;

		if($ObrigatoriedadeCPF_CNPJ == 2){
			if($lin[Qtd] >= 1){
				$DuplicadoCPF_CNPJ = 2;
			}		
		}
		
		if($DuplicadoCPF_CNPJ == 1){
		
			$sql	=	"select Obs,CPF_CNPJ,TipoPessoa from Pessoa where IdPessoa=$local_IdPessoa";
			$res	=	mysql_query($sql,$con);
			$lin	=	mysql_fetch_array($res);
			
			if($lin[Obs]!=""){
				$lin[Obs]	=	"\n".trim($lin[Obs]);
			}
			
			if($lin[CPF_CNPJ]==""){
				$lin[CPF_CNPJ]	=	"NULL";
			}
			
			if($lin[TipoPessoa]!=$local_TipoPessoa){
				$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 and IdParametroSistema = $lin[TipoPessoa]";
				$res2 = mysql_query($sql2,$con);
				$lin2 = mysql_fetch_array($res2);
				
				$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 and IdParametroSistema = $local_TipoPessoa";
				$res3 = mysql_query($sql3,$con);
				$lin3 = mysql_fetch_array($res3);
			
				if($local_Obs!="")	$local_Obs	.=	"\n";
			
				$local_Obs	=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Tipo [$lin2[ValorParametroSistema] > $lin3[ValorParametroSistema]]";
			}
			
			if($local_Obs!="")	$local_Obs	.=	"\n";
				
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de CPF/CNPJ [$lin[CPF_CNPJ] > $local_CPF_CNPJ]".trim($lin[Obs]);
			
			$sql	=	"UPDATE Pessoa SET
							TipoPessoa				='$local_TipoPessoa',
							CPF_CNPJ				='$local_CPF_CNPJ',
							Obs						='$local_Obs',
							DataAlteracao			= (concat(curdate(),' ',curtime())),
							LoginAlteracao			= '$local_Login'
						WHERE 
							IdPessoa				= $local_IdPessoa;";
			if(mysql_query($sql,$con) == true){
				$local_Erro = 4;			// Mensagem de Ediçao Positiva
				
				header("Location: cadastro_pessoa.php?IdPessoa=$local_IdPessoa&Erro=$local_Erro");
			}else{
				$local_Erro = 5;			// Mensagem de Edição Negativa
			}
		}else{
			$local_Erro 	= 26;			// Mensagem de CPF/CNPJ Duplicado
		}		
	}	
?>
