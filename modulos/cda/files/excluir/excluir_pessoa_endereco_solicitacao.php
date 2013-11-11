<?	
	$localModulo		=	1;
	$localOperacao		=	1;
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../files/funcoes.php');	
	include('../../rotinas/verifica.php');
	
	$IdLoja					=	$_SESSION['IdLoja'];	
	$IdPessoa				=	$_GET['IdPessoa'];
	$IdPessoaEndereco		=	$_GET['IdPessoaEndereco'];
	$IdPessoaSolicitacao	=	$_GET['IdPessoaSolicitacao'];
	

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"select IdPessoaEndereco from PessoaSolicitacaoEndereco WHERE IdPessoa = '$IdPessoa' and IdPessoaEndereco=$IdPessoaEndereco and IdPessoaSolicitacao=$IdPessoaSolicitacao;";
		$res	=	@mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
		
		if($lin[IdPessoaEndereco]==""){
			echo $local_Erro = 42;
		}else{
			$sql	=	"DELETE FROM PessoaSolicitacaoEndereco WHERE IdPessoa = '$IdPessoa' and IdPessoaEndereco=$IdPessoaEndereco and IdPessoaSolicitacao=$IdPessoaSolicitacao;";
			if(mysql_query($sql,$con)==true){
				echo $local_Erro = 42;
			}else{
				echo $local_Erro = 43;
			}
		}
	}
?>
