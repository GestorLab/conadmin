<?
	class SFTPConnection
	{
	    private $connection;
	    private $sftp;
		public $erro=false;

	    public function __construct($host, $port=22){
			$this->connection = @ssh2_connect($host, $port);
			if(!$this->connection)
			    throw new Exception("Could not connect to $host on port $port.");
	    }

	    public function login($username, $password){
			if(@ssh2_auth_password($this->connection, $username, $password)){
				$this->sftp = @ssh2_sftp($this->connection);
				if(!$this->sftp)
					$this->erro = true;
			}else{
				$this->erro = true;
			}
	    }

	    public function uploadFile($local_file, $remote_file){
			$sftp = $this->sftp;
			$stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');

			if(!$stream)
				$this->erro = true;

			$data_to_send = @file_get_contents($local_file);
			if($data_to_send === false)
				$this->erro = true;

			if (@fwrite($stream, $data_to_send) === false)
				$this->erro = true;

			@fclose($stream);
	    }
	}
?>