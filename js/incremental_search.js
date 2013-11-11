function inicia_status(IdStatus){
	//document.formulario.Status.value 	= opc_status[IdStatus];
	document.formulario.IdStatus.value 	= val_status[IdStatus];
}
/*
**************************************
* Event Listener Function v1.3       *
* Autor: Carlos R. L. Rodrigues      *
**************************************
*/

addEvent = function(o, e, f, s){
	var r = o[r = "_" + (e = "on" + e)] = o[r] || (o[e] ? [[o[e], o]] : []), a;
	r[r.length] = [f, s || o], o[e] = function(e){
		try{
			(e = e || event).preventDefault || (e.preventDefault = function(){e.returnValue = false;});
			e.stopPropagation || (e.stopPropagation = function(){e.cancelBubble = true;});
			e.target || (e.target = e.srcElement || null);
			e.key = (e.which + 1 || e.keyCode + 1) - 1 || 0;
		}catch(f){}
		for(f = r.length; f; r[--f] && (a = r[f][0], o = r[f][1], a.call ? a.call(o, e) : (o._ = a, o._(e), o._ = null)));
		e = null;
	}
};

removeEvent = function(o, e, f, s){
	for(var i = (e = o["_on" + e] || []).length; i;)
		if(e[--i] && e[i][0] == f && (s || o) == e[i][1])
			return delete e[i];
	return false;
};

function Status( search ){ 
    search = search.toLowerCase(); 
    for( var i in opc_status ){
        if( search ){ 
            for( var j = 0, indices = []; j = opc_status[i].toLowerCase().indexOf( search, j ) + 1; indices[indices.length] = j - 1 ); 
            this( opc_status[i], indices, i ); 
        } 
        else 
            this( opc_status[i], 0, i ); 

    } 
}

IncrementalSearch = function( input, callback, className ){ //v1.0 
    var i, o = ( o = this, o.l = [], o.i = input, o.c = null, o.s = { e: null, i: -1 }, o.f = callback || function(){}, o.n = className || "", o ); 
    for( i in { keydown: 0, focus: 0, blur: 0, keyup: 0, keypress: 0 } ) 
        addEvent( o.i, i, function( e ){ o.handler.call( o, e ); } ); 
}; 
with( { p: IncrementalSearch.prototype } ){ 
    (p.constructor.fadeAway = function( o ){ 
        o instanceof Object ? ( this.trash = this.trash || [] ).push( o ) && setTimeout( this.fadeAway, 200 ) : arguments.callee.c.trash.pop().hide(); 
    }).c = p.constructor; 
    p.callEvent = function( e ){ this[e] && this[e].apply( this, [].slice.call( arguments, 1 ) ); }; 
    p.highlite = function( e ){ ( this.s.e && ( this.s.e.className = "normal" ), ( this.s = { e: e, i: e.listindex } ).e.className += " highlited", this.callEvent( "onhighlite", this.l[ this.s.i ], this.s.e.d ) ); }; 
    p.select = function(){ this.s.i + 1 && ( this.i.value = this.l[ this.s.i ], this.callEvent( "onselect", this.i.value, this.s.e.d ), this.hide() ); }; 
    p.hide = function(){ ( this.c && this.c.parentNode.removeChild( this.c ), this.c = null, this.l = [], this.s = { e: null, i: -1 }, this.callEvent( "onhide" ) ); }; 
    p.next = function(){ var e = ( e = this.s.e ) ? e.nextSibling || e.parentNode.firstChild : null; e && this.highlite( e ); }; 
    p.previous = function(){ var e = ( e = this.s.e ) ? e.previousSibling || e.parentNode.lastChild : null; e && this.highlite( e ); }; 
    p.handler = function( evt ){ 
        var o = this, t = evt.type, k = evt.key, e = /span/i.test( ( e = evt.target ).tagName ) ? e.parentNode : e; 
        t == "keyup" ? k != 40 && k != 38 && k != 13 && o.show() 
        : t == "keydown" ? ( k == 40 && o.next() ) || ( k == 38 && o.previous() ) 
        : t == "keypress" ? k == 13 && !evt.preventDefault() && o.select() 
        : t == "blur" ? o.constructor.fadeAway( o ) 
        : t == "click" ? o.select() 
        : t == "focus" ? o.show() 
        : o.highlite( e ); 
    }; 
    p.show = function(){ 
        var cS, found = 0, o = this, i = o.i, iV = i.value, d = document, c = ( o.hide(), o.c = d.body.appendChild( d.createElement( "div" ) ) ); 
        ( c.className = o.n, cS = c.style, cS.display = "none", cS.position = "absolute", o.callEvent( "onshow" ) ); 
        o.f.call( function( s, x, data ){ 
            if( !( x.length == undefined ? ( x = [x] ) : x ).length ) 
                return; 
            var j, l = 0, i = o.l.length, e = c.appendChild( d.createElement( "div" ) ); 
            for( j in ( o.l[i] = s, e.className = "normal", e.d = data, e.listindex = i, !found && i == o.s.i && ++found && o.highlite( e ), x ) ) 
                e.appendChild( d.createTextNode( s.substring( l, x[j] ) ) ).parentNode.appendChild( d.createElement( "span" ) ).appendChild( d.createTextNode( s.substring( x[j], l = x[j] + iV.length ) ) ).parentNode.className = "selectedText"; 
            for( x in ( e.appendChild( d.createTextNode( s.substr( l ) ) ), { click: 0, mouseover: 0 } ) ) 
                addEvent( e, x, function( e ){ o.handler.call( o, e ); } ); 
        }, iV ); 
        if( !c.childNodes.length ) 
            return o.hide(); 
        for( var x = i.offsetLeft, y = i.offsetTop + i.offsetHeight; i = i.offsetParent; x += i.offsetLeft, y += i.offsetTop ); 
        ( cS.display = "block", cS.left = x + "px", cS.top = y + "px", !found && o.highlite( c.firstChild ) ); 
    }; 
}
