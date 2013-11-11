<?php

/*
 * classe TSqlSelect
 * Esta classe provê meios para manipulação de uma instruçãso SELECT no banco de dados
 */
final class TSqlSelect extends TSqlInstruction {
    
    private $columns; //array de colunas a serem retornadas
    
    /*
     * método addColumn()
     * adiciona uma coluna a ser retornado pelo SELECT
     * @param $column = coluna da tabela
     */
    public function addColumn($column) {
        //adiciona a coluna no array
        $this->columns[] = $column;
    }
    
    /*
     * método getInstrunction()
     * retorna a instrução SELECT em forma de string
     */
    public function getInstruction() {
        //monsta a instru��o de SELECT
        $this->sql = "SELECT ";
        //monta string com os nomes de colunas
        $this->sql .= implode(', ', $this->columns);
        //adiciona na cláusula FROM o nome da tabela
        $this->sql .= " FROM " . $this->entity;
        
        //obtem a cláusula WHERE do objeto TCriteria
        if($this->criteria){
            $expression = $this->criteria->dump();
            if($expression){
                $this->sql .= " WHERE " . $expression;
            }
            //obtém as propriedades do criterio
            $order = $this->criteria->getProperty('order');
            $limit = $this->criteria->getProperty('limit');
            $offset = $this->criteria->getProperty('offset');
            
            //obtém a ordem do SELECT
            if($order){
                $this->sql .= " ORDER BY " . $order;
            }
            if($limit){
                $this->sql .= " LIMIT " . $limit;
            }
            if($offset){
                $this->sql .= " OFFSET " . $offset;
            }
        }
        return $this->sql;
    }
    
}
