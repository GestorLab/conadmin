<?
	$Path	= "../../";
	$Login	= 'automatico';

	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../classes/envia_mensagem/envia_mensagem.php");

	$Vars[IdTipoMensagem]	= 29;

	$Vars[IdLoja]	= $_GET['IdLoja'];
	$Vars[IdPessoa]	= $_GET['IdPessoa'];
	$Vars[Celular]	= $_GET['Celular'];
	$Vars[Conteudo]	= $_GET['Conteudo'];
	$Vars[key]		= $_GET['key'];

	$md5 = md5(md5($Vars[IdLoja].$Vars[IdPessoa].$Vars[Celular]));

	if($Vars[key] == $md5){
		if($Vars[IdLoja] != '' && $Vars[IdPessoa] != '' && $Vars[Celular] != '' && $Vars[Conteudo] != ''){
			geraMensagem($Vars);
		}else{
			echo "Dados inválidos!";
		}
	}else{
		echo "Chave inválida!";
	}
?>