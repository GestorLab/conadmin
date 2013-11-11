<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$local_IdLoja = $_SESSION['IdLoja'];
	//$dadosWhere = "AND IdDevice = " . $_POST["dadosWhere"]["IdDevice"];
	//print_r($_POST["dadosWhere"]);die;
	//echo $dadosWhere;die;
	if(isset($_POST["dadosWhere"])){

		//htmlspecialchars()
		$dadosWhere = " AND DV.IdDevice = " . $_POST["dadosWhere"]["IdDevice"];
		$sql = "SELECT DV.IdLoja, DV.IdDevice, DV.IdTipoDevice, DV.DescricaoDevice, DV.IdGrupoDevice, DV.Observacao, DV.LoginCriacao, 
				       DATE_FORMAT(DV.DataCriacao, '%d/%m/%Y %H:%i:%s') AS DataCriacao, DV.LoginAlteracao,
					   DATE_FORMAT(DV.DataAlteracao, '%d/%m/%Y %H:%i:%s') AS DataAlteracao,
					   GD.DescricaoGrupoDevice, IFNULL(DV.IdDevicePerfil, '') AS IdDevicePerfil
				FROM Device AS DV
				LEFT JOIN GrupoDevice AS GD ON(DV.IdGrupoDevice = GD.IdGrupoDevice)
			    WHERE DV.IdLoja = $local_IdLoja " . $dadosWhere;
		//echo $sql;die;
		mysql_set_charset("utf8", $con);
		$res = mysql_query($sql);
		$lin = @mysql_fetch_array($res, MYSQL_ASSOC);
		//$lin = array_map('htmlspecialchars', $lin);
		
		//print_r($lin);die;
		//echo $lin['Observacao'];die;
		echo json_encode($lin);
		
	}elseif(isset($_POST['dadosWherePorta'])){
		$dadosWherePorta = " AND IdDevice = " . $_POST['dadosWherePorta']["IdDevice"];
		
		header ('Content-type: text/html; charset=iso-8859-1');
		//echo $_POST['dadosWherePorta'];die;
		$sql = "SELECT IdLoja, IdDevice, IdDevicePorta, DescricaoDevicePorta, Disponivel, Observacao 
				FROM DevicePorta WHERE IdLoja = $local_IdLoja " . $dadosWherePorta;
		
		$res = mysql_query($sql);
		//print_r($res);
		if(@mysql_num_rows($res)){
		?>
			<div id='cp_Vencimento' style='margin-bottom: 0px; display: block;'>
				<div id='cp_tit' style='margin-bottom:0;margin-top:10px;'>Portas</div>
			    <table class='tableListarCad' cellspacing='0'>
	                <tr class='tableListarTitleCad'>
	                   <td class='tableListarEspaco'></td>
	 	               <td class='tableListarEspaco'>Descrição</td>
	 	               <td class='tableListarEspaco'>Observação</td>
	 	               <td class='tableListarEspaco'>Disponivel</td>
	 	            </tr>	
		<?php 
		$i = 1;
		while($lin = @mysql_fetch_array($res, MYSQL_ASSOC)):
			if($i%2 == 0){
				$cor = "style='background-color: rgb(226, 231, 237);'";
			}else{
				$cor = '';
			}
		    $IdDevicePorta = $lin['IdDevicePorta'];
		    $option = $lin['Disponivel'] == 1 ? "<option value='1' selected='selected'>Sim</option>" : "<option value='1'>Sim</option>";
		    $option .= $lin['Disponivel'] == 2 ? "<option value='2' selected='selected'>Não</option>" : "<option value='2'>Não</option>";
		?>
					<tr <?php echo $cor; ?> >
						<td style='width: 70px; text-align: center;'>
							<?php echo $i;?>
							<input type="hidden" name='portas[<?php echo $i;?>][IdDevicePorta]' value="<?php echo $IdDevicePorta;?>" />
						</td>
	 	            	<td style='width: 349px;'><input class="observe" name='portas[<?php echo $i;?>][DescricaoDevicePorta]' value="<?php echo $lin[DescricaoDevicePorta];?>" style='margin: 0px; width: 327px; outline: medium none; border-color: rgb(164, 164, 164); background-color: rgb(255, 255, 255);'/></td>
		 	            	<td style='width: 349px;'>
		 	            		<input class="observe" name='portas[<?php echo $i;?>][Observacao]' value="<?php echo $lin[Observacao];?>" style='margin: 0px; width: 327px; outline: medium none; border-color: rgb(164, 164, 164); background-color: rgb(255, 255, 255);'/>
		 	            		
		 	            	</td>
		 	            	<td>
		 	            		<select class="observe" name='portas[<?php echo $i;?>][Disponivel]' style='margin: 0px; width: 80px; outline: medium none; border-color: rgb(164, 164, 164); background-color: rgb(255, 255, 255);'>
		 	            		    <?php echo $option;?>
		 	            		</select>
		 	            		<!--<img id='excluir_2' class='excluir' alt='Excluir?' src='../../img/estrutura_sistema/ico_del.gif'></img>-->
		 	            	</td>
			 	  </tr>
		<?php
		$i++; 
		endwhile; 
		?>
					<tr class='tableListarTitleCad'>
	 	     			<td id='totalVencimentos' class='tableListarEspaco'>Total: <?php echo ($i - 1); ?></td>
 	     				<td></td>
 	     				<td></td>
 	     				<td></td>
	 	     		</tr>
			   </table>
		 	 </div>
		<?php 	
		}
	}
?>