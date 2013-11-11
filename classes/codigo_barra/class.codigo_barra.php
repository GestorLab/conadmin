<?
function CodigoBarras($CodBarras, $altura){

	// Definimos as dimensões das imagens 
	$fino	= 1;
	$largo	= 3; 

	// Criamos um array associativo com os binários 
	$Bar[0] = "00110"; 
	$Bar[1] = "10001"; 
	$Bar[2] = "01001"; 
	$Bar[3] = "11000"; 
	$Bar[4] = "00101"; 
	$Bar[5] = "10100"; 
	$Bar[6] = "01100"; 
	$Bar[7] = "00011"; 
	$Bar[8] = "10010"; 
	$Bar[9] = "01010";

	$p = "../../img/estrutura_sistema/p.gif";
	$b = "../../img/estrutura_sistema/b.gif";

	// Preto - Fino
	echo "<img src='$p' width=$fino height=$altura border=0>";

	// Branco - Fino
	echo "<img src='$b' width=$fino height=$altura border=0>";

	// Preto - Fino
	echo "<img src='$p' width=$fino height=$altura border=0>";

	// Branco - Fino
	echo "<img src='$b' width=$fino height=$altura border=0>";

	for ($a = 0; $a < strlen($CodBarras); $a++){ 

		$Preto  = $CodBarras[$a]; 
		$CodPreto  = $Bar[$Preto]; 

		$a = $a+1; // Sabemos que o Branco é um depois do Preto... 
		$Branco = $CodBarras[$a]; 
		$CodBranco = $Bar[$Branco]; 

		// Encontrado o CodPreto e o CodBranco vamos fazer outro looping dentro do nosso 
		for ($y = 0; $y < 5; $y++) { // O for vai pegar os binários 

			if ($CodPreto[$y] == '0') { // Se o binario for preto e fino ecoa 
				// Preto - Fino
				echo "<img src='$p' width=$fino height=$altura border=0>";
			} 

			if ($CodPreto[$y] == '1') { // Se o binario for preto e grosso ecoa 
				// Preto - Largo
				echo "<img src='$p' width=$largo height=$altura border=0>";
			} 

			if ($CodBranco[$y] == '0') { // Se o binario for branco e fino ecoa 
				// Branco - Fino
				echo "<img src='$b' width=$fino height=$altura border=0>";
			} 

			if($CodBranco[$y] == '1') { // Se o binario for branco e grosso ecoa 
				// Branco - Largo
				echo "<img src='$b' width=$largo height=$altura border=0>";
			} 
		} 

	} // Fechamos nosso looping maior 

	// Encerramos o código ecoando o final(encerramento) 
	// Final padrão do Codigo de Barras 

	// Preto - Largo
	echo "<img src='$p' width=$largo height=$altura border=0>";

	// Branco - Fino
	echo "<img src='$b' width=$fino height=$altura border=0>";

	// Preto - Fino
	echo "<img src='$p' width=$fino height=$altura border=0>";
}
?>