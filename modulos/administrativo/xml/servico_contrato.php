<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Servico(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdServico	 				= $_GET['IdServico'];
		$IdTipoServico	 			= $_GET['IdTipoServico'];
		$IdServicoGrupo	 			= $_GET['IdServicoGrupo'];
		$DescricaoServico	  		= $_GET['DescricaoServico'];
		$IdServicoAlterar			= $_GET['IdServicoAlterar'];
		$Filtro_IdPaisEstadoCidade	= $_GET['Filtro_IdPaisEstadoCidade'];
		$IdPessoa					= $_GET['IdPessoa'];
		$IdPessoaF					= $_GET['IdPessoaF'];
		$IdStatus					= $_GET['IdStatus'];
		
		$where					= "";
		
		if($IdPessoa == "" && $IdPessoaF!=""){
			$IdPessoa	=	$IdPessoaF;
		}
		
		if($IdServico != ''){			$where .= " and Servico.IdServico=$IdServico";							}
		if($IdServicoAlterar != ''){	$where .= " and Servico.IdServico != $IdServicoAlterar";				}
		if($DescricaoServico !=''){		$where .= " and Servico.DescricaoServico like '%$DescricaoServico%'";	}
		if($IdTipoServico != ''){		$where .= " and Servico.IdTipoServico in ($IdTipoServico)";				}
		if($IdServicoGrupo != ''){		$where .= " and Servico.IdServicoGrupo=$IdServicoGrupo";				}
		if($IdStatus != ''){			$where .= " and Servico.IdStatus=$IdStatus";							}
			
		$sql = "select
					Servico.IdLoja,
					Servico.IdServico,
					Servico.DescricaoServico,
					Servico.Filtro_IdPaisEstadoCidade,
					Servico.Filtro_IdTipoPessoa
				from
					Servico,
					ServicoGrupo
				where
					Servico.IdLoja = $IdLoja and
					Servico.IdLoja = ServicoGrupo.IdLoja and
					Servico.IdServicoGrupo = ServicoGrupo.IdServicoGrupo
					$where";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			$cont = 0;
			
			while($lin = @mysql_fetch_array($res)){
				if($lin[IdCategoriaTributaria] == ''){
					$lin[IdCategoriaTributaria] = 0;
				}

				$mostra = true;

				if($IdPessoa!=''){
					if($lin["Filtro_IdPaisEstadoCidade"]!=""){
						$mostra = false;
						$sql8 = "select 
									IdPais, 
									IdEstado, 
									IdCidade 
								from 
									Pessoa, 
									PessoaEndereco 
								where 
									Pessoa.IdPessoa = $IdPessoa and 
									Pessoa.IdPessoa = PessoaEndereco.IdPessoa";
						$res8 = @mysql_query($sql8, $con);
						
						while($lin8 = @mysql_fetch_array($res8)){
							$cidade = explode('^', $lin["Filtro_IdPaisEstadoCidade"]);
							
							for($i = 0; $i < count($cidade); $i++){
								$temp = explode(',', $cidade[$i]);
								$IdPais = $temp[0];
								$IdEstado = $temp[1];
								$IdCidade = $temp[2];
								
								if($lin8["IdPais"] == $IdPais && $lin8["IdEstado"] == $IdEstado && $lin8["IdCidade"] == $IdCidade){
									$mostra = true;
									break;
								}
							}
						}
						// Filtro por Endereço do Contrato/Instalação do contrato
						if($Filtro_IdPaisEstadoCidade != ""){
							$cidade = explode('^', $lin["Filtro_IdPaisEstadoCidade"]);
							$Filtro_IdPaisEstadoCidadeAux = explode(',', $Filtro_IdPaisEstadoCidade);
							$lin8["IdPais"] = $Filtro_IdPaisEstadoCidadeAux[0];
							$lin8["IdEstado"] = $Filtro_IdPaisEstadoCidadeAux[1];
							$lin8["IdCidade"] = $Filtro_IdPaisEstadoCidadeAux[2];
							
							for($i = 0; $i < count($cidade); $i++){
								$temp = explode(',', $cidade[$i]);
								$IdPais = $temp[0];
								$IdEstado = $temp[1];
								$IdCidade = $temp[2];
								
								if($lin8["IdPais"] == $IdPais && $lin8["IdEstado"] == $IdEstado && $lin8["IdCidade"] == $IdCidade){
									$mostra = true;
									break;
								}
							}			
						}	
					}

					if($lin[Filtro_IdTipoPessoa]!=""){
						$sql9	=	"select 
										TipoPessoa 
									from	
										Pessoa								
									where 									
										IdPessoa = $IdPessoa";
										
						$res9	=	@mysql_query($sql9,$con);
						$lin9	=	@mysql_fetch_array($res9);										
						
						if($lin9[TipoPessoa] != $lin[Filtro_IdTipoPessoa]){				
							$mostra	= false;
						}
					}
				}
				
				$sql4 = "select 
							Valor
						from 
							ServicoValor 
						where 
							DataInicio <= curdate() and 
							(DataTermino is Null  or DataTermino >= curdate()) and 
							IdLoja =$lin[IdLoja] and 
							IdServico = $lin[IdServico] 
						order by 
							DataInicio desc 
						limit 
							0,1"; 
				$res4 = @mysql_query($sql4,$con);
				$lin4 = @mysql_fetch_array($res4);
				
				if($mostra){
					$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
					$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";
					$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
					$dados	.=	"\n<Valor><![CDATA[$lin4[Valor]]]></Valor>";
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
	
	echo get_Servico();
?>