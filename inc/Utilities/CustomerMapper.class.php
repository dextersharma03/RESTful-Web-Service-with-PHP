<?php

class CustomerMapper    {

    private static $_db;

    static function initialize()    {

        //Initialize the database connection
        self::$_db = new PDOAgent('Customer');
    
    }

    //CREATE a single Customer
    static function createCustomer(Customer $newCustomer): int   {
        $sql = "INSERT INTO Customers (Name, Address, City) VALUES (:Name, :Address, :City);";

        //Query
        self::$_db->query($sql);
        //Bind
        self::$_db->bind(":Name",$newCustomer->getName());
        self::$_db->bind(":Address",$newCustomer->getAddress());
        self::$_db->bind(":City",$newCustomer->getCity());

        //Execute
        self::$_db->execute();

        //Return 
        return self::$_db->lastInsertedId();

    }

    //READ a single Customer
    static function getCustomer(int $id) {

        $sql = "SELECT * FROM Customers WHERE CustomerID = :id";
        self::$_db->query($sql);
        self::$_db->bind(":id", $id);
        self::$_db->execute();
        return self::$_db->singleResult();
    }

    //READ a list of Customers
    static function getCustomers() : Array {

        $sql = "SELECT * FROM Customers;";
        //QUERY
        self::$_db->query($sql);
        //BIND!  (sssh no bind parameters) 
        //EXECUTE
        self::$_db->execute();
        //RETURN
        return self::$_db->resultSet();
    }

    //UPDATE 
    static function updateCustomer(Customer $updatedCustomer): int   {

        //Get the number of affected rows

        $sql = "UPDATE Customers
            SET Name = :Name, Address= :Address, City = :City
            WHERE CustomerID = :CustomerID;";

        self::$_db->query($sql);

        //BIND
        self::$_db->bind(":Name", $updatedCustomer->getName());
        self::$_db->bind(":Address", $updatedCustomer->getAddress());
        self::$_db->bind(":City", $updatedCustomer->getCity());
        self::$_db->bind(":CustomerID", $updatedCustomer->getCustomerID());

        //EXECUTE
        self::$_db->execute();

        //Return the result
        return self::$_db->lastInsertedId();
    }

    //DELETE
    static function deleteCustomer(int $id): int {

        try {
            $sql = "DELETE FROM Customers WHERE CustomerID = :id;";

            self::$_db->query($sql);
            self::$_db->bind(":id",$id);
            self::$_db->execute();
            return self::$_db->rowCount();
        //Return the amount of affected rows.
        
    }catch(Exception $ex){
        echo $ex->getMessage();
    }

    }
}

?>