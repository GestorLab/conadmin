	function inicia(){
		document.formulario.IdServico.focus();
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'inserir'){
				document.formulario.bt_alterar.disabled 	= true;
			}
			if(document.formulario.Acao.value == 'alterar'){
				document.formulario.bt_alterar.disabled 	= false;
			}
		}	
	}
	function busca_aliquota_estado(IdPais,Erro,Local){
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		if(IdPais == ''){
			IdPais = 0;
		}	
		var nameNode, nameTextNode, url;
		
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
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
	    
	   	url = "../administrativo/xml/estado.php?IdPais="+IdPais;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(Erro != false){
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){					
						// Fim de Carregando
						carregando(false);
					}else{
						var tam, linha, aliquota, tabindex = 0;
						var c0, c1, c2, c3, c4, c5;						
													
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdEstado").length; i++){	
							
							tam 	= document.getElementById('tableAliquota').rows.length;	
										
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEstado = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var SiglaEstado = nameTextNode.nodeValue;
							
							linha	= document.getElementById('tableAliquota').insertRow(tam);

							c0	= linha.insertCell(0);
							c1	= linha.insertCell(1);
							c2	= linha.insertCell(2);
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							
							c0.innerHTML = '&nbsp;';
						
							c1.innerHTML = "<input  type='hidden' name='IdEstado_"+document.formulario.IdPais.value+'_'+IdEstado+"' value='"+IdEstado+"' maxlength='100' readOnly><input type='text' name='Estado_"+IdEstado+"' value='"+SiglaEstado+"' style='width:35px' maxlength='2' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+1)+"' readOnly>"; 
							
							c2.innerHTML = '&nbsp;';
						
							c3.innerHTML = "<input type='text' name='AliquotaICMS_"+document.formulario.IdPais.value+'_'+IdEstado+"' value='0,0000' style='width:150px' maxlength='8' onkeypress=\"mascara_float(this,event)\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+2)+"'>";
							
							c4.innerHTML = "&nbsp;";
						
							c5.innerHTML = "<input type='text' name='FatorBaseCalculoAliquotaICMS_"+document.formulario.IdPais.value+'_'+IdEstado+"' value='1,0000' style='width:150px' maxlength='10' onkeypress=\"mascara_float(this,event)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+2)+"'>"; 						
							tabindex++;
						}									
					}					
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	
	function validar(){	
		if(document.formulario.IdServico.value==""){
			mensagens(1);
			document.formulario.IdServico.focus();
			return false;
		}
		
		if(document.formulario.IdAliquotaTipo.value==""){
			mensagens(1);
			document.formulario.IdAliquotaTipo.focus();
			return false;
		}
		
		for(j=0; j<document.formulario.length; j++){
			if(document.formulario[j].name.substr(0,28) == "FatorBaseCalculoAliquotaICMS"){														
				if(document.formulario[j].value == ""){			
					mensagens(1);
					document.formulario[j].focus();
					return false;		
				}						
			}
		}	
		return true;
	}
	function verificar_tipo_aliquota(IdAliquotaTipo){
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,13) == 'AliquotaICMS_'){
					if(document.formulario.IdCategoriaTributaria.value == 1 && IdAliquotaTipo == 1){
						document.formulario[i].value = '';
						document.formulario[i].readOnly = true;
					} else{
						document.formulario[i].value = '0,0000';
						document.formulario[i].readOnly = false;
					}
				}
				if(IdAliquotaTipo == 4){
					if(document.formulario[i].name.substring(0,13) == 'AliquotaICMS_'){
						document.formulario[i].value = '';
						document.formulario[i].readOnly = true;
					}
					if(document.formulario[i].name.substring(0,29) == 'FatorBaseCalculoAliquotaICMS_'){
						document.formulario[i].value = '0,0000';
					}
				}
				else{
					if(document.formulario[i].name.substring(0,29) == 'FatorBaseCalculoAliquotaICMS_'){
						document.formulario[i].value = '1,0000';
					}
				}
			}
		}
	}