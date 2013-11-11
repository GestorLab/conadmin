<?php

/*
 * classe TRepository
 * esta classe provê os métodos necessários para manipular coleções de objetos
 */
final class TRepository {
    
    private $class; //nome da classe manipulada pelo repositório
    
    /*
     * método __construct()
     * instância um Repositório de objetos
     * @param $class = classe de objetos
     */
    public function __construct($class){
        $this->class = $class;
    }
    
    /*
     * método load()
     * Recupera um conjunto de objetos (collection) da base de dados
     * através de um critério de seleção, e instancia-los em memória
     * @param $criteria = objeto do tipo TCriteria
     */
    public function load(TCriteria $criteria){
        //instância a instrução select
        $sql = new TSqlSelect();
        $sql->addColumn('*');
        $sql->setEntity(constant($this->class . '::TABLENAME'));
        //atribui o critério passado como parametro
        $sql->setCriteria($criteria);
        
        //obtém transação ativa
        if($conn = TTransaction::get()){
            //registra mensagem de log
            TTransaction::log($sql->getInstruction());
            
            //executa a consulta no banco de dados
            $result = $conn->query($sql->getInstruction());
            
            if($result){
                //percorre os resultados da consulta, retornado no objeto
                while($row = $result->fetchObject($this->class)){
                    //armazena no array $results
                    $results[] = $row;
                }
            }
            return $results;
        }else{
            //se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }
    
    /*
     * método delete()
     * Exclui um conjunto de objetos (collection) da base de dados
     * através de um critério de seleção
     * @param $criteria = objeto do tipo TCriteria
     */
    public function delete(TCriteria $criteria){
        //instância instrução de delete
        $sql = new TSqlDelete();
        $sql->setEntity(constant($this->class . '::TABLENAME'));
        //atribui o critério passado como parâmetro
        $sql->setCriteria($criteria);
        
        //obtém transação ativa
        if($conn = TTransaction::get()){
            //registra mensagem de LOG
            TTransaction::log($sql->getInstruction());
            //executa instrução de delete
            $result = $conn->exec($sql->getInstruction());
            return $result;
        }else{
            //se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }
    
    /*
     * método count()
     * Retorna a quantidade de objetos da base de dados
     * que satisfazem um determinado critério de seleção
     * @param $criteria = um objeto do tipo TCriteria
     */
    public function count(TCriteria $criteria){
        //instância instrução de select
        $sql = new TSqlSelect();
        $sql->addColumn('count(*)');
        $sql->setEntity(constant($this->class . '::TABLENAME'));
        //atribui o critério passado com parâmetro
        $sql->setCriteria($criteria);
        
        //obtém transação ativa
        if($conn = TTransaction::get()){
            //registra mensagem de log
            //echo $sql->getInstruction();
            //exit;
            TTransaction::log($sql->getInstruction());
            
            //executa instrução de select
            $result = $conn->query($sql->getInstruction());
            if($result){
                $row = $result->fetch();
            }
            return $row[0];
        }else{
            //se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }
    
}
