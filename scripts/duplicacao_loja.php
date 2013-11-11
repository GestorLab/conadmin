<?
	include("../files/conecta.php");

	$IdLojaCopiar	= $_GET[IdLojaCopiar];
	$IdLoja			= $_GET[IdLojaAtualizar];

	echo "Loja copiada: $IdLojaCopiar<br>";
	echo "Loja para atualizar: $IdLoja";

	echo "<br>";

	if($IdLojaCopiar != ''){
		$sql	=	"SET FOREIGN_KEY_CHECKS=0;";
		mysql_query($sql,$con);

		$Tabela[0]	= 'Carteira';
		$Tabela[1]	= 'CentroCusto';
		$Tabela[2]	= 'CodigoInterno';
		$Tabela[3]	= 'DatasEspeciais';
		$Tabela[4]	= 'GrupoProduto';
		$Tabela[5]	= 'GrupoUsuario';
		$Tabela[6]	= 'GrupoPermissaoSubOperacao';
		$Tabela[7]	= 'Periodicidade';
		$Tabela[8]	= 'PlanoConta';
		$Tabela[9]	= 'ServicoGrupo';
		$Tabela[10]	= 'TipoEmail';
		$Tabela[11]	= 'UsuarioSubOperacao';
		$Tabela[12]	= 'ContratoTipoVigencia';

		$QtdTabelas = count($Tabela);

		$iii=0;

		for($i=0; $i<$QtdTabelas;$i++){

			$sqlTabela		= "select * from ".$Tabela[$i]." where IdLoja = $IdLojaCopiar";
			$resTabela		= mysql_query($sqlTabela,$con);
			$qtdColunas		= mysql_num_fields($resTabela);
			$numRegistro	= mysql_num_rows($resTabela);

			if($numRegistro > 0){
				while($linTabela = mysql_fetch_array($resTabela)){
				
					$sqlInsert			= "insert into ".$Tabela[$i]." set ";
					$sqlInsertFields	= "";

					for($ii=0; $ii<$qtdColunas; $ii++){
						$infoColuna = mysql_fetch_field($resTabela,$ii);
						$NomeColuna = $infoColuna->name;

						$Insere = true;

						if($NomeColuna == 'IdLoja'){
							$linTabela[$NomeColuna] = $IdLoja;
						}

						if($NomeColuna == 'DataCriacao'){
							$linTabela[$NomeColuna] = date("Y-m-d H:i:s");
							$Insere = true;
						}

						if($NomeColuna == 'LoginAlteracao'){
							$linTabela[$NomeColuna] = 'NULL';
							$Insere = true;
						}

						if($NomeColuna == 'DataAlteracao'){
							$Insere = false;
						}

						if($Insere == true){
							if($sqlInsertFields != ''){
								$sqlInsertFields .= ", ";
							}
							$sqlInsertFields .= "$NomeColuna='$linTabela[$NomeColuna]'";
						}
					}

					$sqlInsert = $sqlInsert.$sqlInsertFields;
					mysql_query($sqlInsert,$con);
					$iii++;
				}
			}
		}

		$sql	=	"SET FOREIGN_KEY_CHECKS=1;";
		mysql_query($sql,$con);

		echo $iii." Registros<br>";
	}
?>
