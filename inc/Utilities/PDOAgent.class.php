<?php

class PDOAgent    {

    //Attributes to connect to the database
    private $_host = DB_HOST;
    private $_user = DB_USER;
    private $_pass = DB_PASS;
    private $_dbname = DB_NAME;

    //store PDO things
    //Store the PDO instance
    private $_dbh;
    //Store prepared statement
    private $_pstmt;

    //Store the class name
    private $_className;


    //Connect to the database
    public function __construct(string $className)   {
        //Store the class we want to map
        $this->_className = $className;

        //Build the data source name
        $dsn = 'mysql:host=' .$this->_host . ';dbname=' .$this->_dbname; 

        //Options for PDO
        $options = array ( PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

        //Connect
        try {
            $this->_dbh = new PDO($dsn, $this->_user, $this->_pass, $options);
        } catch (PDOException $pe)  {
            echo $pe->getMessage();
        }
    }

    //This function  will create a SQL query and store it as a statement.
    public function query(string $query)    {
        $this->_pstmt = $this->_dbh->prepare($query);
    }


    //This function will assign the appropraite PARAM type for PDO according to the value.
    public function bind($param, $value, $type = null)  {
        if (is_null($type)) {
            switch (true)   {
                //If its an integer
                case is_int($value):
                    $type = PDO::PARAM_INT;
                break;
                //If its a boolean
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                break;
                //If  its null
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                break;

                //Default - Im a string!
                default:
                    $type = PDO::PARAM_STR;
                break;
            }
        }

        //Add the bind parameter information to the statement
        $this->_pstmt->bindValue($param, $value, $type);
    }

    //This function will execute the statement
    public function execute()   {
        $this->_pstmt->execute();
    }

    //This function returns the result set
    public function resultSet() {
        return $this->_pstmt->fetchAll(PDO::FETCH_CLASS, $this->_className);
    }

    public function singleResult()  {
        //Set the fetch mode
        $this->_pstmt->setFetchMode(PDO::FETCH_CLASS, $this->_className);

        return $this->_pstmt->fetch(PDO::FETCH_CLASS);

    }

    public function lastInsertedId()    {
        return $this->_dbh->lastInsertId();
    }

    public function rowCount()  {
        return $this->_pstmt->rowCount();
    }


}