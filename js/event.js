/*
**************************************
* Event Listener Function v1.4       *
* Autor: Carlos R. L. Rodrigues      *
**************************************
*/
addEvent = function(o, e, f, s){
	var r = o[r = "_" + (e = "on" + e)] = o[r] || (o[e] ? [[o[e], o]] : []), a, c, d;
	r[r.length] = [f, s || o], o[e] = function(e){
		try{
			(e = e || event).preventDefault || (e.preventDefault = function(){e.returnValue = false;});
			e.stopPropagation || (e.stopPropagation = function(){e.cancelBubble = true;});
			e.target || (e.target = e.srcElement || null);
			e.key = (e.which + 1 || e.keyCode + 1) - 1 || 0;
		}catch(f){}
		for(d = 1, f = r.length; f; r[--f] && (a = r[f][0], o = r[f][1], a.call ? c = a.call(o, e) : (o._ = a, c = o._(e), o._ = null), d &= c !== false));
		return e = null, !!d;
    }
};

removeEvent = function(o, e, f, s){
	for(var i = (e = o["_on" + e] || []).length; i;)
		if(e[--i] && e[i][0] == f && (s || o) == e[i][1])
			return delete e[i];
	return false;
};

//+ Jonas Raoni Soares Silva
//@ http://jsfromhell.com/forms/enter-as-tab [rev. #2]
//enterAsTab(form: HTMLFormElement, [jumpAlways: Boolean = false]): void
//A função irá adicionar tabulação via enter em todos os inputs, exceto selects (pois é necessário utilizar o enter para selecionar uma opção) e textareas (pois o enter fornece quebra de linhas).
//Os inputs devem estar dentro de uma tag form e ao chegar no último input o comportamento padrão é voltar para o primeiro ao pressionar enter.
		
//form formulário que receberá a tabulação via enter
//jumpAlways se true, o enter irá pular até mesmo selects e textareas

enterAsTab = function(f, a){
	addEvent(f, "keypress", function(e){
		var l, i, f, j, o = e.target;
		if(e.key == 13 && (a || !/textarea|select/i.test(o.type))){
			for(i = l = (f = o.form.elements).length; f[--i] != o;);
			for(j = i; (j = (j + 1) % l) != i && (!f[j].type || f[j].disabled || f[j].readOnly || f[j].type.toLowerCase() == "hidden"););
			e.preventDefault(), j != i && f[j].focus();
		}
	});
};
