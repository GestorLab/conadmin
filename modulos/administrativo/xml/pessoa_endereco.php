<?
	$localModulo	=	0;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Pessoa_Endereco(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja	 					= $_SESSION['IdLoja'];
		$IdPessoa 					= $_GET['IdPessoa'];
		$IdPessoaEndereco 			= $_GET['IdPessoaEndereco'];
		$IdServico		 			= $_GET['IdServico'];
		$Filtro_IdPaisEstadoCidade	= $_GET['Filtro_IdPaisEstadoCidade'];
		$where						= "";
		
		if($IdPessoa != ''){				$where .= " and Pessoa.IdPessoa=$IdPessoa";	}
		if($IdPessoaEndereco !=''){			$where .= " and IdPessoaEndereco=$IdPessoaEndereco";	}
		
		$sql = "select 
					Pessoa.IdPessoa,					
					Pessoa.TipoPessoa,					
					PessoaEndereco.IdPessoaEndereco,
					PessoaEndereco.Telefone,					
					PessoaEndereco.Celular,
					PessoaEndereco.Fax,	
					PessoaEndereco.ComplementoTelefone,					
					PessoaEndereco.EmailEndereco,
					PessoaEndereco.NomeResponsavelEndereco,
					PessoaEndereco.Endereco,
					PessoaEndereco.CEP,
					PessoaEndereco.Bairro,
					PessoaEndereco.Complemento,
					PessoaEndereco.Numero,
					PessoaEndereco.IdPais,
					Pais.NomePais,
					PessoaEndereco.IdEstado,
					Estado.NomeEstado,
					Estado.SiglaEstado,
					PessoaEndereco.IdCidade,
					Cidade.NomeCidade,
					PessoaEndereco.DescricaoEndereco  
				from 
					Pessoa,
					PessoaEndereco, 
					Pais,
					Estado,
					Cidade
				where
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					PessoaEndereco.IdPais = Pais.IdPais and
					PessoaEndereco.IdEstado = Estado.IdEstado and 
					PessoaEndereco.IdCidade = Cidade.IdCidade and 
					Pais.IdPais = Estado.IdPais and
					Pais.IdPais = Cidade.IdPais and 
					Estado.IdEstado = Cidade.IdEstado
					$where";
		$res = @mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			$cont = 0;
			
			while($lin = @mysql_fetch_array($res)){
				$mostra = true; 
				
				if($IdPessoa != ""){
					$sql0 = "select 
								Filtro_IdPaisEstadoCidade,
								Filtro_IdTipoPessoa
							from 
								Servico 
							where 
								Servico.IdLoja = '$IdLoja' and 
								Servico.IdServico = '$IdServico'";
					$res0 = @mysql_query($sql0, $con);
					$lin0 = @mysql_fetch_array($res0);
					
					if($lin0["Filtro_IdPaisEstadoCidade"] != ""){
						$mostra = false;
						$cidade = explode('^', $lin0["Filtro_IdPaisEstadoCidade"]);
						
						for($i = 0; $i < count($cidade); $i++){
							$temp = explode(',', $cidade[$i]);
							$IdPais = $temp[0];
							$IdEstado = $temp[1];
							$IdCidade = $temp[2];
							
							if($lin["IdPais"] == $IdPais && $lin["IdEstado"] == $IdEstado && $lin["IdCidade"] == $IdCidade){
								$mostra = true;
								break;
							}
						}
						// Filtro por Endereço do Contrato/Instalação do contrato
						if($Filtro_IdPaisEstadoCidade != ""){
							$cidade = explode('^', $lin0["Filtro_IdPaisEstadoCidade"]);
							$Filtro_IdPaisEstadoCidadeAux = explode(',', $Filtro_IdPaisEstadoCidade);
							
							for($i = 0; $i < count($cidade); $i++){
								$temp = explode(',', $cidade[$i]);
								$IdPais = $temp[0];
								$IdEstado = $temp[1];
								$IdCidade = $temp[2];
								
								if($Filtro_IdPaisEstadoCidadeAux[0] == $IdPais && $Filtro_IdPaisEstadoCidadeAux[1] == $IdEstado && $Filtro_IdPaisEstadoCidadeAux[2] == $IdCidade){
									$mostra = true;
									break;
								}
							}
						}
					}

					if($lin0["Filtro_IdTipoPessoa"] != ""){
						if($lin["TipoPessoa"] != $lin0["Filtro_IdTipoPessoa"]){				
							$mostra	= false;
						}
					}
				}
				
				if($mostra){
					$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
					$dados	.=	"\n<IdPessoaEndereco><![CDATA[$lin[IdPessoaEndereco]]]></IdPessoaEndereco>";
					$dados	.=	"\n<IdPais><![CDATA[$lin[IdPais]]]></IdPais>";
					$dados	.=	"\n<NomePais><![CDATA[$lin[NomePais]]]></NomePais>";
					$dados	.=	"\n<IdEstado><![CDATA[$lin[IdEstado]]]></IdEstado>";
					$dados	.=	"\n<NomeEstado><![CDATA[$lin[NomeEstado]]]></NomeEstado>";
					$dados	.=	"\n<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
					$dados	.=	"\n<IdCidade><![CDATA[$lin[IdCidade]]]></IdCidade>";
					$dados	.=	"\n<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
					$dados	.=	"\n<CEP><![CDATA[$lin[CEP]]]></CEP>";
					$dados	.=	"\n<Endereco><![CDATA[$lin[Endereco]]]></Endereco>";
					$dados	.=	"\n<Complemento><![CDATA[$lin[Complemento]]]></Complemento>";
					$dados	.=	"\n<Numero><![CDATA[$lin[Numero]]]></Numero>";
					$dados	.=	"\n<Bairro><![CDATA[$lin[Bairro]]]></Bairro>";
					$dados	.=	"\n<Telefone><![CDATA[$lin[Telefone]]]></Telefone>";
					$dados	.=	"\n<Celular><![CDATA[$lin[Celular]]]></Celular>";
					$dados	.=	"\n<Fax><![CDATA[$lin[Fax]]]></Fax>";
					$dados	.=	"\n<ComplementoTelefone><![CDATA[$lin[ComplementoTelefone]]]></ComplementoTelefone>";
					$dados	.=	"\n<NomeResponsavelEndereco><![CDATA[$lin[NomeResponsavelEndereco]]]></NomeResponsavelEndereco>";
					$dados	.=	"\n<DescricaoEndereco><![CDATA[$lin[DescricaoEndereco]]]></DescricaoEndereco>";
					$dados	.=	"\n<EmailEndereco><![CDATA[$lin[EmailEndereco]]]></EmailEndereco>";
					$cont++;
				}
				
				if($Limit != "" && $cont >= $Limit){
					break;
				}
			}
			
			$dados	.=	"\n</reg>";
			
			if($cont > 0){
				return $dados;
			} else{
				return "false";
			}
		} else{
			return "false";
		}
	}
	
	echo get_Pessoa_Endereco();
?>