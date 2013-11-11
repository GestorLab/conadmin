<?php
	function resizeImage($imagem,$pfx,$x,$y,$destino){
		// DEFINIR O NOME DO ARQUIVO PARA O THUMBNAIL
		$ext	=	strtolower(end(explode('.', $imagem)));

		$thumbnail	=	$destino;

		ini_set("memory_limit","128M");

		// LÊ A IMAGEM DE ORIGEM
		switch ($ext){
			case "jpg":
				$img_origem = ImageCreateFromJPEG($imagem);
				break;
			case "jpeg":
				$img_origem = ImageCreateFromJPEG($imagem);
				break;
			case "gif":
				$img_origem = ImageCreateFromGif($imagem);
				break;
			case "png":
				$img_origem = ImageCreateFromPng($imagem);
				break;
		}
		// PEGA AS DIMENSÕES DA IMAGEM DE ORIGEM
		$origem_x = imagesx($img_origem); // Largura
		$origem_y = imagesy($img_origem); // Altura

		if($x=="" && $y==""){
			// DEFINIR AS DIMENSÕES PADRÕES PARA O THUMBNAIL
			$x = 120; // Largura
			$y = 120; // Altura
		}else{
			if($x!="" && $y==""){
				$divisor	=	$origem_x/$x;
				$y			=	$origem_y/$divisor;
			}else{
				$divisor	=	$origem_y/$y;
				$x			=	$origem_x/$divisor;
			}
		}
	
		// ESCOLHE A LARGURA MAIOR E, BASEADO NELA, GERA A LARGURA MENOR
		if($origem_x > $origem_y) { // Se a largura for maior que a altura
		   $final_x = $x; // A largura será a do thumbnail
		   $final_y = floor($x * $origem_y / $origem_x); // A altura é calculada
		   $f_x = 0; // Colar no x = 0
		   $f_y = round(($y / 2) - ($final_y / 2)); // Centralizar a imagem no meio y do thumbnail
		}else{ // Se a altura for maior ou igual à largura
		   $final_x = floor($y * $origem_x / $origem_y); // Calcula a largura
		   $final_y = $y; // A altura será a do thumbnail
		   $f_x = round(($x / 2) - ($final_x / 2)); // Centraliza a imagem no meio x do thumbnail
		   $f_y = 0; // Colar no y = 0
		}

		$final_x	=	(int)$final_x;
		$final_y	=	(int)$final_y;

		$f_x	=	(int)$f_x;
		$f_y	=	(int)$f_y;

		$x	=	(int)$x;
		$y	=	(int)$y;

		// CRIA A IMAGEM FINAL PARA O THUMBNAIL
		$img_final = imagecreatetruecolor($x,$y);

		// COPIA A IMAGEM ORIGINAL PARA DENTRO DO THUMBNAIL
		ImageCopyResized($img_final, $img_origem, $f_x, $f_y, 0, 0, $final_x, $final_y, $origem_x, $origem_y);

	// SALVA O THUMBNAIL

		switch ($ext){
			case "jpg":
				ImageJPEG($img_final, $thumbnail);
				break;
			case "jpeg":
				ImageJPEG($img_final, $thumbnail);
				break;
			case "gif":
				ImageGIF($img_final, $thumbnail);
				break;
			case "png":
				ImagePNG($img_final, $thumbnail);
				break;
		}

	// LIBERA A MEMÓRIA
		ImageDestroy($img_origem);
		ImageDestroy($img_final);

		if($img_final != '') return true;
		else				 return false;
	}
?>
