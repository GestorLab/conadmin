	function excluir(IdAgenda){
		if(IdAgenda == ''){
			var IdAgenda = document.formulario.IdAgenda.value;
		}
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
			}
			var xmlhttp   = false;
			if (window.XMLHttpRequest) { // Mozilla, Safari,...
    			xmlhttp = new XMLHttpRequest();
		        if(xmlhttp.overrideMimeType){
		    //    	xmlhttp.overrideMimeType('text/xml');
				}
			}else if (window.ActiveXObject){ // IE
				try{
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}catch(e){
					try{
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		            } catch (e) {}
     		   }
    		}
    
   			url = "files/excluir/excluir_agenda.php?IdAgenda="+IdAgenda;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							//Salert(xmlhttp.responseText);
							document.formulario.Erro.value = xmlhttp.responseText;
							if(parseInt(xmlhttp.responseText) == 7){
								document.formulario.Acao.value 	= 'inserir';
								url = 'cadastro_agenda.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdAgenda == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}//if
								}//for	
								if(aux=1){
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
								}							
							}//if
						}//else
					}//if
					// Fim de Carregando
					carregando(false);
				}//if
				return true;
			}
			xmlhttp.send(null);
		}
	} 
	function validar(){
		if(document.formulario.Data.value == ''){
			mensagens(1);
			document.formulario.Data.focus();
			return false;
		}else{
			if(isData(document.formulario.Data.value) == false){		
				mensagens(27);
				document.formulario.Data.focus();
				return false;
			}
		}
		if(document.formulario.Hora.value != ''){
			if(isTime(document.formulario.Hora.value) == false){		
				mensagens(28);
				document.formulario.Hora.focus();
				return false;
			}
		}
		if(document.formulario.IdStatus.value == '0'){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
		if(document.formulario.Descricao.value == ''){
			mensagens(1);
			document.formulario.Descricao.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdServico.focus();
		tabela_nova();
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
			}
		}	
	}
	function tabela_nova(){
		var CountMes = parseInt(document.formulario.CountMes.value) + 1;
		
		var tam = document.getElementById('tabelaMascara').rows.length;
		var l1	= document.getElementById('tabelaMascara').insertRow(tam);
		
		l1.accessKey = CountMes;
		
		var c0	= l1.insertCell(0);
		var c1	= l1.insertCell(1);
		var c2	= l1.insertCell(2);
		var c3	= l1.insertCell(3);
		var c4	= l1.insertCell(4);
										
		var c0	= l1.insertCell(0);
		var c1	= l1.insertCell(1);
		var c2	= l1.insertCell(2);
		var c3	= l1.insertCell(3);
		var c4	= l1.insertCell(4);
										
		c0.innerHTML	= "<img src='../../img/estrutura_sistema/ico_excl.gif' alt='Excluir' onClick=\"excluir_foto("+CountFoto+",'"+IdGaleriaFoto+"')\" style='cursor:pointer; padding:0 5px 0 5px; margin-bottom:3px'>";
		c1.innerHTML	= "<input type='hidden' name='tempEndFoto"+CountFoto+"' id='tempEndFoto"+CountFoto+"' value=''><input type='file' id='EndFoto"+CountFoto+"' name='EndFoto"+CountFoto+"' value='' style='width:250px; margin:0 10px 5px 0;' onChange=\"document.formulario.tempEndFoto"+CountFoto+".value=document.formulario.EndFoto"+CountFoto+".value; ativa_imagem(document.formulario.tempEndFoto"+CountFoto+".value,document.formulario.DescricaoFoto"+CountFoto+".value)\" tabindex="+tabindex+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\">";
		c2.innerHTML	= "<input type='text' name='FotoAutor"+CountFoto+"' value='' style='width:220px; margin:0 10px 5px 0;' maxlength='30' tabindex="+parseInt(tabindex+1)+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\">";
		c3.innerHTML	= "<input type='text' name='DescricaoFoto"+CountFoto+"' value='' style='width:291px; margin:0 2px 5px 0;' maxlength='100' tabindex="+parseInt(tabindex+1)+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\">";
		c4.innerHTML	= "<img style='cursor:pointer; margin-bottom:5px' name='bt_lampada"+CountFoto+"' src='../../img/estrutura_sistema/ico_foto.gif' alt='Ver Foto' onClick=\"ativa_imagem(document.getElementById('tempEndFoto"+CountFoto+"').value,document.formulario.DescricaoFoto"+CountFoto+".value)\">";
	}
