<?php
class zipfile
{
    /**
     * Array para armazenar dados comprimidos
     *
     * @var  array    $datasec
     */
    var $datasec      = array();

    /**
     * diretуrio central 
     *
     * @var  array    $ctrl_dir
     */
    var $ctrl_dir     = array();

    /**
     * Fim do registro do diretуrio central
     *
     * @var  string   $eof_ctrl_dir
     */
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";

    /**
     * Posiзгo do ъltimo offset
     *
     * @var  integer  $old_offset
     */
    var $old_offset   = 0;


    /**
     * Converte a data no formato Unix para formato DOS 
     * 
     */
    function unix2DosTime($unixtime = 0) {
        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980) {
                $timearray['year']    = 1980;
                $timearray['mon']     = 1;
                $timearray['mday']    = 1;
                $timearray['hours']   = 0;
                $timearray['minutes'] = 0;
                $timearray['seconds'] = 0;
        } // end if

        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
                ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    } 


    /**
     * Adiciona um  "arquivo" no  formato zip.
     */
    function addFile($data, $name, $time = 0)
    {
        $name     = str_replace('\\', '/', $name);

        $dtime    = dechex($this->unix2DosTime($time));
        $hexdtime = '\x' . $dtime[6] . $dtime[7]
                  . '\x' . $dtime[4] . $dtime[5]
                  . '\x' . $dtime[2] . $dtime[3]
                  . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');

        $fr   = "\x50\x4b\x03\x04";
        $fr   .= "\x14\x00";           
        $fr   .= "\x00\x00";            
        $fr   .= "\x08\x00";            
        $fr   .= $hexdtime;             

        
        $unc_len = strlen($data);
        $crc     = crc32($data);
        $zdata   = gzcompress($data);
        $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); 
        $c_len   = strlen($zdata);
        $fr      .= pack('V', $crc);             
        $fr      .= pack('V', $c_len);          
        $fr      .= pack('V', $unc_len);         
        $fr      .= pack('v', strlen($name));    
        $fr      .= pack('v', 0);                
        $fr      .= $name;

    
        $fr .= $zdata;


        $fr .= pack('V', $crc);                 
        $fr .= pack('V', $c_len);               
        $fr .= pack('V', $unc_len);             

        
        $this -> datasec[] = $fr;
        $new_offset        = strlen(implode('', $this->datasec));

        
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .= "\x00\x00";                
        $cdrec .= "\x14\x00";                
        $cdrec .= "\x00\x00";                
        $cdrec .= "\x08\x00";                
        $cdrec .= $hexdtime;                 
        $cdrec .= pack('V', $crc);           
        $cdrec .= pack('V', $c_len);         
        $cdrec .= pack('V', $unc_len);       
        $cdrec .= pack('v', strlen($name) ); 
        $cdrec .= pack('v', 0 );             
        $cdrec .= pack('v', 0 );             
        $cdrec .= pack('v', 0 );             
        $cdrec .= pack('v', 0 );             
        $cdrec .= pack('V', 32 );            

        $cdrec .= pack('V', $this -> old_offset ); 
        $this -> old_offset = $new_offset;

        $cdrec .= $name;

        
        $this -> ctrl_dir[] = $cdrec;
    } 


    /**
     * 
     *
     * Retorna um  string contento um arquivo zip
     *
     * 
     */
    function file()
    {
        $data    = implode('', $this -> datasec);
        $ctrldir = implode('', $this -> ctrl_dir);

        return
            $data .
            $ctrldir .
            $this -> eof_ctrl_dir .
            pack('v', sizeof($this -> ctrl_dir)) .  
            pack('v', sizeof($this -> ctrl_dir)) .  
            pack('V', strlen($ctrldir)) .           
            pack('V', strlen($data)) .              
            "\x00\x00";                             
    } 
} 
?>