<?php

/*
 * class TLoggerTXT
 * emplementa o algoritimo de LOG em TXT
 */
class TLoggerTXT extends TLogger {
    
    /*
     * método write()
     * escreve uma menssagem no arquivo de LOG
     * @param $message = menssagem a ser escrita
     */
    public function write($message) {
        $time = date('Y-m-d H:i:s');
        //mostra string
        $text = "$time :: $message\n";
        //adiciona ao final do arquivo
        $handler = fopen($this->filename, 'a');
        fwrite($handler, $text);
        fclose($handler);
    }
    
}
