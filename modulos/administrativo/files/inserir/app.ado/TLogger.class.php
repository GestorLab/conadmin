<?php

/*
 * class TLogger
 * esta classe provê uma interface abstrata para a definição de algoritimos de LOG
 */
abstract class TLogger {
    
    protected $filename; //local do arquivo de log
    
    /*
     * método __construct()
     * instancia um logger
     * @param $filename = local do arquivo de log
     */
    public function __construct($filename) {
        $this->filename = $filename;
        //reseta o conteudo do arquivo
        file_put_contents($filename, '');
    }
    
    //define o método write como obrigatório
    abstract function write($message);
    
}