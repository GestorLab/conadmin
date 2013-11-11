<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_postes(){
		global $con;
		
		$IdTipoPoste			= $_GET['IdTipoPoste'];
		$IdPoste				= $_GET['IdPoste'];		
		$where = "";
		
		if($IdTipoPoste != ""){
			$where = "IdTipoPoste = $IdTipoPoste LIMIT 45";
		}
		if($IdPoste != ""){
			$where = "IdPoste = $IdPoste
			AND Pais.IdPais = Poste.IdPais 
			AND Estado.IdEstado = Poste.IdEstado 
			AND Cidade.IdCidade = Poste.IdCidade ";
		}
			//Info Tipo de Poste
		$sql = "SELECT							
					Poste.IdPoste,
					Poste.IdTipoPoste,
					Poste.NomePoste,
					Poste.DescricaoPoste,
					Poste.IdPais,
					Pais.NomePais,
					Poste.IdEstado,
					Estado.NomeEstado,
					Poste.IdCidade,
					Cidade.NomeCidade,
					Poste.Endereco,
					Poste.Numero,
					Poste.Complemento,
					Poste.Bairro,
					Poste.Cep,
					Poste.Latitude,
					Poste.Longitude,
					Poste.LoginCriacao,
					Poste.DataCriacao,
					Poste.LoginAlteracao,
					Poste.DataAlteracao 
				FROM
					Poste,
					Pais,
					Estado,
					Cidade 
				WHERE 
					$where";
		$res 	= mysql_query($sql,$con);		
		$Total 	= mysql_num_rows($res);

		//Montar XML
		if(mysql_num_rows($res) > 0){	
			header ("content-type: text/xml");					
				$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
				$dados	.=	"\n<reg>";	
			while($lin	= mysql_fetch_array($res)){						
				$dados	.=	"\n<IdPoste><![CDATA[$lin[IdPoste]]]></IdPoste>";		
				$dados	.=	"\n<IdTipoPoste><![CDATA[$lin[IdTipoPoste]]]></IdTipoPoste>";		
				$dados	.=	"\n<NomePoste><![CDATA[$lin[NomePoste]]]></NomePoste>";		
				$dados	.=	"\n<DescricaoPoste><![CDATA[$lin[DescricaoPoste]]]></DescricaoPoste>";		
				$dados	.=	"\n<IdPais><![CDATA[$lin[IdPais]]]></IdPais>";		
				$dados	.=	"\n<NomePais><![CDATA[$lin[NomePais]]]></NomePais>";		
				$dados	.=	"\n<IdCidade><![CDATA[$lin[IdCidade]]]></IdCidade>";		
				$dados	.=	"\n<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";		
				$dados	.=	"\n<IdEstado><![CDATA[$lin[IdEstado]]]></IdEstado>";		
				$dados	.=	"\n<NomeEstado><![CDATA[$lin[NomeEstado]]]></NomeEstado>";		
				$dados	.=	"\n<Endereco><![CDATA[$lin[Endereco]]]></Endereco>";		
				$dados	.=	"\n<Numero><![CDATA[$lin[Numero]]]></Numero>";		
				$dados	.=	"\n<Complemento><![CDATA[$lin[Complemento]]]></Complemento>";		
				$dados	.=	"\n<Bairro><![CDATA[$lin[Bairro]]]></Bairro>";		
				$dados	.=	"\n<Cep><![CDATA[$lin[Cep]]]></Cep>";		
				$dados	.=	"\n<Latitude><![CDATA[$lin[Latitude]]]></Latitude>";		
				$dados	.=	"\n<Longitude><![CDATA[$lin[Longitude]]]></Longitude>";		
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";		
				
				$lin[DataCriacao] = dataConv($lin[DataCriacao],'Y-m-d H:i:s','d/m/Y H:i:s');				
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";		
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";		
				
				$lin[DataAlteracao] = dataConv($lin[DataAlteracao],'Y-m-d H:i:s','d/m/Y H:i:s');				
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";						
				$dados	.=	"\n<Total><![CDATA[$Total]]></Total>";		
			}
			
			if(mysql_num_rows($res) >=1){
				$dados	.=	"\n</reg>";					
			}
		}else{
			$dados = "false";
		}
		
		return $dados;
	}

	echo get_postes();

?>