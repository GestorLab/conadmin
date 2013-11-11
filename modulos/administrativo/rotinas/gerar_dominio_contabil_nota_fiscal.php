<?
	set_time_limit(0);

	include ('../../classes/zip.lib/zip.lib.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;			
		
		$sql = "UPDATE CodigoInterno SET
					ValorCodigoInterno		='$local_CodigoEmpresa',
					LoginAlteracao			='$local_Login',
					DataAlteracao			= concat(curdate(),' ',curtime())
				WHERE 
					IdLoja					= $local_IdLoja and
					IdGrupoCodigoInterno	= 36 and
					IdCodigoInterno			= 1";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "UPDATE CodigoInterno SET
					ValorCodigoInterno		='$local_CodigoAcumulador',
					LoginAlteracao			='$local_Login',
					DataAlteracao			= concat(curdate(),' ',curtime())
				WHERE 
					IdLoja					= $local_IdLoja and
					IdGrupoCodigoInterno	= 36 and
					IdCodigoInterno			= 2";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "UPDATE CodigoInterno SET
					ValorCodigoInterno		='$local_DoctoFinal',
					LoginAlteracao			='$local_Login',
					DataAlteracao			= concat(curdate(),' ',curtime())
				WHERE 
					IdLoja					= $local_IdLoja and
					IdGrupoCodigoInterno	= 36 and
					IdCodigoInterno			= 3";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
			
			
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";			
			
			$local_MesVencimento = dataConv($local_MesVencimento,'m/Y','Y-m');

			$PatchPeriodo = "remessa/dominio_contabil";
			@mkdir($PatchPeriodo);

			$PatchPeriodo .= "/$local_IdLoja";	
			@mkdir($PatchPeriodo);

			$PatchPeriodo .= "/$local_MesVencimento";
			@mkdir($PatchPeriodo);

			$zip = new zipfile;
			
			$sql = "select
						Pessoa.Nome,
						Pessoa.RazaoSocial,
						Pessoa.TipoPessoa,
						Pessoa.CPF_CNPJ
					from
						Loja,
						Pessoa
					where
						Loja.IdLoja = $local_IdLoja and
						Loja.IdPessoa = Pessoa.IdPessoa";
			$res = mysql_query($sql,$con);
			$DadosEmpresa = mysql_fetch_array($res);		
			
			$MesVencimentoAux =	$local_MesVencimento;
			
			# Gera o arquivo	
			include("dominio_contabil_nota_fiscal_layout_arquivo_unico.php");	

			$abre = fopen($FileName, "r" );
			$com  = fread($abre,filesize($FileName));
			@fclose($FileName);
			$zip->addFile($com,$FileName);  // Adiciona arquivos ao .zip
						
			# Gera os dois arquivos compactados
			$FileZip = $PatchPeriodo."/".$local_MesVencimento.".zip";
			$strzip=$zip->file();
			$abre = fopen($FileZip, "w");
			$salva = fwrite($abre, $strzip);
			fclose($abre);

			header("Location: $FileZip");

			/*# Gera o arquivo do Layout 3	
			include("dominio_contabil_nota_fiscal_layout_3.php");	

			$abre = fopen($FileName, "r" );
			$com  = fread($abre,filesize($FileName));
			@fclose($FileName);
			$zip->addFile($com,$FileName);  // Adiciona arquivos ao .zip
					
			# Gera o arquivo do Layout 19			
			include("dominio_contabil_nota_fiscal_layout_19.php");
			
			$abre = fopen($FileName, "r" );
			$com  = fread($abre,filesize($FileName));
			@fclose($FileName);
			$zip->addFile($com,$FileName);  // Adiciona arquivos ao .zip
			
			# Gera os dois arquivos compactados
			$FileZip = $PatchPeriodo."/".$local_MesVencimento.".zip";
			$strzip=$zip->file();
			$abre = fopen($FileZip, "w");
			$salva = fwrite($abre, $strzip);
			fclose($abre);*/

			header("Location: $FileZip");

		}else{
			$sql = "ROLLBACK;";		
			$local_Erro = 5;			// Mensagem de Inserção Negativa
		}
		mysql_query($sql,$con);						
	}	
?>
