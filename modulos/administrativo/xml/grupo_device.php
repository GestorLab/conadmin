<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	//print_r($_POST['dadosWhere']);
	if(isset($_POST['dadosWhere'])){
		$sql = "select IdLoja, IdGrupoDevice, DescricaoGrupoDevice,
		        DisponivelContrato, LoginCriacao, date_format(DataCriacao, '%d/%m/%Y') as DataCriacao,
		        LoginAlteracao, date_format(DataAlteracao, '%d/%m/%Y') as DataAlteracao
		        from GrupoDevice where IdLoja = " . $_POST['dadosWhere']['IdLoja'] .
				" AND IdGrupoDevice = " . $_POST['dadosWhere']['IdGrupoDevice'];
		//echo $sql;die;
		$res = mysql_query($sql);
		$lin = @mysql_fetch_assoc($res);
		if($lin != null){
			echo json_encode($lin);
		}	
	}
	
	if(isset($_POST['dadosWhereQuadro'])){
		$sql = "select IdLoja, IdGrupoDevice, DescricaoGrupoDevice, 
		        LoginCriacao, date_format(DataCriacao, '%d/%m/%Y') as DataCriacao,
		        LoginAlteracao, date_format(DataAlteracao, '%d/%m/%Y') as DataAlteracao 
		        from GrupoDevice where " . $_POST['dadosWhereQuadro'];
		//echo $sql;die;
		$res = mysql_query($sql);
		
		
		header ('Content-type: text/html; charset=iso-8859-1');
		if(mysql_num_rows($res) > 0){
?>
		
			<table id="listaDados" class="teste">
				<tr>
					<td class="listaDados_titulo">Grupo Device</td>
					<td class="listaDados_titulo">Descrição</td>
				</tr>
				<?php while($lin = @mysql_fetch_array($res, MYSQL_ASSOC)):?>
				<tr class="dadosTable" style="background-color: #FFF;">
					<td style='width: 80px;'><?php echo $lin['IdGrupoDevice'];?></td>
					<td style='width: 290px;'><?php echo $lin['DescricaoGrupoDevice'];?></td>
				</tr>
				<?php endwhile;?>
			</table>
<?php 
		}
	}
	//}
//}
?>
