	function excluir(IdContaReceber){
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
    
   			url = "files/excluir/excluir_conta_receber.php?IdContaReceber="+IdContaReceber;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						var numMsg = parseInt(xmlhttp.responseText);
						mensagens(numMsg);
						if(numMsg == 7){
							var aux = 0;
							for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
								if(IdContaReceber == document.getElementById('tableListar').rows[i].accessKey){
									document.getElementById('tableListar').deleteRow(i);
									tableMultColor('tableListar',document.filtro.corRegRand.value);
									aux=1;
									break;
								}
							}
							if(aux=1){
								var valor=0,receb=0;
								for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
									temp	=	document.getElementById('tableListar').rows[i].cells[5].innerHTML.split(">");
									temp1	=	temp[1].split("<");
									temp2	=	temp1[0].replace(',','.');
									if(temp2=='') temp2 = 0;
									valor	+=	parseFloat(temp2);
										
									temp	=	document.getElementById('tableListar').rows[i].cells[7].innerHTML.split(">");
									temp1	=	temp[1].split("<");
									temp2	=	temp1[0].replace(',','.');
									if(temp2=='') temp2 = 0;
									receb	+=	parseFloat(temp2);
								}
								document.getElementById('tableListarValor').innerHTML		=	formata_float(Arredonda(valor,2),2).replace('.',',');	
								document.getElementById('tableListarReceb').innerHTML		=	formata_float(Arredonda(receb,2),2).replace('.',',');	
								document.getElementById("tableListarTotal").innerHTML		=	"Total: "+(document.getElementById('tableListar').rows.length-2);
							}									
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}
	}
	function busca_pessoa_aproximada(campo,event){
		var url = "xml/pessoa_nome.php?Nome="+campo.value;
		
		call_ajax(url,function (xmlhttp){
			var NomeDefault = new Array(), nameNode, nameTextNode;
			
			if(campo.value != '' && xmlhttp.responseText != "false"){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("NomeDefault").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeDefault[i] = nameTextNode.nodeValue;
				}
			}
			
			busca_aproximada('filtro',campo,event,NomeDefault,22,111);
		},false);
	}