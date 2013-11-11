	
	function inicia(){
		add_linha_table();
		add_pagina();
	}
	function add_linha_table(){
		var pagina			=	document.formulario.QTDPagina.value;
		
		if(pagina == "") pagina = 0;
		
		pagina	=	parseInt(pagina) + 1;
		
		tam 	= document.getElementById('table_etiqueta').rows.length;
		linha	= document.getElementById('table_etiqueta').insertRow(tam);
		
		c0	= linha.insertCell(0);	
		c1	= linha.insertCell(1);	
		c2	= linha.insertCell(2);
			
		c0.innerHTML = "<div id='pagina_"+(pagina)+"'></div>";
		c1.innerHTML = "<div id='pagina_"+(pagina+1)+"'></div>";
		c2.innerHTML = "<div id='pagina_"+(pagina+2)+"'></div>";
	}
	function add_pagina(){
		var corpo			=	"";
		var qtd_linha		=	document.formulario.QTDLinha.value;
		var qtd_coluna		=	document.formulario.QTDColuna.value;
		var pagina			=	document.formulario.QTDPagina.value;
		var qtd_etiqueta	=	document.formulario.QTDEtiqueta.value;
		var TotalEtiqueta	=	document.formulario.TotalEtiqueta.value;
		
		var	i,ii,visivel,disabled,tam,linha;
		
		if(pagina == "") 		pagina = 0;
		if(qtd_etiqueta == "")  qtd_etiqueta = 0;
		
		pagina	=	parseInt(pagina) + 1;

		if(pagina == 1){
			visivel  = "block";
			disabled = "disabled";
		}else{
			visivel  = "none";
			disabled = "";
		}
		
		if((TotalEtiqueta-qtd_etiqueta) > (qtd_linha*qtd_coluna)){
			selecionadoTotal	=	"checked";
		}else{
			selecionadoTotal	=	"";
		}
		
		corpo	+="<div style='float:left; border:1px #004492 solid; width:230px; margin:10px; text-align:center; display:block'>";
		corpo	+="<p class='tit' style='margin-top:0;'>PÁGINA "+pagina+"</p>";
		corpo	+="<p><input type='checkbox' name='todos_"+pagina+"' style='border:0' onClick=\"verifica_pagina('todos',"+pagina+",this)\" "+selecionadoTotal+" "+disabled+">&nbsp;Todas</p>";
		corpo	+="<center><table>";
		
		for(i=0;i<qtd_linha;i++){
			corpo	+="<tr>";
			for(ii=0;ii<qtd_coluna;ii++){
				qtd_etiqueta	=	parseInt(qtd_etiqueta) + 1;
				
				if(qtd_etiqueta <= TotalEtiqueta){
					document.formulario.QTDEtiqueta.value	=	qtd_etiqueta;
					
					selecionado		=	"checked";
				}else{
					selecionado		=	"";
				}
				corpo	+="<td style='width:80px; background-color: #82BDFF; padding:3px; text-align:center'><input type='checkbox' name='etiqueta_"+pagina+"_"+i+"_"+ii+"' style='border:0' onClick=\"verifica_pagina('pagina',"+pagina+",this);\" "+selecionado+"></td>";
			}
			corpo	+="</tr>";
		}
			
		corpo	+="</table><BR></center></div>";
		
		document.formulario.QTDPagina.value					=	pagina;
		document.getElementById('pagina_'+pagina).innerHTML	=	corpo;
	}
	function verifica_pagina(tipo,pagina,campo){
		var QTDLinha		=	document.formulario.QTDLinha.value;
		var QTDColuna		=	document.formulario.QTDColuna.value;
		var QtdEtiqueta		=	document.formulario.QTDEtiqueta.value;
		var TotalEtiqueta	=	document.formulario.TotalEtiqueta.value;
		var proxima,i,aux,tam;
		
		if(tipo == 'todos'){
			if(campo.checked == true){	
				proxima	=	parseInt(pagina) + 1;
				tam 	= document.getElementById('table_etiqueta').rows.length;
				for(i=proxima;i<=(tam*3);i++){
					if(document.getElementById('pagina_'+i).innerHTML!=""){
						document.formulario.QTDPagina.value			   =   parseInt(document.formulario.QTDPagina.value) - 1;
					}					
					document.getElementById('pagina_'+i).innerHTML	=	'';	
				}
				
				var posIni=0,posFim=0,cont=0;
				
				document.formulario.QTDEtiqueta.value	=	0;
				
				for(i=0;i<document.formulario.length;i++){
					if(pagina == 1){
						if(document.formulario[i].name.substr(0,6) == 'todos_'){
							aux  =	document.formulario[i].name.split('_');
							if(trim(aux[1]) == pagina){
								document.formulario[i].disabled	=	true;
							}
						}
					}
					if(document.formulario[i].name.substr(0,9) == 'etiqueta_'){
						aux  =	document.formulario[i].name.split('_');
						if(trim(aux[1]) == pagina){
							if(document.formulario.QTDEtiqueta.value < TotalEtiqueta){
								document.formulario[i].checked	=	true;
								document.formulario.QTDEtiqueta.value	= parseInt(document.formulario.QTDEtiqueta.value) + 1;
							}
						}
					}
				}
				
				document.formulario.bt_exportar.disabled	=	false;	
			}else{
				document.formulario.bt_exportar.disabled	=	true;	
			}
		}else{
			if(campo.checked == true){
				document.formulario.QTDEtiqueta.value		   =	parseInt(document.formulario.QTDEtiqueta.value) + 1;
				
				proxima	=	parseInt(pagina) + 1;
					
				var posIni=0,posFim=0,aux,cont=0;
					
				for(i=0;i<document.formulario.length;i++){
					if(document.formulario[i].name.substr(0,9) == 'etiqueta_'){
						aux  =	document.formulario[i].name.split('_');
						if(trim(aux[1]) == pagina){
							if(document.formulario[i].checked == true){
								cont++;
							}
						}
					}
				}
				if(cont == (QTDLinha*QTDColuna)){
					tam 	= document.getElementById('table_etiqueta').rows.length;
					for(i=proxima;i<=(tam*3);i++){
						if(document.getElementById('pagina_'+i).innerHTML!=""){
							document.formulario.QTDPagina.value			    =   parseInt(document.formulario.QTDPagina.value) - 1;
						}
						document.getElementById('pagina_'+i).innerHTML	=	'';						
					}
					
					document.formulario.QTDEtiqueta.value	=	0;
					
					for(i=0;i<document.formulario.length;i++){
						if(document.formulario[i].name.substr(0,6) == 'todos_'){
							aux  =	document.formulario[i].name.split('_');
							if(trim(aux[1]) == pagina){
								document.formulario[i].checked	=	true;
								if(pagina == 1){
									document.formulario[i].disabled	=	true;
								}
							}
						}
						if(document.formulario[i].name.substr(0,9) == 'etiqueta_'){
							if(document.formulario[i].checked == true){
								document.formulario.QTDEtiqueta.value	= parseInt(document.formulario.QTDEtiqueta.value) + 1;
							}
						}
					}										
				}
				if(TotalEtiqueta < (QTDLinha*QTDColuna)){
					if(document.formulario.QTDEtiqueta.value == TotalEtiqueta){
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,6) == 'todos_'){
								aux  =	document.formulario[i].name.split('_');
								if(trim(aux[1]) == pagina){
									document.formulario[i].checked	=	true;
									document.formulario[i].disabled	=	true;
									break;
								}
							}
						}
					}
				}
				
				if(parseInt(document.formulario.QTDEtiqueta.value) > parseInt(TotalEtiqueta)){
					campo.checked	=	false;
					alert("ATENCAO! Todas as etiquetas já foram selecionadas.");
					document.formulario.QTDEtiqueta.value		   =	parseInt(document.formulario.QTDEtiqueta.value) - 1;
				}
				
				if(document.formulario.QTDEtiqueta.value == TotalEtiqueta){
					document.formulario.bt_exportar.disabled	=	false;	
				}
			}else{
				document.formulario.QTDEtiqueta.value		   =	parseInt(document.formulario.QTDEtiqueta.value) - 1;
				
				proxima	=	parseInt(pagina) + 1;
				
				var posIni=0,posFim=0,aux,cont=0;
				for(i=0;i<document.formulario.length;i++){
					if(document.formulario[i].name.substr(0,9) == 'etiqueta_'){
						aux  =	document.formulario[i].name.split('_');
						if(trim(aux[1]) == pagina){
							if(document.formulario[i].checked == false){
								cont++;
							}
						}
					}
				}
				if(TotalEtiqueta < QTDLinha*QTDColuna){
					tam 	= document.getElementById('table_etiqueta').rows.length;
					for(i=pagina;i<=(tam*3);i++){
						if(document.getElementById('pagina_'+i).innerHTML!=""){
							ultima	=	i;
						}					
					}
					
					for(i=proxima;i<=(tam*3);i++){
						if(document.getElementById('pagina_'+i).innerHTML!=""){
							document.formulario.QTDPagina.value			    =   parseInt(document.formulario.QTDPagina.value) - 1;
						}
						document.getElementById('pagina_'+i).innerHTML	=	'';						
					}
					
					document.formulario.QTDEtiqueta.value	=	0;
					
					for(i=0;i<document.formulario.length;i++){
						if(document.formulario[i].name.substr(0,9) == 'etiqueta_'){
							if(document.formulario[i].checked == true){
								document.formulario.QTDEtiqueta.value	= parseInt(document.formulario.QTDEtiqueta.value) + 1;
							}
						}
					}
					
					
					if(cont < QTDLinha*QTDColuna){
					
						tam 	= document.getElementById('table_etiqueta').rows.length;
						if(proxima == ((tam*3)+1)){
							if((tam*3) < proxima){
								add_linha_table();
							}
						}					
						add_pagina();
										
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,6) == 'todos_'){
								aux  =	document.formulario[i].name.split('_');
								if(trim(aux[1]) == pagina){
									document.formulario[i].checked	=	false;
									document.formulario[i].disabled	=	false;	
								}
							}
						}
					
						document.formulario.bt_exportar.disabled	=	false;	
					}else{
						document.getElementById('pagina_'+pagina).innerHTML	=	'';	
						document.formulario.QTDPagina.value			    	=   parseInt(document.formulario.QTDPagina.value) - 1;					
					
						if(document.formulario.QTDEtiqueta.value < TotalEtiqueta){
							document.formulario.bt_exportar.disabled	=	true;	
						}else{
							document.formulario.bt_exportar.disabled	=	false;	
						}
					}
				
				}else{
					if(cont == 1){
						tam 	= document.getElementById('table_etiqueta').rows.length;
						if(proxima == ((tam*3)+1)){
							if((tam*3) < proxima){
								add_linha_table();
							}
						}					
						add_pagina();
										
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,6) == 'todos_'){
								aux  =	document.formulario[i].name.split('_');
								if(trim(aux[1]) == pagina){
									document.formulario[i].checked	=	false;
									document.formulario[i].disabled	=	false;	
								}
							}
						}
					}else{
						var ultima	=	0;
						tam 	= document.getElementById('table_etiqueta').rows.length;
						for(i=pagina;i<=(tam*3);i++){
							if(document.getElementById('pagina_'+i).innerHTML!=""){
								ultima	=	i;
							}					
						}
						
						if(pagina == ultima){
							for(i=0;i<document.formulario.length;i++){
								if(document.formulario[i].name.substr(0,9) == 'etiqueta_'){
									aux  =	document.formulario[i].name.split('_');
									if(trim(aux[1]) == pagina){
										if(document.formulario[i].checked == true){
											cont++;
										}
									}
								}
							}
							if(cont == TotalEtiqueta){
								document.formulario.bt_exportar.disabled	=	false;
							}else{
								document.formulario.bt_exportar.disabled	=	true;
							}	
						}else{
							for(i=proxima;i<=(tam*3);i++){
								if(document.getElementById('pagina_'+i).innerHTML!=""){
									document.formulario.QTDPagina.value			    =   parseInt(document.formulario.QTDPagina.value) - 1;
								}
								document.getElementById('pagina_'+i).innerHTML	=	'';						
							}
							
							document.formulario.QTDEtiqueta.value	=	0;
							
							for(i=0;i<document.formulario.length;i++){
								if(document.formulario[i].name.substr(0,9) == 'etiqueta_'){
									if(document.formulario[i].checked == true){
										document.formulario.QTDEtiqueta.value	= parseInt(document.formulario.QTDEtiqueta.value) + 1;
									}
								}
							}
							
							tam 	= document.getElementById('table_etiqueta').rows.length;
							if(proxima == ((tam*3)+1)){
								if((tam*3) < proxima){
									add_linha_table();
								}
							}					
							add_pagina();
											
							for(i=0;i<document.formulario.length;i++){
								if(document.formulario[i].name.substr(0,6) == 'todos_'){
									aux  =	document.formulario[i].name.split('_');
									if(trim(aux[1]) == pagina){
										document.formulario[i].checked	=	false;
										document.formulario[i].disabled	=	false;	
									}
								}
							}
							
							document.formulario.bt_exportar.disabled	=	false;	
						}
					}
				}
			}
		}
	}
	function validar(){
		var 	atual=0,cont=0,temp=0,campo="";
		document.formulario.Cedulas.value	=	"";
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,9) == 'etiqueta_'){
				aux  	=	document.formulario[i].name.split('_');
				pagina	=	trim(aux[1]);	
				if(atual!=pagina){
					atual	=	pagina;
					cont	=	0;
					temp	=	0;	
					campo	=	"";
					
					if(document.formulario.Cedulas.value!=""){
						campo	+=	'\n';
					}
				}
				
				if(campo!="" && campo!='\n'){
					campo	+=	"¬";
				}
				if(document.formulario[i].checked == true){
					cont++;
					campo	+=	aux[1]+"_"+aux[2]+"_"+aux[3]+"_1";
				}else{
					campo	+=	+aux[1]+"_"+aux[2]+"_"+aux[3]+"_0";
				}
				temp++;
				
				if(temp == document.formulario.QTDLinha.value*document.formulario.QTDColuna.value){
					if(cont==0){
						mensagens(123,'etiqueta');
						return false;
					}
					document.formulario.Cedulas.value	+=	campo;
				}
			}
		}
		return true;
	}
