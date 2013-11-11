function busca_caixa(IdCaixa, Erro, Local){
	if(IdCaixa == ""){
		IdCaixa = 0;
	}
	
	if(Local == "" || Local == undefined){
		Local = document.formulario.Local.value;
	}
	
	var url = "./xml/caixa.php?IdCaixa="+IdCaixa;
	
	call_ajax(url,function (xmlhttp){
		if(Erro){
			document.formulario.Erro.value = 0;
			verificaErro();
		}
		
		if(xmlhttp.responseText == "false"){
			switch(Local){
				case "Caixa":
					document.formulario.IdCaixa.value			= "";
					document.formulario.IdStatus.value			= "1";
					document.formulario.LoginAbertura.value		= "";
					document.formulario.DataAbertura.value		= "";
					document.formulario.LoginFechamento.value	= "";
					document.formulario.DataFechamento.value	= "";
					document.formulario.Acao.value				= "inserir";
					
					addParmUrl("marCaixaMovimentacao","IdCaixa","");
					
					document.getElementById("cp_Status").innerHTML = "";
					
					listar_forma_pagamento();
					break;	
				case "Movimentacao":
					document.formulario.IdCaixa.value			= "";
					document.formulario.Acao.value				= "inserir";
					break;	
			}
		} else{
			var nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixa")[0]; 
			var nameTextNode = nameNode.childNodes[0];
			IdCaixa = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var IdStatus = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var Status = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("CorStatus")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var CorStatus = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("DataAbertura")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var DataAbertura = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("Titular")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var Titular = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("NomeResponsavel")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var NomeResponsavel = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAbertura")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var LoginAbertura = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("DataFechamento")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var DataFechamento = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("LoginFechamento")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var LoginFechamento = nameTextNode.nodeValue;
			
			switch(Local){
				case "Caixa":
					document.formulario.IdCaixa.value 			= IdCaixa;
					document.formulario.IdStatus.value 			= IdStatus;
					document.formulario.Titular.value 			= Titular;
					document.formulario.LoginAbertura.value		= LoginAbertura;
					document.formulario.DataAbertura.value		= dateFormat(DataAbertura);
					document.formulario.LoginFechamento.value	= LoginFechamento;
					document.formulario.DataFechamento.value	= dateFormat(DataFechamento);
					document.formulario.Acao.value				= "alterar";
					
					addParmUrl("marCaixaMovimentacao","IdCaixa",IdCaixa);
					
					document.getElementById("cp_Status").innerHTML = Status+"<div style='font-size:10px;'>Responsável: "+NomeResponsavel+"</div>";
					document.getElementById("cp_Status").style.color = CorStatus;
					document.getElementById("bl_FormaPagamento").innerHTML = "";
					document.formulario.TabIndex.value = document.formulario.TabIndexFix.value;
					document.formulario.FormaPagamento.value = "0";
					var reponseXMLFormaPagamento = xmlhttp.responseXML.getElementsByTagName("FormaPagamento")[0];
					
					for(var i = 0; i < reponseXMLFormaPagamento.getElementsByTagName("IdFormaPagamento").length; i++){
						nameNode = reponseXMLFormaPagamento.getElementsByTagName("IdFormaPagamento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdFormaPagamento = nameTextNode.nodeValue;
						
						nameNode = reponseXMLFormaPagamento.getElementsByTagName("ValorAbertura")[i];
						nameTextNode = nameNode.childNodes[0];
						var ValorAbertura = nameTextNode.nodeValue;
						
						nameNode = reponseXMLFormaPagamento.getElementsByTagName("ValorCancelado")[i];
						nameTextNode = nameNode.childNodes[0];
						var ValorCancelado = nameTextNode.nodeValue;
						
						nameNode = reponseXMLFormaPagamento.getElementsByTagName("ValorAtual")[i];
						nameTextNode = nameNode.childNodes[0];
						var ValorAtual = nameTextNode.nodeValue;
						
						listar_forma_pagamento(IdFormaPagamento, ValorAbertura, ValorCancelado, ValorAtual);
					}
					break;
				case "Movimentacao":
					document.formulario.IdCaixa.value 			= IdCaixa;
					document.formulario.DataAbertura.value		= dateFormat(DataAbertura);
					document.formulario.NomeResponsavel.value	= NomeResponsavel;
					document.formulario.Acao.value				= "alterar";
					break;
			}
		}
		
		verificaAcao();
	});
}