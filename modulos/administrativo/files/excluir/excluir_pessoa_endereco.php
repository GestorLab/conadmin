<?	
	$localModulo		=	1;
	$localOperacao		=	1;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$IdLoja					=	$_SESSION['IdLoja'];	
	$local_Login			=	$_SESSION['Login'];
	$IdPessoa				=	$_GET['IdPessoa'];
	$IdPessoaEndereco		=	$_GET['IdPessoaEndereco'];
	$Obs					=	'';



	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;

		$local_Erro = 0;
		
		$sql0 = "select 
					IdPessoaEndereco 
				from 
					PessoaEndereco 
				WHERE 
					IdPessoa = '$IdPessoa' and 
					IdPessoaEndereco=$IdPessoaEndereco;";
		$res0 = @mysql_query($sql0,$con);
		if($lin0 = @mysql_fetch_array($res0)){
			$sql = "select
						IdContrato
					from
						Contrato
					where
						Contrato.IdPessoa = '$IdPessoa' and
						(
							Contrato.IdPessoaEndereco = $IdPessoaEndereco or
							Contrato.IdPessoaEnderecoCobranca = $IdPessoaEndereco
						);";
			$res = @mysql_query($sql,$con);
			if($lin = @mysql_fetch_array($res)){
				$local_Erro = 129;
			}
			
			$sql = "select 
						PessoaEndereco.IdPessoaEndereco 
					from 
						Pessoa,
						PessoaEndereco
					WHERE 
						Pessoa.IdPessoa = '$IdPessoa' and 
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Pessoa.IdEnderecoDefault != $IdPessoaEndereco and
						PessoaEndereco.IdPessoaEndereco = $IdPessoaEndereco;";
			$res = @mysql_query($sql,$con);
			if(!$lin = @mysql_fetch_array($res)){
				$local_Erro = 129;
			}
		} else{
			$local_Erro = 7;
		}
		
		if($local_Erro != 0){
			echo $local_Erro;
		}else{
			$qtd				= 0;
			
			$sql	=	"select count(*) Qtd from Contrato where IdLoja = $IdLoja and IdPessoa = $IdPessoa and IdPessoaEndereco = $IdPessoaEndereco";
			$res	=	@mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
			$qtd   +=	$lin[Qtd];
			
			$sql	=	"select count(*) Qtd from Contrato where IdLoja = $IdLoja and IdPessoa = $IdPessoa and IdPessoaEnderecoCobranca = $IdPessoaEndereco";
			$res	=	@mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
			$qtd   +=	$lin[Qtd];
			
			$sql	=	"select count(*) Qtd from OrdemServico where IdLoja = $IdLoja and IdPessoa = $IdPessoa and IdPessoaEndereco = $IdPessoaEndereco";
			$res	=	@mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
			$qtd   +=	$lin[Qtd];
			
			$sql	=	"select count(*) Qtd from OrdemServico where IdLoja = $IdLoja and IdPessoa = $IdPessoa and IdPessoaEnderecoCobranca = $IdPessoaEndereco";
			$res	=	@mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
			$qtd   +=	$lin[Qtd];
			
			$sql	=	"select count(*) Qtd from ContaReceber where IdLoja = $IdLoja and IdPessoa = $IdPessoa and IdPessoaEndereco = $IdPessoaEndereco";
			$res	=	@mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
			$qtd   +=	$lin[Qtd];
			
			if($qtd == 0){

				$sql = "select 
							DescricaoEndereco,
							CEP,
							Endereco,
							Numero,
							Complemento,
							Bairro,
							IdPais,
							IdEstado,
							IdCidade,
							Telefone,
							Celular, 
							Fax, 
							ComplementoTelefone,
							EmailEndereco
						from 
							PessoaEndereco 
						where 
							IdPessoa = $IdPessoa and 
							IdPessoaEndereco = $IdPessoaEndereco;";
				$res = mysql_query($sql,$con);
				$lin = mysql_fetch_array($res);			

				if($Obs != '') { $Obs .= "\n"; }
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Descrição Endereço - ".$lin[DescricaoEndereco]."]";
					
				if($Obs != '') { $Obs .= "\n"; }
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [CEP - ".$lin[CEP]."]";
				
				if($Obs != '') { $Obs .= "\n"; }
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Endereço - ".$lin[Endereco]."]";

				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Número - ".$lin[Numero]."]";

				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Complemento - ".$lin[Complemento]."]";

				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Bairro - ".$lin[Bairro]."]";
				
				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [IdPais - ".$lin[IdPais]."]";
										
				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [IdEstado - ".$lin[IdEstado]."]";
										
				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [IdCidade - ".$lin[IdCidade]."]";					

				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Telefone - ".$lin[Telefone]."]";				

				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Celular - ".$lin[Celular]."]";				

				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Fax - ".$lin[Fax]."]";				

				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Complemento Telefone - ".$lin[ComplementoTelefone]."]";				

				if($Obs != '') { $Obs .= "\n"; }							
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Endereço Excluido [Email Endereço - ".$lin[EmailEndereco]."]";		

				$sql = "select 
							Obs
						from 
							Pessoa 
						where 
							IdPessoa = $IdPessoa;";
				$res = mysql_query($sql,$con);
				$lin = mysql_fetch_array($res);
				
				if($lin[Obs]!=""){
					if($Obs!=""){
						$Obs .= "\n";
					}
					
					$Obs .= trim($lin[Obs]);
				}
				
				$QtdAspas = substr_count($Obs,"'"); // Busca a quantidade de aspas simples dentro da string
				
				if($QtdAspas%2 == 0){
					$Obs = str_replace("'",'"',$Obs);
				} else{
					$Obs = str_replace("'",'',$Obs);
				}
			
				$sql = "UPDATE Pessoa SET
							Obs = \"$Obs\"
						WHERE 
							IdPessoa = $IdPessoa;";				
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;

				$sql	=	"DELETE FROM PessoaEndereco WHERE IdPessoa = '$IdPessoa' and IdPessoaEndereco=$IdPessoaEndereco;";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;

				for($i=0; $i<$tr_i; $i++){
					if($local_transaction[$i] == false){
						$local_transaction = false;				
					}
				}
				
				if($local_transaction == true){
					$sql = "COMMIT;";
					echo $local_Erro = 7;		
				}else{
					$sql = "ROLLBACK;";
					echo $local_Erro = 129;	
				}
				mysql_query($sql,$con);				
			} else{
				echo $local_Erro = 129;
			}
		}
	}
?>
