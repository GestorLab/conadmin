<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{

		$sql=	"INSERT INTO NotaFiscal2ViaEletronicaArquivo SET 
					IdLoja					='$local_IdLoja',
					IdNotaFiscalLayout		='$local_IdNotaFiscalLayout',
					MesReferencia			='$local_MesReferencia',
					Status					='$local_IdStatusArquivoMestre', 
					StatusArquivoMestre		='$local_IdStatusArquivoMestre',
					LogProcessamento		= '',
					IdStatus				='1',
					DataCriacao				= (concat(curdate(),' ',curtime())),
					LoginCriacao			= '$local_Login';";
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'processar';
			$local_Erro = 3;		
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 1;		
		}	
	}
?>