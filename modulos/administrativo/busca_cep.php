<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
	</head>
	<body>
		<div id='tit'>Consulta de CEP</div>
		<div id='filtro_busca' style='height:200px'>
			<form name='Geral' onSubmit="return CriticaCampos();" action="http://www.correios.com.br/servicos/cep/Resultado_Log.cfm?RequestTimeout=50" target="_blank">
			<table style='margin-left:5px'>
				<tr>
					<td class='descCampo'>Estado</td>
					<td class='descCampo'>Cidade</td>
					</tr>
					<tr>
						<td class='campo'>
							<select name=UF style='width:50px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								<option value="AC">AC</option>
								<option value="AL">AL</option>
								<option value="AM">AM</option>
								<option value="AP">AP</option>
								<option value="BA">BA</option>
								<option value="CE">CE</option>
								<option value="DF">DF</option>
								<option value="ES">ES</option>
								<option value="GO">GO</option>
								<option value="MA">MA</option>
								<option value="MG">MG</option>
								<option value="MS">MS</option>
								<option value="MT">MT</option>
								<option value="PA">PA</option>
								<option value="PB">PB</option>
								<option value="PE">PE</option>
								<option value="PI">PI</option>
								<option value="PR">PR</option>
								<option value="RJ">RJ</option>
								<option value="RN">RN</option>
								<option value="RO">RO</option>
								<option value="RR">RR</option>
								<option value="RS">RS</option>
								<option value="SC">SC</option>
								<option value="SE">SE</option>
								<option value="SP">SP</option>
								<option value="TO">TO</option>
							</select>
						</td>
						<td>
							<input align=left maxLength=40 name=Localidade style='width:270px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
					</tr>
				</table>
				<table style='margin-left:5px'> 
					<tr>
						<td class='descCampo'>Tipo</td>
						<td class='descCampo'>Logradouro</td>
					</tr>														
					<tr> 
						<td class='campo'>
							<select name=Tipo onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								<option value=""></option>
								<option value="Avenida">Avenida</option>
								<option value="Bloco">Bloco</option>
								<option value="Praça">Praça</option>
								<option value="Quadra">Quadra</option>
								<option value="Rua">Rua</option>
								<option value="Outros">Outros</option>
							</select>
						</td>
						<td>
							<input align=left maxLength=60 name=Logradouro style='width:245px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeypress="if ((event.keyCode > 32 && event.keyCode < 40) || (event.keyCode > 41 && event.keyCode < 48) || (event.keyCode > 57 && event.keyCode < 65) || (event.keyCode > 90 && event.keyCode < 97)) event.returnValue = false;">
						</td>
					</tr>
				</table>
				<table style='margin-left:5px'>
					<tr>
						<td class='descCampo'>Nº/Lote/Apto/Casa</td>
					</tr>
					<tr>
						<td><input align=left maxlength=5 name=Numero size=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"></td>
					</tr>
				</table>
				<table style='margin-left:5px'>
					<tr>
						<td>
							<input type='Submit' value='Ok' class='botao'>
							<input type='button' value='Cancelar' onClick='window.close()' class='botao'>
						</td>
					</tr>
				</table>
			</form>
		</div>		
	</body>
</html>
<script>
	var valorCampo = '';
	function inicia(){
		document.Geral.UF.focus();
	}
	function CriticaCampos(){
    	if (document.Geral.Localidade.value == ""){
    		alert("Informe o nome completo da Cidade/Município/Distrito/Povoado. Para o DF poderá ser informado o nome da Região Administrativa (Lago Sul, Lago Norte, Cruzeiro, Taguatinga, etc) !!");
   			 document.Geral.Localidade.focus();
   			 return (false);
   		 }else{ 
		    var Branco = " ";
		    var Posic, Carac;
	   		var Temp = document.Geral.Localidade.value.length;    
	    	var Cont = 0;
	    	for (var i=0; i < Temp; i++){  
			   Carac =  document.Geral.Localidade.value.charAt (i);
	 		   Posic  = Branco.indexOf (Carac);   
	 		   if (Posic == -1)   
	 			   Cont++;      
	  		   }   
	  		   if (Cont <= 0){
	   				alert("Informe o nome completo da Cidade/Município/Distrito/Povoado. Para o DF poderá ser informado o nome da Região Administrativa (Lago Sul, Lago Norte, Cruzeiro, Taguatinga, etc) !!");
	    			document.Geral.Localidade.focus();
	   				return (false);
	           }   
	    }
	    if (document.Geral.Logradouro.value == ""){
		    alert("Informe o nome do logradouro");
		    document.Geral.Logradouro.focus();
	    	return (false);
	    }else{ 
		    var Branco = " ";
		    var Posic, Carac;
	    	var Temp = document.Geral.Logradouro.value.length;    
		    var Cont = 0;
		    for (var i=0; i < Temp; i++){  
			    Carac =  document.Geral.Logradouro.value.charAt (i);
	    		Posic  = Branco.indexOf (Carac);   
			    if (Posic == -1){   
	    			Cont++;      
	    		}
	    	}   
	    	if (Cont <= 0){
			    alert("Informe o nome do logradouro");
	   			document.Geral.Logradouro.focus();
	   			 return (false);
	    	}  
	    }
	    window.close();
	}   
	inicia();
</script>
