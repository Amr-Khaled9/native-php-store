<?php
class config
{
    private $conn;
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';
    private $databasename = 'ecommerce';
    //create connection
    public function __construct()
    {
        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->databasename);
        // // check connection 
        // if($this->conn -> connect_error){
        //     echo "connect failed : ".$this->conn -> connect_error;
        // }
        // else echo "seccessfully";
    }
    // هعمل هنا function مسؤوله عن quary 
    public function runDML(string $query): bool
    {
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else
            return false;
    }
    public function runDQL(string $read)
    {
        $result = $this->conn->query($read);
        if ($result) {
            return $result;
        } else
            return [];
    }
}
?>