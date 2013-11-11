<?
	set_time_limit(0);
	ini_set("memory_limit","256M");
	
	$localModulo		=	1;
	$localOperacao		=	107;
	$localSuboperacao	=	"V";	
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	 
	require("../../../../classes/fpdf/class.fpdf.php");
	require("class.etiqueta.php");
	
	$IdLoja					= $_SESSION["IdLoja"];
	$IdContaReceber			= $_POST['Filtro_IdContaReceber'];
	$IdProcessoFinanceiro	= $_POST['IdProcessoFinanceiro'];
	$IdPessoa 				= $_POST['Filtro_IdPessoa'];
	$IdLocalCobranca 		= $_POST['IdLocalCobranca'];
	$EnderecoCobranca		= $_POST['EnderecoCobranca'];
	$Cedulas				= $_POST['Cedulas'];
	$QTDPagina				= $_POST['QTDPagina'];
	
	$pdf=new Etiqueta('P','cm','Letter');
	
	// Processos e configurações
	$pdf->SetDisplayMode('real');
	
	//float left, float top , float right
	$pdf->SetMargins(0.4, 1.8, 0.3);
	$pdf->SetAutoPageBreak(false); 
	$pdf->AddPage();	
	$pdf->Conteudo($Cedulas, $QTDPagina, $con);
	$pdf->Output("Etiquetas_".date("Y-m-d_H-i-s").".pdf","D");
?>
