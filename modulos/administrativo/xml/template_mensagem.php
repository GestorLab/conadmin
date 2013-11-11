<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_TemplateMensagem(){
		global $con;
		global $_GET;
		
		$local_IdLoja		= $_SESSION['IdLoja'];
		$local_IdTemplate	= $_GET['IdTemplate'];
		$where				= '';
		
		if($local_IdTemplate != ''){
			$where .= " and TemplateMensagem.IdTemplate = $local_IdTemplate";
		}
		
		$sql = "select 
					TemplateMensagem.IdTemplate, 
					TemplateMensagem.DescricaoTemplate, 
					TemplateMensagem.Estrutura, 
					TemplateMensagem.LoginCriacao, 
					TemplateMensagem.DataCriacao, 
					TemplateMensagem.LoginAlteracao, 
					TemplateMensagem.DataAlteracao
				from 
					TemplateMensagem
				where 
					TemplateMensagem.IdLoja = '$local_IdLoja'
					$where;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados .= "\n<IdTemplate>$lin[IdTemplate]</IdTemplate>";
				$dados .= "\n<DescricaoTemplate><![CDATA[$lin[DescricaoTemplate]]]></DescricaoTemplate>";
				$dados .= "\n<Estrutura><![CDATA[$lin[Estrutura]]]></Estrutura>";
				$dados .= "\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados .= "\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados .= "\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados .= "\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_TemplateMensagem();
?>