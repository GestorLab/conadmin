<?php
include ('../../../files/conecta.php');
include ('../../../files/funcoes.php');
include ('../../../rotinas/verifica.php');

$local_IdLoja = $_SESSION['IdLoja'];

header ('Content-type: text/html; charset=iso-8859-1');

if(isset($_POST['dadosWhere'])){
	$sql = "SELECT * FROM DevicePerfilAtributo WHERE " . $_POST['dadosWhere'];
	//echo $sql;die;
	$res = mysql_query($sql, $con);
	//$lin = @mysql_fetch_array($res, MYSQL_ASSOC);
	//print_r($lin);die;
	$cont = 1;
	$html = "<tr><td><b>%s</b></td><td><b>&nbsp</b></td></tr>";
	$linha = "";
	$teste = "";
	while($lin = @mysql_fetch_array($res, MYSQL_ASSOC)){
		if(cont%2 == 0){
			$linha = str_replace("&nbsp;", "%s", $linha);
			$linha = sprintf($linha, $lin['DescricaoAtributo']);
			$teste .= $linha;
			$linha = $html;
			continue;
		}else{
			$linha = sprintf($linha, $lin['DescricaoAtributo']);
		}
		$cont++;
		//$html = "";
	}
	
	echo $teste;die;
}

?>
<div id="cp_parametrosSistemas">
	<div id="cp_tit">Atributos</div>
</div>
<table id="tabelaParametro" style="margin: 0">
	<tr>
		<td>DescricaoAtributo</td>
		<td>DescricaoAtributo</td>
	</tr>
	<tr>
		<td>
			<input type="text" class="obrig" value="ValorDefault" /><br />
			Observacao
		</td>
		<td>
			<input type="text" class="obrig" value="ValorDefault" /><br />
			Observacao
		</td>
	</tr>
</table>