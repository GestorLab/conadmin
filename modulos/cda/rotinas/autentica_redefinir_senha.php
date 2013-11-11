<?
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");

	$Path	=   "../../../";
	include("../../../classes/envia_mensagem/envia_mensagem.php");
	
	$local_CPF_CNPJ			= $_POST['CPF_CNPJ'];
	$local_Email			= $_POST['Email'];
	$local_EnviaSenha		= $_POST['EnviaSenha'];
	$local_EnviaSenhaNova	= $_POST['EnviaSenhaNova'];
	$local_ValidarCPF_CNPJ	= $_POST['ValidarCPF_CNPJ'];
	$local_Login			= "cda";
	$local_Erro				= "../aviso_envio_senha_erro.php";
	
	$sql = "select distinct
				Pessoa.IdPessoa,
				Pessoa.Email,
				Pessoa.CPF_CNPJ
			from
				Pessoa,
				Contrato,
				ContratoParametro,
				ServicoParametro
			where 
				Pessoa.IdPessoa = Contrato.IdPessoa and
				Contrato.IdLoja = ContratoParametro.IdLoja and 
				Contrato.IdContrato = ContratoParametro.IdContrato and 
				Contrato.IdServico = ContratoParametro.IdServico and 
				ContratoParametro.IdLoja = ServicoParametro.IdLoja and 
				ContratoParametro.IdServico = ServicoParametro.IdServico and 
				ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and 
				(
					Pessoa.CPF_CNPJ = '".inserir_mascara($local_CPF_CNPJ)."' or
					(
						ServicoParametro.AcessoCDA = 1 and
						ContratoParametro.Valor = '$local_CPF_CNPJ'
					)
				);";
	$res = @mysql_query($sql, $con);
	$lin = @mysql_fetch_array($res);
	
	if($local_EnviaSenha == 2) {
		$local_LoginAceito = false;
		$temp = explode(";",$lin[Email]);
		
		if(in_array($local_Email,$temp)){
			$lin[Email] = $local_Email;
			$local_LoginAceito = true;
		}
	} else{
		$local_LoginAceito = $lin;
	}
	
	if($local_LoginAceito){
		if($lin[Email] != ''){
			if($local_EnviaSenhaNova == 1){
				$local_IdPessoa = $lin[IdPessoa];
				$local_IP = $_SERVER[REMOTE_ADDR];
				$sql_TMP = "SELECT 
							(MAX(IdSolicitacao) + 1) IdSolicitacao 
						FROM 
							SolicitacaoSenha;";
				$res_TMP = mysql_query($sql_TMP,$con);
				$lin_TMP = @mysql_fetch_array($res_TMP);
				
				if($lin_TMP[IdSolicitacao] != NULL){
					$local_IdSolicitacao = $lin_TMP[IdSolicitacao];
				} else{
					$local_IdSolicitacao = 1;
				}
				
				$sql_TMP = "INSERT INTO SolicitacaoSenha SET
							IdSolicitacao	= '$local_IdSolicitacao',
							Login			= '$local_Login',
							IP				= '$local_IP',
							IdPessoa		= '$local_IdPessoa',
							DataSolicitacao = (concat(curdate(),' ',curtime()));";
				if(mysql_query($sql_TMP, $con)){		
					enviarNovaSenhaCDA($lin[CPF_CNPJ]);	
					$local_Erro = "../aviso_envio_senha.php";
				}
			} else{
				enviarRedefinicaoSenhaCDA($lin[Email], $lin[CPF_CNPJ]);			
				$local_Erro = "../aviso_envio_senha.php";
			}
		}
	}
	
	header("Location: $local_Erro");
?>