<?
	$localModulo = 0;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_NomePessoa(){
		global $con;
		global $_GET;
		
		$Nome = $_GET['Nome'];
		$Limit = getCodigoInterno(7,12);
		
		if($Limit == ""){
			$Limit = 0;
		}
		
		$sql = "select 
					Pessoa.Nome,			
					Pessoa.RazaoSocial,
					Pessoa.TipoPessoa
				from 
					Pessoa
				where 
					(
						Pessoa.Nome like '$Nome%' or 
						Pessoa.RazaoSocial like '$Nome%'
					)
				order by
					Pessoa.Nome,
					Pessoa.RazaoSocial
				limit $Limit;";
		$res = @mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				if($lin[TipoPessoa] == 1){
					if(getCodigoInterno(3,24) == "Nome" || getCodigoInterno(3,24) == "RazaoSocial"){
					//	$lin[NomeDefault] = $lin[getCodigoInterno(3,24)];//Leonardo-> Foi conversado com o douglas para ser feito essa alteração.
						$dados	.=	"\n<NomeDefault><![CDATA[".$lin[getCodigoInterno(3,24)]."]]></NomeDefault>";
					}else{
						$dados	.=	"\n<NomeDefault><![CDATA[$lin[Nome]]]></NomeDefault>";
						$dados	.=	"\n<NomeDefault><![CDATA[$lin[RazaoSocial]]]></NomeDefault>";
					}
				} else{
					//$lin[NomeDefault] = $lin[Nome];//Leonardo-> Foi conversado com o douglas para ser feito essa alteração.
					$dados	.=	"\n<NomeDefault><![CDATA[$lin[Nome]]]></NomeDefault>";
				}
				
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_NomePessoa();
?>