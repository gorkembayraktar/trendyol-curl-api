<?php


class EmailReaderConfig{
    // email login credentials
    public $server = '';
    public $user   = '';
    public $pass   = '';
    public $port   = 993; // adjust according to server settings

    public function __construct($server,$user,$pass,$port){
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        $this->port = $port;
    }
}


class EmailReader {

    // imap server connection
    public $conn;

    // inbox storage and inbox message count
    private $inbox;
    private $msg_cnt;

    public $config;


   

    // connect to the server and get the inbox emails
    function __construct(EmailReaderConfig $config) {

        $this->config = $config;


        $this->connect();

        //$this->inbox();

        return !!$this->conn;
        
    }

    // close the server connection
    function close() {
        if(!$this->conn) return false;

        imap_close($this->conn);
    }

    // open the server connection
    // the imap_open function parameters will need to be changed for the particular server
    // these are laid out to connect to a Dreamhost IMAP server
    function connect() {
        try{

            $result = @imap_open('{'.$this->config->server.':'.$this->config->port.'/imap/ssl/novalidate-cert}INBOX', $this->config->user, $this->config->pass);
       
            if (!$result) {
                $lastError = @imap_last_error();
    
                throw new Exception('Could not open mailbox!');
            }

            $this->conn = $result;


        }catch(Exception $e){

            return false;
        }
    }
    function reconnect(){
        $this->close();
        $this->connect();
    }

    // move the message to a new folder
    function move($msg_index, $folder='INBOX.Processed') {
        // move on server
        imap_mail_move($this->conn, $msg_index, $folder);
        imap_expunge($this->conn);

        // re-read the inbox
        $this->inbox();
    }

    // get a specific message (1 = first email, 2 = second email, etc.)
    function get($msg_index=NULL) {
        if (count($this->inbox) <= 0) {
            return array();
        }
        elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
            return $this->inbox[$msg_index];
        }

        return $this->inbox[0];
    }

    // read the inbox
    function inbox($limit = 3) {


        if(!$this->conn) return;

        // last $limit
        $this->msg_cnt = imap_num_msg($this->conn);

   
        
        $in = array();
        for($i = ($this->msg_cnt - $limit); $i <= $this->msg_cnt; $i++) {
            $in[] = $this->item($i);
        }

        $this->inbox = $in;
    }
    function unseenMessages(){
        $datax = imap_search($this->conn,'UNSEEN');

        $list = array();

       if($datax){
            foreach($datax as $k){
                $list[] = $this->item($k);
            }
        }
        return $list;
    }
    function item($i){
        if(!$this->conn) return;
  
        $header = imap_headerinfo($this->conn, $i);

        return array(
            'index'     => $i,
            "title"     =>  utf8_decode(imap_utf8($header->subject)),
            'header'    => $header,
            'body'      => (imap_body($this->conn, $i)),
            'structure' => imap_fetchstructure($this->conn, $i)
        );
    }
    function seen($id){
        if(!$this->conn) return false;

        $durum =  imap_setflag_full($this->conn,$id, "\\Seen \\Flagged");
        return (bool)($durum);
    }
    public function last(){
        if($this->msg_cnt == 0){
            return null;
        }
        $lastItem = $this->item($this->msg_cnt);
        $this->reconnect();
        $this->msg_cnt = imap_num_msg($this->conn);
        return $lastItem;
    }

    public function decode($encoding,$text){
        switch ($encoding) {
            # 7BIT
            case 0:
                return $text;
            # 8BIT
            case 1:
                return quoted_printable_decode(imap_8bit($text));
            # BINARY
            case 2:
                return imap_binary($text);
            # BASE64
            case 3:
                return imap_base64($text);
            # QUOTED-PRINTABLE
            case 4:
                return quoted_printable_decode($text);
            # OTHER
            case 5:
                return $text;
            # UNKNOWN
            default:
                return $text;
        }
    }


    function getAll(){
        return $this->inbox;
    }


    function __destruct(){
        $this->close();
    }

}