<?php

// mysql> DESC Customers;
// +------------+------------------+------+-----+---------+----------------+
// | Field      | Type             | Null | Key | Default | Extra          |
// +------------+------------------+------+-----+---------+----------------+
// | CustomerID | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
// | Name       | char(50)         | NO   |     | NULL    |                |
// | Address    | char(100)        | NO   |     | NULL    |                |
// | City       | char(30)         | NO   |     | NULL    |                |
// +------------+------------------+------+-----+---------+----------------+
// 4 rows in set (0.01 sec)

class Customer  {

    //Attributes for our POPO
    private $CustomerID;
    private $Name;
    private $Address;
    private $City;

    //Getters
    public function getCustomerID() : int {
        return $this->CustomerID;
    }

    
    public function getName() : string {
        return $this->Name;
    }

    
    public function getAddress() : string {
        return $this->Address;
    }

    
    public function getCity() : string {
        return $this->City;
    }

    //Setters
    public function setCustomerID(int $customerID)   {
        $this->CustomerID = $customerID;
    }

    public function setName(string $name)   {
        $this->Name = $name;
    }

    
    public function setAddress(string $address)   {
        $this->Address = $address;
    }

    public function setCity(string $city)   {
        $this->City = $city;
    }

    //Serialize the object to JSON.
    public function jsonSerialize()
    {
        //Or you can specify a new object of stdClass and add the attributes you want to return.
        
        $obj = new stdClass;
        //Add all the attributes you want.
        $obj->CustomerID = $this->CustomerID;
        $obj->Name = $this->Name;
        $obj->Address = $this->Address;
        $obj->City = $this->City;
        
        return $obj;
    }
}

?>