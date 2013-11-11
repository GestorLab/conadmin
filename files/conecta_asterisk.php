<?
	if($_SESSION["IdLoja"] != ''){
		$con_AST = getCodigoInterno(11000, 1);
	}
	$con_AST = @explode("\r\n", $con_AST);
	$conAST = @mysql_connect($con_AST[0], $con_AST[1], $con_AST[2]);
	@mysql_select_db($con_AST[3], $conAST);
?>