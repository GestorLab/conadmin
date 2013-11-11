<?
	$localModulo	=	1;
	
	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	@include ("../../../rotinas/verifica.php");
	
	function get_ContaSMS(){
		global $con;
		global $_GET;
		
		$IdLoja 		= $_SESSION["IdLoja"];
		$IdContaSMS 	=	$_GET['IdContaSMS'];
			
		$sql = "Select
					ContaSMS.IdContaSMS,
					ContaSMS.DescricaoContaSMS,
					ContaSMS.IdOperadora,
					ContaSMS.IdStatus,
					ContaSMS.LoginCriacao,
					ContaSMS.DataCriacao,
					ContaSMS.LoginAlteracao,
					ContaSMS.DataAlteracao
				From
					ContaSMS
				Where
					IdContaSMS = $IdContaSMS";
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			@header ("content-type: text/xml");
			$dados	.=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			echo "false";
		}
		while($lin = @mysql_fetch_array($res)){
			$lin[DataCriacao] 	= dataConv($lin[DataCriacao],'Y-m-d H:i:s','d/m/Y H:i:s');
			$lin[DataAlteracao] = dataConv($lin[DataAlteracao],'Y-m-d H:i:s','d/m/Y H:i:s');
			
			$dados 	.= "\n<IdContaSMS>$lin[IdContaSMS]</IdContaSMS>";
			$dados	.=	"\n<DescricaoContaSMS><![CDATA[$lin[DescricaoContaSMS]]]></DescricaoContaSMS>";
			$dados 	.= "\n<IdOperadora>$lin[IdOperadora]</IdOperadora>";
			$dados 	.= "\n<IdStatus>$lin[IdStatus]</IdStatus>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			echo $dados;
		}
	}
	get_ContaSMS();
 ?>