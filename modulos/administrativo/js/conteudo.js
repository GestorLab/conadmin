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
						
						c1.innerHTML 		= LinkIni + Nome.substr(0,20) + LinkFim;
						c1.style.textAlign	= "left";
						c1.title			= Titulo;
						
						c2.innerHTML 		= LinkIni + Descricao.substr(0,50) + LinkFim;
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

function busca_ordem_servico(Tabela,Local,CampoOrder,Order,vi_cidade,vi_tipo,vi_subtipo,IdGrupoUsuarioAtendimento){
	if(Tabela=='' && Local==''){
		return false;
	}
	
	var LocalBD = Local.split("_");
	LocalBD		= LocalBD[0];
	
	if(CampoOrder==undefined) 					CampoOrder='';
	if(Order==undefined) 	  					Order='';
	if(vi_cidade==undefined) 					vi_cidade='';
	if(vi_tipo==undefined) 	  					vi_tipo='';
	if(vi_subtipo==undefined) 					vi_subtipo='';
	if(IdGrupoUsuarioAtendimento==undefined)	IdGrupoUsuarioAtendimento='';
	
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
	
	url = "xml/ordem_servico_conteudo.php?CampoOrder="+CampoOrder+"&Local="+LocalBD+"&Order="+Order+"&IdGrupoUsuarioAtendimento="+IdGrupoUsuarioAtendimento;
   
   	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);
	
		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(xmlhttp.responseText == 'false'){
					document.getElementById(Local).style.display	=	'none';
					
					while(document.getElementById(Tabela).rows.length > 1){
						document.getElementById(Tabela).deleteRow(1);
					}
					
					// Carregando...
					carregando(false);
					return false;
				}else{
					document.getElementById(Local).style.display	=	'block';
					
					var IdOrdemServico,CorSubTipo,NomeCidade,SiglaEstado,Cidade,Nome,DescricaoOS,DescricaoTipoOrdemServico,DescricaoSubTipoOrdemServico;
					var LoginAtendimento,Status,DataAgendamentoAtendimento,IdMarcador,Marcador, DescricaoServico, descricao;
					
					while(document.getElementById(Tabela).rows.length > 0){
						document.getElementById(Tabela).deleteRow(0);
					}
					
					var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, imagem;
					
					if(Order == 'DESC')	OrderInversa	=	'ASC';
					else				OrderInversa	=	'DESC';
					
					
					tam 	= document.getElementById(Tabela).rows.length;
					linha	= document.getElementById(Tabela).insertRow(tam);
					
					c0	= linha.insertCell(0);	
					c1	= linha.insertCell(1);	
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);	
					c4	= linha.insertCell(4);	
					c5	= linha.insertCell(5);	
					c6	= linha.insertCell(6);	
					c7	= linha.insertCell(7);	
					c8	= linha.insertCell(8);	
					c9	= linha.insertCell(9);	
					
					
					if(CampoOrder == 'OrdemServico.IdOrdemServico'){
						if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
						else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
					}else{
						imagem	=	"";
					}
					
					c0.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.IdOrdemServico','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Id</B>"+imagem+"</a>";
					c0.style.cursor  = "pointer";
					c0.style.width = "30px";
					
					c1.innerHTML = '';
					
					if(CampoOrder == 'Pessoa.Nome'){
						if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
						else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
					}else{
						imagem	=	"";
					}
					
					c2.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','Pessoa.Nome','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Cliente</B>"+imagem+"</a>";
					c2.style.cursor  = "pointer";
					
					if(CampoOrder == 'OrdemServico.DescricaoOS'){
						if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
						else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
					}else{
						imagem	=	"";
					}
					
					c3.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.DescricaoOS','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Descrição</B>"+imagem+"</a>";
					c3.style.cursor  = "pointer";
					
					if(vi_cidade==1){
						c4	= linha.insertCell(4);
							
						if(CampoOrder == 'Cidade.NomeCidade'){
							if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
							else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
						}else{
							imagem	=	"";
						}
					
						c4.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','Cidade.NomeCidade','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Cidade</B>"+imagem+"</a>";
						c4.style.cursor  = "pointer";
						
						if(vi_tipo==1){
							c5	= linha.insertCell(5);
							
							if(CampoOrder == 'TipoOrdemServico.DescricaoTipoOrdemServico'){
								if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
								else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
							}else{
								imagem	=	"";
							}
					
							c5.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','TipoOrdemServico.DescricaoTipoOrdemServico','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Tipo</B>"+imagem+"</a>";
							c5.style.cursor  = "pointer";
							
							if(vi_subtipo==1){
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								c8	= linha.insertCell(8);
								c9	= linha.insertCell(9);
							
								if(CampoOrder == 'SubTipoOrdemServico.DescricaoSubTipoOrdemServico'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c6.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','SubTipoOrdemServico.DescricaoSubTipoOrdemServico','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>SubTipo</B>"+imagem+"</a>";
								c6.style.cursor  = "pointer";
								
								
								if(CampoOrder == 'OrdemServico.LoginAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c7.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.LoginAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Atend.</B>"+imagem+"</a>";
								c7.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.DataAgendamentoAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c8.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.DataAgendamentoAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Data</B>"+imagem+"</a>";
								c8.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.IdStatus'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c9.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.IdStatus','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Status</B>"+imagem+"</a>";
								c9.style.cursor  = "pointer";
							}else{
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								c8	= linha.insertCell(8);
							
								if(CampoOrder == 'OrdemServico.LoginAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c6.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.LoginAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Atend.</B>"+imagem+"</a>";
								c6.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.DataAgendamentoAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c7.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.DataAgendamentoAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Data</B>"+imagem+"</a>";
								c7.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.IdStatus'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c8.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.IdStatus','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Status</B>"+imagem+"</a>";
								c8.style.cursor  = "pointer";
							}
						}else{
							if(vi_subtipo==1){
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								c8	= linha.insertCell(8);
							
								if(CampoOrder == 'SubTipoOrdemServico.DescricaoSubTipoOrdemServico'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c5.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','SubTipoOrdemServico.DescricaoSubTipoOrdemServico','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>SubTipo</B>"+imagem+"</a>";
								c5.style.cursor  = "pointer";
								
								
								if(CampoOrder == 'OrdemServico.LoginAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c6.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.LoginAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Atend.</B>"+imagem+"</a>";
								c6.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.DataAgendamentoAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c7.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.DataAgendamentoAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Data</B>"+imagem+"</a>";
								c7.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.IdStatus'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c8.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.IdStatus','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Status</B>"+imagem+"</a>";
								c8.style.cursor  = "pointer";
							}else{
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
							
								if(CampoOrder == 'OrdemServico.LoginAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c5.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.LoginAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Atend.</B>"+imagem+"</a>";
								c5.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.DataAgendamentoAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c6.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.DataAgendamentoAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Data</B>"+imagem+"</a>";
								c6.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.IdStatus'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c7.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.IdStatus','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Status</B>"+imagem+"</a>";
								c7.style.cursor  = "pointer";
							}
						}
					}else{
						if(vi_tipo==1){
							c4	= linha.insertCell(4);
							
							if(CampoOrder == 'TipoOrdemServico.DescricaoTipoOrdemServico'){
								if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
								else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
							}else{
								imagem	=	"";
							}
					
							c4.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','TipoOrdemServico.DescricaoTipoOrdemServico','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Tipo</B>"+imagem+"</a>";
							c4.style.cursor  = "pointer";
							
							if(vi_subtipo==1){
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								c8	= linha.insertCell(8);
							
								if(CampoOrder == 'SubTipoOrdemServico.DescricaoSubTipoOrdemServico'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c5.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','SubTipoOrdemServico.DescricaoSubTipoOrdemServico','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>SubTipo</B>"+imagem+"</a>";
								c5.style.cursor  = "pointer";
								
								
								if(CampoOrder == 'OrdemServico.LoginAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c6.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.LoginAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Atend.</B>"+imagem+"</a>";
								c6.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.DataAgendamentoAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c7.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.DataAgendamentoAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Data</B>"+imagem+"</a>";
								c7.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.IdStatus'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c8.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.IdStatus','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Status</B>"+imagem+"</a>";
								c8.style.cursor  = "pointer";
							}else{
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
							
								if(CampoOrder == 'OrdemServico.LoginAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c5.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.LoginAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Atend.</B>"+imagem+"</a>";
								c5.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.DataAgendamentoAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c6.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.DataAgendamentoAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Data</B>"+imagem+"</a>";
								c6.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.IdStatus'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c7.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.IdStatus','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Status</B>"+imagem+"</a>";
								c7.style.cursor  = "pointer";
							}
						}else{
							if(vi_subtipo==1){
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
							
								if(CampoOrder == 'SubTipoOrdemServico.DescricaoSubTipoOrdemServico'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c4.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','SubTipoOrdemServico.DescricaoSubTipoOrdemServico','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>SubTipo</B>"+imagem+"</a>";
								c4.style.cursor  = "pointer";
								
								
								if(CampoOrder == 'OrdemServico.LoginAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c5.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.LoginAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Atend.</B>"+imagem+"</a>";
								c5.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.DataAgendamentoAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c6.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.DataAgendamentoAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Data</B>"+imagem+"</a>";
								c6.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.IdStatus'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c7.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.IdStatus','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Status</B>"+imagem+"</a>";
								c7.style.cursor  = "pointer";
							}else{
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
							
								if(CampoOrder == 'OrdemServico.LoginAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c4.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.LoginAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Atend.</B>"+imagem+"</a>";
								c4.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.DataAgendamentoAtendimento'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c5.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.DataAgendamentoAtendimento','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Data</B>"+imagem+"</a>";
								c5.style.cursor  = "pointer";
								
								if(CampoOrder == 'OrdemServico.IdStatus'){
									if(Order == 'ASC')	imagem	=	"<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
									else				imagem	=	"<img src='../../img/estrutura_sistema/seta_descending.gif'>";
								}else{
									imagem	=	"";
								}
					
								c6.innerHTML = "<a onClick=\"busca_ordem_servico('"+Tabela+"','"+Local+"','OrdemServico.IdStatus','"+OrderInversa+"','"+vi_cidade+"','"+vi_tipo+"','"+vi_subtipo+"','"+IdGrupoUsuarioAtendimento+"')\"><B>Status</B>"+imagem+"</a>";
								c6.style.cursor  = "pointer";
							}
						}
					}
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdOrdemServico").length; i++){	
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CorSubTipo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						CorSubTipo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Nome = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoOS")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoOS = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
						nameTextNode = nameNode.childNodes[0];
						NomeCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						SiglaEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipoOrdemServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoTipoOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoOrdemServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoSubTipoOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAtendimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						LoginAtendimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAgendamentoAtendimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataAgendamentoAtendimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Status = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdMarcador")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdMarcador = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Marcador")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Marcador = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoServico = nameTextNode.nodeValue;
						
						tam 	= document.getElementById('quadroAvisoOrdem_'+Local).rows.length;
						linha	= document.getElementById('quadroAvisoOrdem_'+Local).insertRow(tam);
						
						if(CorSubTipo!=""){
							linha.style.backgroundColor =	CorSubTipo;
						}
						
						if(NomeCidade!=""){
							Cidade	=	NomeCidade+"-"+SiglaEstado;
						}else{
							Cidade	=	"";
						}
						
						if(DataAgendamentoAtendimento!=""){
							var year 	= DataAgendamentoAtendimento.substring(0,4);
							var month 	= DataAgendamentoAtendimento.substring(5,7);
							var day 	= DataAgendamentoAtendimento.substring(8,10);
							var end 	= DataAgendamentoAtendimento.substring(11,DataAgendamentoAtendimento.length);
							
							var date = day + "/" + month + "/" + year.substring(2,4);
							
							if(end != ''){
								date = date + " " + end.substring(0,5);
							}
							
							DataAgendamentoAtendimento	=	date;
						}
						
						linha.accessKey = IdOrdemServico; 
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);	
						c2	= linha.insertCell(2);	
						c3	= linha.insertCell(3);	
						
						var linkIni = "<a href='cadastro_ordem_servico.php?IdOrdemServico="+IdOrdemServico+"'>";
						var linkFim = "</a>";
						
						c0.innerHTML = linkIni + IdOrdemServico + linkFim;
						c0.style.cursor  = "pointer";
						
						switch(IdMarcador){
							case '1':
								c1.innerHTML = linkIni + '<font color=\"#FF0000"\>&#8226;' + linkFim;
								c1.alt   = Marcador;
								c1.title = Marcador;
								c1.style.padding	=	"2px";
								c1.style.width		=	"14px";
								break;
							case '2':
								c1.innerHTML = linkIni + '<font color=\"#F9F900"\>&#8226;' + linkFim;
								c1.alt   = Marcador;
								c1.title = Marcador;
								c1.style.padding	=	"2px";
								c1.style.width		=	"14px";
								break;	
							case '3':
								c1.innerHTML = linkIni + '<font color=\"#008000"\>&#8226;' + linkFim;
								c1.alt   = Marcador;
								c1.title = Marcador;
								c1.style.padding	=	"2px";
								c1.style.width		=	"14px";
								break;
							default:
								c1.innerHTML 		= '&nbsp;';
						}	
						
						c2.innerHTML = linkIni + Nome.substr(0,15) + linkFim;
						c2.style.cursor = "pointer";
						c2.alt			= Nome;
						c2.title		= Nome;
						
						if(DescricaoServico!=""){
							descricao	=	'Descrição Serviço: '+DescricaoServico+"\n\nDescrição OS: "+DescricaoOS;
						}else{
							descricao	=	'Descrição OS: '+DescricaoOS;
						}
						
						c3.innerHTML = linkIni + DescricaoOS.substr(0,45) + linkFim;
						c3.style.cursor = "pointer";
						c3.alt			= descricao;
						c3.title		= descricao;
							
						if(vi_cidade==1){
							c4	= linha.insertCell(4);
							
							c4.innerHTML = linkIni + Cidade + linkFim;
							c4.style.cursor = "pointer";
							
							if(vi_tipo == 1){
								c5	= linha.insertCell(5);
							
								c5.innerHTML = linkIni + DescricaoTipoOrdemServico + linkFim;
								c5.style.cursor = "pointer";
								
								if(vi_subtipo == 1){
									c6	= linha.insertCell(6);
									c7	= linha.insertCell(7);
									c8	= linha.insertCell(8);
									c9	= linha.insertCell(9);
									
									c6.innerHTML = linkIni + DescricaoSubTipoOrdemServico + linkFim;
									c6.style.cursor = "pointer";
									
									c7.innerHTML = linkIni + LoginAtendimento + linkFim;
									c7.style.cursor = "pointer";
									
									c8.innerHTML = linkIni + DataAgendamentoAtendimento + linkFim;
									c8.style.cursor = "pointer";
									
									c9.innerHTML = linkIni + Status + linkFim;
									c9.style.cursor = "pointer";
								}else{
									c6	= linha.insertCell(6);
									c7	= linha.insertCell(7);
									c8	= linha.insertCell(8);
										
									c6.innerHTML = linkIni + LoginAtendimento + linkFim;
									c6.style.cursor = "pointer";
										
									c7.innerHTML = linkIni + DataAgendamentoAtendimento + linkFim;
									c8.style.cursor = "pointer";
										
									c8.innerHTML = linkIni + Status + linkFim;
									c8.style.cursor = "pointer";
								}
							}else{
								if(vi_subtipo == 1){
									c5	= linha.insertCell(5);
									c6	= linha.insertCell(6);
									c7	= linha.insertCell(7);
									c8	= linha.insertCell(8);
									
									c5.innerHTML = linkIni + DescricaoSubTipoOrdemServico + linkFim;
									c5.style.cursor = "pointer";
									
									c6.innerHTML = linkIni + LoginAtendimento + linkFim;
									c6.style.cursor = "pointer";
									
									c7.innerHTML = linkIni + DataAgendamentoAtendimento + linkFim;
									c7.style.cursor = "pointer";
									
									c8.innerHTML = linkIni + Status + linkFim;
									c8.style.cursor = "pointer";
								}else{
									c5	= linha.insertCell(5);
									c6	= linha.insertCell(6);
									c7	= linha.insertCell(7);
										
									c5.innerHTML = linkIni + LoginAtendimento + linkFim;
									c5.style.cursor = "pointer";
										
									c6.innerHTML = linkIni + DataAgendamentoAtendimento + linkFim;
									c6.style.cursor = "pointer";
										
									c7.innerHTML = linkIni + Status + linkFim;
									c7.style.cursor = "pointer";
								}
							}
						}else{
							if(vi_tipo == 1){
								c4	= linha.insertCell(4);
							
								c4.innerHTML = linkIni + DescricaoTipoOrdemServico + linkFim;
								c4.style.cursor = "pointer";
								
								if(vi_subtipo == 1){
									c5	= linha.insertCell(5);
									c6	= linha.insertCell(6);
									c7	= linha.insertCell(7);
									c8	= linha.insertCell(8);
									
									c5.innerHTML = linkIni + DescricaoSubTipoOrdemServico + linkFim;
									c5.style.cursor = "pointer";
									
									c6.innerHTML = linkIni + LoginAtendimento + linkFim;
									c6.style.cursor = "pointer";
									
									c7.innerHTML = linkIni + DataAgendamentoAtendimento + linkFim;
									c7.style.cursor = "pointer";
									
									c8.innerHTML = linkIni + Status + linkFim;
									c8.style.cursor = "pointer";	
								}else{
									c5	= linha.insertCell(5);
									c6	= linha.insertCell(6);
									c7	= linha.insertCell(7);
									
									c5.innerHTML = linkIni + LoginAtendimento + linkFim;
									c5.style.cursor = "pointer";
									
									c6.innerHTML = linkIni + DataAgendamentoAtendimento + linkFim;
									c6.style.cursor = "pointer";
									
									c7.innerHTML = linkIni + Status + linkFim;
									c7.style.cursor = "pointer";
								}
							}else{
								if(vi_subtipo == 1){
									c4	= linha.insertCell(4);
									c5	= linha.insertCell(5);
									c6	= linha.insertCell(6);
									c7	= linha.insertCell(7);
									
									c4.innerHTML = linkIni + DescricaoSubTipoOrdemServico + linkFim;
									c4.style.cursor = "pointer";
									
									c5.innerHTML = linkIni + LoginAtendimento + linkFim;
									c5.style.cursor = "pointer";
									
									c6.innerHTML = linkIni + DataAgendamentoAtendimento + linkFim;
									c6.style.cursor = "pointer";
									
									c7.innerHTML = linkIni + Status + linkFim;
									c7.style.cursor = "pointer";
								}else{
									c4	= linha.insertCell(4);
									c5	= linha.insertCell(5);
									c6	= linha.insertCell(6);
									
									c4.innerHTML = linkIni + LoginAtendimento + linkFim;
									c4.style.cursor = "pointer";
									
									c5.innerHTML = linkIni + DataAgendamentoAtendimento + linkFim;
									c5.style.cursor = "pointer";
									
									c6.innerHTML = linkIni + Status + linkFim;
									c6.style.cursor = "pointer";
								}
							}
						}						
						
					}
				}
			}
			carregando(false);
		}
		return true;
	}
	xmlhttp.send(null);
}

function buscar_quadro_help_desk(IdPessoa, OrderBy, CampoOrderBy){
	if(IdPessoa == undefined || IdPessoa==''){
		IdPessoa = 0;
	}
	
	if(CampoOrderBy == undefined || CampoOrderBy == ''){
		CampoOrderBy = "";
	}
	
	if(document.getElementById(CampoOrderBy)){
		if(document.getElementById(CampoOrderBy).innerHTML == ''){
			OrderBy = "";
		}
	}
	
	var nameNode, nameTextNode, url, Condicao;
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
    
   	url = "xml/buscar_quadro_help_desk.php?IdPessoa="+IdPessoa+"&OrderBy="+OrderBy+"&CampoOrderBy="+CampoOrderBy;
	xmlhttp.open("GET", url,true);

	xmlhttp.onreadystatechange = function(){
		// Carregando...
		document.getElementById('quadroHelpDesk').style.display		= "block";
		document.getElementById('carregandoQuadro').style.display	= "block";
		
		if(xmlhttp.readyState == 4){
			if(xmlhttp.status == 200){
				//alert(xmlhttp.responseText);
				if(xmlhttp.responseText == 'false'){
					document.getElementById('quadroHelpDesk').style.display	= "none";
					
					// Fim de Carregando
					document.getElementById('carregandoQuadro').style.display	= "none";
				}else{
					if(OrderBy == "ASC"){
						ImagemSeta = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
						OrderBy = "DESC";
					} else{
						ImagemSeta = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";
						OrderBy = "ASC";
					}
					
					while(document.getElementById('tabelaQuadroHelpDesk').rows.length > 0){
						document.getElementById('tabelaQuadroHelpDesk').deleteRow(0);
					}
					
					var cabecalho, ImagemSeta, tam, linha, c0, c1, c2, c3, c4, c5, c6;
					
					tam 	= document.getElementById('tabelaQuadroHelpDesk').rows.length;
					linha	= document.getElementById('tabelaQuadroHelpDesk').insertRow(tam);
					
					linha.accessKey = IdTicket; 
					
					c0	= linha.insertCell(0);
					c1	= linha.insertCell(1);
					c2	= linha.insertCell(2);
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					c6	= linha.insertCell(6);
					
					c0.innerHTML = "<a href=\"javascript: buscar_quadro_help_desk('" + IdPessoa + "','"+OrderBy+"','ImagemSeta_0')\"><b>Id</b><span id='ImagemSeta_0'></span></a>";
					c0.style.width = "40px";
					
					c1.innerHTML = "<a href=\"javascript: buscar_quadro_help_desk('" + IdPessoa + "','"+OrderBy+"','ImagemSeta_1')\"><b>Tipo/SubTipo</b><span id='ImagemSeta_1'></span></a>";
					
					c2.innerHTML = "<a href=\"javascript: buscar_quadro_help_desk('" + IdPessoa + "','"+OrderBy+"','ImagemSeta_2')\"><b>Assunto</b><span id='ImagemSeta_2'></span></a>";
					
					c3.innerHTML = "<a href=\"javascript: buscar_quadro_help_desk('" + IdPessoa + "','"+OrderBy+"','ImagemSeta_3')\"><b>Usuário Cadastro</b><span id='ImagemSeta_3'></span></a>";
					
					c4.innerHTML = "<a href=\"javascript: buscar_quadro_help_desk('" + IdPessoa + "','"+OrderBy+"','ImagemSeta_4')\"><b>Data de Aber.</b><span id='ImagemSeta_4'></span></a>";
					c4.style.width = "92px";
					
					c5.innerHTML = "<a href=\"javascript: buscar_quadro_help_desk('" + IdPessoa + "','"+OrderBy+"','ImagemSeta_5')\"><b>Previsão</b><span id='ImagemSeta_5'></span></a>";
					c5.style.width = "72px";
					
					c6.innerHTML = "<a href=\"javascript: buscar_quadro_help_desk('" + IdPessoa + "','"+OrderBy+"','ImagemSeta_6')\"><b>Status</b><span id='ImagemSeta_6'></span></a>";
					c6.style.width = "170px";
					
					if(CampoOrderBy != ''){
						document.getElementById(CampoOrderBy).innerHTML = ImagemSeta;
					}else{
						document.getElementById("ImagemSeta_0").innerHTML = ImagemSeta;
					}
					
					var IdTicket, TipoSubTipo, TipoSubTipoTemp, Assunto, AssuntoTemp, LoginCriacao, DataCriacao, DataHoraTemp, PrevisaoEtapa, Cor, Status, linkIni, linkFim;
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTicket").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTicket")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdTicket = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("TipoSubTipo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						TipoSubTipo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("TipoSubTipoTemp")[i]; 
						nameTextNode = nameNode.childNodes[0];
						TipoSubTipoTemp = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Assunto")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Assunto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("AssuntoTemp")[i]; 
						nameTextNode = nameNode.childNodes[0];
						AssuntoTemp = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						LoginCriacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataCriacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataHoraTemp")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataHoraTemp = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("PrevisaoEtapa")[i]; 
						nameTextNode = nameNode.childNodes[0];
						PrevisaoEtapa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Cor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Status = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MD5")[i]; 
						nameTextNode = nameNode.childNodes[0];
						MD5 = nameTextNode.nodeValue;
						
						aberta_quadro_help_desk(document.getElementById("botao_aberta_quadro_help_desk"));
						
						tam 	= document.getElementById('tabelaQuadroHelpDesk').rows.length;
						linha	= document.getElementById('tabelaQuadroHelpDesk').insertRow(tam);
						
						if(Cor != ''){
							linha.style.backgroundColor = Cor;
						}
						
						linha.accessKey = IdTicket; 
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);	
						c2	= linha.insertCell(2);	
						c3	= linha.insertCell(3);	
						c4	= linha.insertCell(4);
						c5	= linha.insertCell(5);
						c6	= linha.insertCell(6);
						
						linkIni	= "<a href='../../modulos/helpdesk/direciona/direciona_help_desk.php?Ticket="+MD5+"' target='_blank' ";
						linkFim	= "</a>";
						
						c0.innerHTML = linkIni + ">" + IdTicket + linkFim;
						
						c1.innerHTML = linkIni + "onmousemove=\"quadro_alt(event, this, '" + TipoSubTipo + "');\">" + TipoSubTipoTemp + linkFim;
						
						c2.innerHTML = linkIni + "onmousemove=\"quadro_alt(event, this, '" + Assunto + "');\">" + AssuntoTemp + linkFim;
						
						c3.innerHTML = linkIni + ">" + LoginCriacao + linkFim;
						
						c4.innerHTML = linkIni + ">" + DataHoraTemp + linkFim;
						
						c5.innerHTML = linkIni + ">" + PrevisaoEtapa + linkFim;
						
						c6.innerHTML = linkIni + ">" + Status + linkFim;
					}
				}
				if(window.janela != undefined){
					window.janela.close();
				}
			}
			// Fim de Carregando
			document.getElementById('carregandoQuadro').style.display	= "none";
			document.getElementById('tabelaHelpDesk').style.display		= "block";
		} 
		return true;
	}
	xmlhttp.send(null);
}

function aberta_quadro_help_desk(botao, Alterar) {
	if(Alterar == undefined){
		Alterar = 0;
	}
	
	var nameNode, nameTextNode, url;
	var xmlhttp = false;
	
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
    
   	url = "xml/aberta_quadro_help_desk.php?Alterar="+Alterar;
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4){
			if(xmlhttp.status == 200){
				if(parseInt(xmlhttp.responseText) == 0) {
					botao.src = "../../img/estrutura_sistema/ico_seta_down.gif";
					botao.title = "Maximizar";
					botao.alt = "Maximizar";
					document.getElementById("tabelaHelpDesk").style.display = "none";
				} else{
					botao.src = "../../img/estrutura_sistema/ico_seta_up.gif";
					botao.title = "Minimizar";
					botao.alt = "Minimizar";
					document.getElementById("tabelaHelpDesk").style.display = "block";
				}
			}
		} 
		return true;
	}
	
	xmlhttp.send(null);
}