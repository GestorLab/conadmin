<?php
$localModulo	=	1;

include ('../../../files/conecta.php');
include ('../../../files/funcoes.php');
include ('../../../rotinas/verifica.php');
header ('Content-type: text/html; charset=iso-8859-1');

$local_IdLoja = $_SESSION['IdLoja'];

if(isset($_POST['dadosWhere'])){
	$sql = "SELECT QtdMaxPortas FROM DevicePerfil WHERE IdDevicePerfil = " . $_POST['dadosWhere'];
	//echo $sql;die;
	$res = mysql_query($sql, $con);
	$lin = @mysql_fetch_array($res, MYSQL_ASSOC);
	
	if($lin != null){
		if($lin['QtdMaxPortas'] != "" && $lin['QtdMaxPortas'] > 0){
			for($i = 1; $i <= $lin['QtdMaxPortas']; $i++){
				$html .= "<option value='$i'>$i</option>";
			}
?>
			<select id="QuantidadePortasSelect" style='width: 67px;' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
				<option value="0"></option>
				<?php echo $html;?>
			</select>
<?php 
		}else if($lin['QtdMaxPortas'] == 0){
			echo $lin['QtdMaxPortas'];
		}
	}
}