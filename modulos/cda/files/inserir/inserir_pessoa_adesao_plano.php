<?
	$Path = "../../../../";
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../../../classes/envia_mensagem/envia_mensagem.php');
	include('../funcoes.php');
	
	$_SESSION["IdLoja"]		= getParametroSistema(95,6);
	$_SESSION["IdLojaCDA"]	= getParametroSistema(95,6);
	$local_IdPais			= getCodigoInternoCDA(3,1);
	$local					= "cda";
	$bloqueio				= "disabled";
	
	foreach($_POST as $key => $value){
		if(substr($key,0,3) != "bt_"){
			if(substr($key,0,24) == "NomeResponsavelEndereco_"){
				$key = substr($key,0,25);
			}
			
			if($key == "OcultarEnderecoCobrancaTemp"){
				$key = "OcultarEnderecoCobranca";
			}
			
			$temp = endArray(explode("_",$key));
			
			if($temp == 1 || $temp == 2 || $key == "OcultarEnderecoCobranca"){
				$var = "\$_POST[".$key."]";
			} else{
				$var = "\$local_".$key;
			}
			
			
			$count = (count($key) - 5);
			
			if(substr($key,$count,4) == "Temp" && !in_array(substr($key,0,$count),$_POST)){
				eval("\$local_".substr($key,0,$count)." = '".$value."';");
			}
			
			eval($var." = '".$value."';");
		}
	}
	
	$local_ObsAux = $local_Obs;
	$local_Obs  = date("d/m/Y H:i:s")." [$local] - ".$local_Obs.".";
	@include("../../../administrativo/files/inserir/inserir_pessoa.php");
	
	if($local_Erro == 3){
		$local_Obs = $local_ObsAux;
		$local_IdPessoaEnderecoCobranca = $local_IdPessoaEndereco;
		$Path = "../../../administrativo/";
		
		@include("../../../administrativo/files/inserir/inserir_contrato.php");
		
		if($local_Erro == 3){
			$local_header = "../../debug.php?IdParametroSistema=29&Erro=64";
			# Mensagem de Cadastramento de Contrato Positiva 
		} else{
			$sql = "delete from PessoaEndereco where IdPessoa = $local_IdPessoa;";
			@mysql_query($sql,$con);
			
			$sql = "delete from PessoaGrupoPessoa where IdPessoa = $local_IdPessoa;";
			@mysql_query($sql,$con);
			
			$sql = "delete from Pessoa where IdPessoa = $local_IdPessoa;";
			@mysql_query($sql,$con);
			
			$local_header = "../../debug.php?IdParametroSistema=26&Erro=21";
			# Mensagem de Cadastramento de Contrato Negativa
		}
	} else{
		$local_header = "../../debug.php?IdParametroSistema=24&Erro=21";
		# Mensagem de Cadastramento de Pessoa Negativa
	}
	
	header("Location: $local_header");
?>