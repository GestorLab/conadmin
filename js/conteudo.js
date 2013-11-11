function busca_datas_especiais(Data,campo){
	if(Data == ''){
		Data = 0;
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
	
	url = "xml/datas_especiais.php?Data="+formatDate(Data);
   	
   	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 
	
		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(xmlhttp.responseText == 'false'){
					return false;
				}else{
					var nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoData")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					var DescricaoData = nameTextNode.nodeValue;
					
					var nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					var Cor = nameTextNode.nodeValue;
					
					campo.title					=	DescricaoData;
					campo.style.backgroundColor	=	Cor;
				}
			}
		} 
	}
	xmlhttp.send(null);
}
function busca_compromisso(Data,campo,dia){
	if(Data == ''){
		Data = 0;
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
	
	var IdAgenda;
	
	url = "xml/agenda.php?Data="+formatDate(Data);
	
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 
	
		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(xmlhttp.responseText == 'false'){
					campo.innerHTML 			= "<a href=\"cadastro_agenda.php?Data="+Data+"\">"+dia+"</a>";
				}else{
					var qtdCompromisso = xmlhttp.responseXML.getElementsByTagName("IdAgenda").length;

					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenda")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					IdAgenda = nameTextNode.nodeValue;
					
					
					dia = parseInt(dia);
					
					campo.style.border	=	'1px #004492 solid';
					campo.style.cursor	=	'pointer';
					campo.innerHTML 	= "<a href=\"javascript:agenda('"+Data+"')\">"+dia+"</a>";
					
					if(campo.title != ''){
						campo.title += "\n";
					}

					campo.title	+= qtdCompromisso;

					if(qtdCompromisso > 1){
						campo.title	+= " anotações.";
					}else{
						campo.title	+= " anotação.";
					}					
				}
			}
		} 
	}
	xmlhttp.send(null);
}

function legenda(mes,ano){
	if(mes == '' || ano==''){
		while(document.getElementById('Legenda').rows.length > 0){
			document.getElementById('Legenda').deleteRow(0);
		}
		return false;
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
	
	if(mes < 10)	mes = '0'+mes;
	
	url = "xml/legenda.php?mes="+mes+"&ano="+ano;

   	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 
	
		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(xmlhttp.responseText == 'false'){
					while(document.getElementById('Legenda').rows.length > 0){
						document.getElementById('Legenda').deleteRow(0);
					}
					return false;
				}else{
					while(document.getElementById('Legenda').rows.length > 0){
						document.getElementById('Legenda').deleteRow(0);
					}
					var DescricaoData, Cor; 
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DescricaoData").length; i++){	
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoData")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoData = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Cor = nameTextNode.nodeValue;
						
						if(i%2 == 0){
							tam 	= document.getElementById('Legenda').rows.length;
							linha	= document.getElementById('Legenda').insertRow(tam);
						
						
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);
						
							c0.innerHTML = '';
							c0.style.backgroundColor  =	Cor;
							c0.width			  = '10px;';
						
							c1.innerHTML 	 = DescricaoData;
							c1.width			  = '155px;';
						}else{
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
						
							c2.innerHTML = '';
							c2.style.backgroundColor  =	Cor;
							c2.width			  = '10px;';	
						
							c3.innerHTML 	 = DescricaoData;
						
						}
					}
				}
			}
		} 
	}
	xmlhttp.send(null);
}	

