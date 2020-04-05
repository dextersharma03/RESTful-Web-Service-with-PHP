<?php
//Dikshit Sharma
//Require configuration
require_once('inc/config.inc.php');

//Require Entities
require_once('inc/Entities/Customer.class.php');

require_once('inc/Utilities/RestClient.class.php');
require_once('inc/Utilities/Page.class.php');

$editCustomer = new Customer();
//Check if there was get data, perform the action
if (!empty($_GET))   {
    //Perform the Action
    if($_GET["action"] == "delete")  {
        //Call the rest client with DELETE
        RestClient::call("DELETE",array('id'=>$_GET['id']));
    }

    //Was there an edit?
    if($_GET["action"] == "edit")  {
        //Call the rest client with GET, encode the result into a typed Customer
        $jCustomer = RestClient::call("GET",array('id'=>$_GET['id']));
        $editCustomer->setCustomerID($jCustomer->CustomerID);
        $editCustomer->setName($jCustomer->Name);
        $editCustomer->setAddress($jCustomer->Address);
        $editCustomer->setCity($jCustomer->City);
    
    }

}

//Check for post data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["action"]) && $_POST["action"] == "edit")    {
        //Assemble the the postData
        $postData = array(
            "CustomerID" => $_POST["customerid"],
            "Name" => $_POST["nameEdit"],
            "Address" => $_POST["addressEdit"],
            "City" => $_POST["cityEdit"],
        );
        //Call the RestClient with PUT
        RestClient::call("PUT",$postData);
        
    //Was probably a create
    } else {
        //Assemble the Customer
        $postData = array(
            "Name" => $_POST["name"],
            "Address" => $_POST["address"],
            "City" => $_POST["city"],
        );
        //Add the data 
        RestClient::Call("POST", $postData);
    }
}

//Get all the customers from the web service via the REST client
$jCustomers = RestClient::call("GET");

//Store the customer objects 
$customers = array();
//Iterate through the customers and convert them back to Customer objects
foreach($jCustomers as $jCustomer)   {
    if (is_object($jCustomer))    {
        $ns = new Customer();
        $ns->setCustomerID($jCustomer->CustomerID);
        $ns->setName($jCustomer->Name);
        $ns->setAddress($jCustomer->Address);
        $ns->setCity($jCustomer->City);
        
        //Add the new customer to the customers
        $customers[] = $ns;
    }
}


Page::$title = "Simple RESTful WebService";
Page::header();
Page::listCustomers($customers);
//Check the action, edit?  show edit page? get?  show create form
    if (!empty($_GET) && $_GET["action"] == "edit") {
        Page::editCustomer($editCustomer);
    } else {
        Page::addCustomer();

    }

Page::footer();