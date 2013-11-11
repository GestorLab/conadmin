<?
	$localModulo		=	1;
	$localOperacao		=	31;
	
	$local_IdLoja		=	$_SESSION["IdLoja"];
	$local_IdContaEventual	=	$_GET['IdContaEventual'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');

	$local_Login = $_SESSION["Login"];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;	
	
		$sql	=	"delete from ContaEventualParcela where IdLoja = $local_IdLoja and IdContaEventual=$local_IdContaEventual;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	
		$sql	=	"delete from ContaEventual where IdLoja = $local_IdLoja and IdContaEventual=$local_IdContaEventual;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	
		$sql1 = "	select distinct 
						ContaReceberDados.IdLoja,
						ContaReceberDados.IdContaReceber,
						ContaReceberDados.IdStatus
					from
						LancamentoFinanceiroContaReceber,
						Pessoa left join (PessoaGrupoPessoa, GrupoPessoa) on (
						  Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and
						  PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and
						  PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						),
						LocalCobranca,
						LancamentoFinanceiro,
						ContaReceberDados 
					where
						ContaReceberDados.IdLoja = $local_IdLoja and
						ContaReceberDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
						ContaReceberDados.IdLoja = LancamentoFinanceiro.IdLoja and
						ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
						ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
						LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
						ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
						ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
						LancamentoFinanceiro.IdContaEventual = $local_IdContaEventual and
						ContaReceberDados.IdStatus != 7 and
						ContaReceberDados.IdStatus = 0 
					order by
						ContaReceberDados.IdContaReceber desc";
		$res1 = mysql_query($sql1,$con);
		while($lin1 = mysql_fetch_array($res1)){
			$sql	=	"delete from LancamentoFinanceiroContaReceber where IdLoja = $local_IdLoja and IdContaReceber=$lin1[IdContaReceber];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$sql	=	"delete from ContaReceberPosicaoCobranca where IdLoja = $local_IdLoja and IdContaReceber=$lin1[IdContaReceber];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$sql	=	"delete from ContaReceberVencimento where IdLoja = $local_IdLoja and IdContaReceber=$lin1[IdContaReceber];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$sql	=	"delete from ContaReceber where IdLoja = $local_IdLoja and IdContaReceber=$lin1[IdContaReceber];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		$sql2 = "select 
					Demonstrativo.IdLoja,
					Demonstrativo.IdContaReceber,
					Demonstrativo.IdLancamentoFinanceiro,
					Demonstrativo.IdProcessoFinanceiro,
					Demonstrativo.Tipo,
					Demonstrativo.Codigo,
					substr(Demonstrativo.Descricao, 1, 15) Descricao,
					Demonstrativo.Referencia,
					Demonstrativo.Valor,
					Demonstrativo.ValorDespesas,
					Demonstrativo.ValorDescontoAConceber,
					Demonstrativo.IdStatus 
				from
					Demonstrativo,
					LancamentoFinanceiro 
				where 
					Demonstrativo.IdLoja = $local_IdLoja and
					Demonstrativo.IdLoja = LancamentoFinanceiro.IdLoja and
					Demonstrativo.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
					Demonstrativo.Codigo = '$local_IdContaEventual' and
					Demonstrativo.IdStatus = '0'";
		$res2 = mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			$sql	=	"delete from LancamentoFinanceiro where IdLoja = $local_IdLoja and IdContaEventual=$lin2[Codigo];";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			echo $local_Erro = 7;
			$sql = "COMMIT;";
		}else{
			echo $local_Erro = 33;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