function agenda(DataBusca){
	if(DataBusca == ''){
		DataBusca = 	'busca';
	}else{
		DataBusca = formatDate(DataBusca);
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
	
	url = "xml/agenda.php?Data="+DataBusca;

   	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 
	
		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(xmlhttp.responseText == 'false'){
					while(document.getElementById('quadroAvisoAgenda').rows.length > 1){
						document.getElementById('quadroAvisoAgenda').deleteRow(1);
					}
					return false;
				}else{
					while(document.getElementById('quadroAvisoAgenda').rows.length > 1){
						document.getElementById('quadroAvisoAgenda').deleteRow(1);
					}
					var Nome, IdAgenda, Descricao, DataAgenda, Data, IdStatus, Titulo, Hora; 
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdAgenda").length; i++){	
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Nome = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenda")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdAgenda = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Descricao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAgenda")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataAgenda = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Data")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Data = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Hora")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Hora = nameTextNode.nodeValue;
						
						tam 	= document.getElementById('quadroAvisoAgenda').rows.length;
						linha	= document.getElementById('quadroAvisoAgenda').insertRow(tam);
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);
						c2	= linha.insertCell(2);
						
						LinkIni	=	"<a href='cadastro_agenda.php?IdAgenda="+IdAgenda+"'>";
						LinkFim	= 	"</a>";
						
						if(tam%2 != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						if(IdStatus == 2){
							linha.style.backgroundColor = "#A7E7A7";
						}else{
							var data 		= new Date();
		
							var day		= data.getDate();
							var month	= (data.getMonth()+1);
							var year 	= data.getFullYear();
							
							if(day < 10)   day   = '0'+day;
							if(month < 10) month = '0'+month;
							
							var hoje	= year+"-"+month+"-"+day;
							
							if( Data < hoje){
								linha.style.backgroundColor = "#FFD2D2";
							}
						}

						Titulo = "Data: " + DataAgenda + " " + Hora.substr(0,5);
						if(Nome != ''){	Titulo += "\n" + "Cliente: " + Nome;	}
						Titulo += "\n--------------------------------------------------------------------------------";
						Titulo += "\n" + Descricao;
						
						c0.innerHTML 		= LinkIni + DataAgenda + LinkFim;
						c0.style.textAlign	= "center";
						c0.style.width		=	"60px";
						c0.title			= Titulo;
						
						c1.innerHTML 		= LinkIni + Nome.substr(0,15) + LinkFim;
						c1.style.textAlign	= "left";
						c1.title			= Titulo;
						
						c2.innerHTML 		= LinkIni + Descricao.substr(0,22)+'...' + LinkFim;
						c2.style.textAlign	= "left";
						c2.title			= Titulo;
					}
				}
			}
		} 
	}
	xmlhttp.send(null);
}	

function inicia(){
	var qtdDias = 	new Array();
	var mes 	=	new Array();
	var mesP 	=	new Array();
	var m	 	=	new Array();
	
	var dte		= new Date();
	
	if(m=='' || ano==''){
		m 			= dte.getMonth();
		ano 		= dte.getFullYear();
	}
	
	
	qtdDias		=	[31,(ano%4 == 0) ? 29 : 28,31,30,31,30,31,31,30,31,30,31];	
	mes	  		= ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
	mesP	 	= ["jan","fev","mar","abr","mai","jun","jul","ago","set","out","nov","dez"];

	var data 		= new Date(ano,m,1);
	
	var iniSemana	= data.getDay();
	var quantDias	= qtdDias[m];
	
	var mesAnt	= parseInt(m), mesProx=parseInt(m), anoAnt=parseInt(ano), anoProx=parseInt(ano);
	
	mesAnt	=	parseInt(mesAnt)-1;	
	mesProx	=	parseInt(mesProx)+1;
	
	if(mesAnt < 0){		
		mesAnt  = 11;	
		anoAnt  = parseInt(anoAnt)-1;		
	}
	
	if(mesProx > 11){	
		mesProx = 0;	
		anoProx = parseInt(anoProx)+1;	
	}
	
	document.calendar.mesAnt.value	=	mesAnt;
	document.calendar.anoAnt.value	=	anoAnt;
	document.calendar.mesProx.value	=	mesProx;
	document.calendar.anoProx.value	=	anoProx;
	
	document.getElementById('Ant').innerHTML	=	mesP[mesAnt];
	document.getElementById('Atual').innerHTML	=	mes[m]+'/'+ano;
	document.getElementById('Prox').innerHTML	=	mesP[mesProx];
}
function diaMax(quantDias,ii){
	if(ii<=quantDias){
		return ii;
	}else{
		return "&nbsp;";
	}
}