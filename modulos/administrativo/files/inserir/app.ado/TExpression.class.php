<?php

/**
 * classe TExpression
 * classe abstrata para gerenciar expressões
 */
abstract class TExpression {
    
    //operadores lógicos
    const AND_OPERADOR = 'AND';
    const OR_OPERADOR = 'OR';
    
    //marca método dump como obrigatorio
    abstract public function dump();
    
}