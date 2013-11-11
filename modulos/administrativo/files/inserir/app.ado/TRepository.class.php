<?php

/*
 * classe TRepository
 * esta classe prov� os m�todos necess�rios para manipular cole��es de objetos
 */
final class TRepository {
    
    private $class; //nome da classe manipulada pelo reposit�rio
    
    /*
     * m�todo __construct()
     * inst�ncia um Reposit�rio de objetos
     * @param $class = classe de objetos
     */
    public function __construct($class){
        $this->class = $class;
    }
    
    /*
     * m�todo load()
     * Recupera um conjunto de objetos (collection) da base de dados
     * atrav�s de um crit�rio de sele��o, e instancia-los em mem�ria
     * @param $criteria = objeto do tipo TCriteria
     */
    public function load(TCriteria $criteria){
        //inst�ncia a instru��o select
        $sql = new TSqlSelect();
        $sql->addColumn('*');
        $sql->setEntity(constant($this->class . '::TABLENAME'));
        //atribui o crit�rio passado como parametro
        $sql->setCriteria($criteria);
        
        //obt�m transa��o ativa
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
            //se n�o tiver transa��o, retorna uma exce��o
            throw new Exception('N�o h� transa��o ativa!!');
        }
    }
    
    /*
     * m�todo delete()
     * Exclui um conjunto de objetos (collection) da base de dados
     * atrav�s de um crit�rio de sele��o
     * @param $criteria = objeto do tipo TCriteria
     */
    public function delete(TCriteria $criteria){
        //inst�ncia instru��o de delete
        $sql = new TSqlDelete();
        $sql->setEntity(constant($this->class . '::TABLENAME'));
        //atribui o crit�rio passado como par�metro
        $sql->setCriteria($criteria);
        
        //obt�m transa��o ativa
        if($conn = TTransaction::get()){
            //registra mensagem de LOG
            TTransaction::log($sql->getInstruction());
            //executa instru��o de delete
            $result = $conn->exec($sql->getInstruction());
            return $result;
        }else{
            //se n�o tiver transa��o, retorna uma exce��o
            throw new Exception('N�o h� transa��o ativa!!');
        }
    }
    
    /*
     * m�todo count()
     * Retorna a quantidade de objetos da base de dados
     * que satisfazem um determinado crit�rio de sele��o
     * @param $criteria = um objeto do tipo TCriteria
     */
    public function count(TCriteria $criteria){
        //inst�ncia instru��o de select
        $sql = new TSqlSelect();
        $sql->addColumn('count(*)');
        $sql->setEntity(constant($this->class . '::TABLENAME'));
        //atribui o crit�rio passado com par�metro
        $sql->setCriteria($criteria);
        
        //obt�m transa��o ativa
        if($conn = TTransaction::get()){
            //registra mensagem de log
            //echo $sql->getInstruction();
            //exit;
            TTransaction::log($sql->getInstruction());
            
            //executa instru��o de select
            $result = $conn->query($sql->getInstruction());
            if($result){
                $row = $result->fetch();
            }
            return $row[0];
        }else{
            //se n�o tiver transa��o, retorna uma exce��o
            throw new Exception('N�o h� transa��o ativa!!');
        }
    }
    
}
