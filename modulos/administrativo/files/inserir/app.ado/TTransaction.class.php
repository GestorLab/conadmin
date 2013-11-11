<?php

/*
 * class TTransaction
 * esta classe provê o métodos necessários para manipular transações
 */
final class TTransaction {
    
    private static $conn; //conexão ativa
    private static $logger; //objeto de LOG
    
    /*
     * método __construct()
     * Está declarado como private para impedir que se crie instâncias de TTransaction
     */
    private function __construct() {}
    
    /*
     * método open()
     * Abre uma transação e uma conexao ao BD
     * @param $database = nome do banco de dados
     */
    public static function open($database) {
        //abre uma conexão e armazena na propriedade estática $conn
        if(empty(self::$conn)){
            self::$conn = TConnection::open($database);
            //inicia a transação
            self::$conn->beginTransaction();
            
            //desliga o log de SQL
            self::$logger = NULL;
        }
    }
    
    /*
     * método get()
     * retorna a conexão ativa da transação
     */
    public static function get() {
        //retorna a conexão ativa
        return self::$conn;
    }
    
    /*
     * método rollback()
     * desfaz todas operações realizadas na transação
     */
    public static function rollback() {
        if(self::$conn){
            //desfaz as operações realizadas durante a transação
            self::$conn->rollBack();
            self::$conn = NULL;
        }
    }
    
    /*
     * método close()
     * aplica todas as operações realizadas e fecha a transação
     */
    public static function close() {
        if(self::$conn){
            //aplica as operações ralizadas
            //durante a transação
            self::$conn->commit();
            self::$conn = NULL;
        }
    }
    
    /*
     * método setLogger()
     * define qual estratégia (algoritimo de LOG será usado)
     */
    public static function setLogger(TLogger $logger) {
        self::$logger = $logger;
    }
    
    /*
     * método log()
     * armazena uma menssagem no arquivo de LOG
     * baseada na extratégia ($logger) atual
     */
    public static function log($message) {
        //verifica se existe um log
        if(self::$logger){
            self::$logger->write($message);
        }
    }
    
}
