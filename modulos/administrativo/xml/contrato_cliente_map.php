<?
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_ContratoClienteMap(){
		global $con;
		global $_SESSION;
		global $_GET;
		
		$IdLoja				= $_SESSION["IdLoja"];
		$localIdServico		= $_GET["filtro_id_servico"];
		$localIdEstado		= $_GET["filtro_estado"];
		$localNomeCidade	= $_GET["filtro_cidade"];
		$localIdStatus		= $_GET["filtro_status"];
		$localLimit			= $_GET["filtro_limit"];
		$While				= "";
		
		if($localIdServico != ''){
			$While .= " AND Contrato.IdServico = '$localIdServico'";
		}
		
		if($localIdEstado != ''){
			$While .= " AND PessoaEndereco.IdEstado = '$localIdEstado'";
		}
		
		if($localNomeCidade != ''){
			$While .= " AND Cidade.NomeCidade LIKE '%$localNomeCidade%'";
		}
		
		if($localIdStatus != ''){
			$AUX = explode("G_", $localIdStatus);
			
			if($AUX[1] != ""){
				switch($AUX[1]){
					case '1':
						$While .= " AND (Contrato.IdStatus >= 1 AND Contrato.IdStatus < 199)";
						break;
					case '2':
						$While .= " AND (Contrato.IdStatus >= 200 AND Contrato.IdStatus < 300)";
						break;
					case '3':
						$While .= " AND (Contrato.IdStatus >= 300 AND Contrato.IdStatus < 400)";
						break;
				}
			} else{
				$While .= " AND Contrato.IdStatus = '$localIdStatus'";
			}
		}
		
		if($localLimit != ""){
			$Limit	= " LIMIT $localLimit";
		}
		
		$sql = "SELECT 
					Contrato.IdLoja,
					Pessoa.IdPessoa,
					PessoaEndereco.Latitude,
					PessoaEndereco.Longitude
				FROM
					Contrato,
					Pessoa,
					PessoaEndereco,
					Cidade
				WHERE 
					Contrato.IdLoja = $IdLoja AND 
					Contrato.IdPessoa = Pessoa.IdPessoa AND 
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa AND 
					Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco  AND
					PessoaEndereco.IdPais = Cidade.IdPais AND 
					PessoaEndereco.IdEstado = Cidade.IdEstado AND 
					PessoaEndereco.IdCidade = Cidade.IdCidade 
					$While
				GROUP BY 
					CONCAT(PessoaEndereco.Latitude, ', ', PessoaEndereco.Longitude),
					CASE WHEN PessoaEndereco.Latitude IS NULL THEN Pessoa.IdPessoa END
				ORDER BY
					PessoaEndereco.Latitude DESC
				$Limit";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			$sql_fc = "SELECT 
							Temp.NomePais,
							Temp.SiglaEstado
						FROM
							(
								SELECT 
									Pais.NomePais,
									Estado.IdEstado,
									Estado.SiglaEstado
								FROM
									Contrato,
									Pessoa,
									PessoaEndereco,
									Pais,
									Estado,
									Cidade
								WHERE 
									Contrato.IdLoja = $IdLoja AND 
									Contrato.IdPessoa = Pessoa.IdPessoa AND 
									Pessoa.IdPessoa = PessoaEndereco.IdPessoa AND 
									Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco  AND
									PessoaEndereco.IdPais = Pais.IdPais AND 
									Pais.IdPais = Estado.IdPais AND 
									PessoaEndereco.IdEstado = Estado.IdEstado AND 
									Estado.IdPais = Cidade.IdPais AND 
									Estado.IdEstado = Cidade.IdEstado AND 
									PessoaEndereco.IdCidade = Cidade.IdCidade 
									$While
								GROUP BY 
									CONCAT(PessoaEndereco.Latitude, ', ', PessoaEndereco.Longitude),
									CASE WHEN PessoaEndereco.Latitude IS NULL THEN Pessoa.IdPessoa END
								$Limit
							) Temp
						GROUP BY 
							Temp.IdEstado";
			$res_fc = mysql_query($sql_fc, $con);
			
			if(mysql_num_rows($res_fc) == 1) {
				$lin_fc = mysql_fetch_array($res_fc);
				$AddressFocos = $lin_fc["SiglaEstado"].", ".$lin_fc["NomePais"];
			}
			
			$dados .= "\n\t<Foco><![CDATA[$AddressFocos]]></Foco>";
			
			while($lin = @mysql_fetch_array($res)){
				if($lin["Latitude"] == "" && $lin["Longitude"] == ""){
					$where_1 = " AND Contrato.IdPessoa = '".$lin["IdPessoa"]."' AND PessoaEndereco.Latitude IS NULL AND PessoaEndereco.Longitude IS NULL"; 
				} else{
					$where_1 = " AND PessoaEndereco.Latitude = '".$lin["Latitude"]."' AND PessoaEndereco.Longitude = '".$lin["Longitude"]."'"; 
				}
				
				$dados .= "\n\t<Localizacao>";
				$dados .= "\n\t<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
				$dados .= "\n\t<Latitude><![CDATA[$lin[Latitude]]]></Latitude>";
				$dados .= "\n\t<Longitude><![CDATA[$lin[Longitude]]]></Longitude>";
				$dados .= "\n\t<Pessoa>";
				
				$sql_pe = "SELECT 
								Pessoa.IdPessoa,
								Pessoa.Nome,
								Pessoa.RazaoSocial,
								Pessoa.Telefone1,
								Pessoa.Telefone2,
								Pessoa.Telefone3,
								Pessoa.Celular,
								Pessoa.Email,
								PessoaEndereco.Endereco,
								PessoaEndereco.Bairro,
								PessoaEndereco.Numero,
								CONCAT(Cidade.NomeCidade, ' - ', Estado.SiglaEstado, ', ', PessoaEndereco.CEP, ', ', Pais.NomePais) Address
							FROM
								Contrato,
								Pessoa,
								PessoaEndereco,
								Pais,
								Estado,
								Cidade 
							WHERE 
								Contrato.IdLoja = '".$lin["IdLoja"]."' AND 
								Contrato.IdPessoa = Pessoa.IdPessoa AND 
								Pessoa.IdPessoa = PessoaEndereco.IdPessoa AND 
								Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco AND 
								PessoaEndereco.IdPais = Pais.IdPais AND
								PessoaEndereco.IdEstado = Estado.IdEstado AND 
								PessoaEndereco.IdCidade = Cidade.IdCidade AND 
								Pais.IdPais = Estado.IdPais AND 
								Pais.IdPais = Cidade.IdPais AND 
								Estado.IdEstado = Cidade.IdEstado
								$where_1
							GROUP BY 
								Pessoa.IdPessoa";
				$res_pe = mysql_query($sql_pe, $con);
				
				while($lin_pe = mysql_fetch_array($res_pe)){
					if(!empty($lin_pe["Numero"])) {
						$lin_pe["Address"] = $lin_pe["Numero"].", ".$lin_pe["Address"];
					}
					
					if(!empty($lin_pe["Endereco"])) {
						$lin_pe["Address"] = $lin_pe["Endereco"].", ".$lin_pe["Address"];
					}
					
					$dados .= "\n\t<IdPessoa><![CDATA[$lin_pe[IdPessoa]]]></IdPessoa>";
					$dados .= "\n\t<Nome><![CDATA[$lin_pe[Nome]]]></Nome>";
					$dados .= "\n\t<RazaoSocial><![CDATA[$lin_pe[RazaoSocial]]]></RazaoSocial>";
					$dados .= "\n\t<Telefone1><![CDATA[$lin_pe[Telefone1]]]></Telefone1>";
					$dados .= "\n\t<Telefone2><![CDATA[$lin_pe[Telefone2]]]></Telefone2>";
					$dados .= "\n\t<Telefone3><![CDATA[$lin_pe[Telefone3]]]></Telefone3>";
					$dados .= "\n\t<Celular><![CDATA[$lin_pe[Celular]]]></Celular>";
					$dados .= "\n\t<Email><![CDATA[$lin_pe[Email]]]></Email>";
					$dados .= "\n\t<Endereco><![CDATA[$lin_pe[Endereco]]]></Endereco>";
					$dados .= "\n\t<Address><![CDATA[$lin_pe[Address]]]></Address>";
				}
				
				$dados .= "\n\t</Pessoa>";
				$dados .= "\n\t</Localizacao>";
			}
			
			$dados .= "\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_ContratoClienteMap();
?>