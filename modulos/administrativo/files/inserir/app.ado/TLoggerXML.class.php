<?php

/*
 * classe TLoggerXML
 * implementa o algoritimo de LOG em XML
 */
class TLoggerXML extends TLogger {
    
    /*
     * método write()
     * escreve uma menssagem no arquivo de LOG
     * @param $message = menssagem a ser escrita
     */
    public function write($message) {
        $time = date('Y-m-d H:i:s');
        //mostra a string
        $text = "<log>\n";
        $text .= "<time>$time</time>\n";
        $text .= "<message>$message</message>\n";
        $text .= "</log>\n";
        
        //adiciona ao final do arquivo
        $handler = fopen($this->filename, 'a');
        fwrite($handler, $text);
        fclose($handler);
    }
    
}
