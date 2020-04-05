<?php


//Require configuration
require_once('inc/config.inc.php');

//Require Entities
require_once('inc/Entities/Customer.class.php');

//Require Utillity Classes
require_once('inc/Utilities/PDOAgent.class.php');
require_once('inc/Utilities/CustomerMapper.class.php');

/*
Create  - INSERT - POST
Read    - SELECT - GET
Update  - UPDATE - PUT
DELETE  - DELETE - DELETE
*/

//Instantiate a new Customer Mapper
CustomerMapper::initialize();


//Pull the request data
// parse_str(file_get_contents('php://input'), $requestData);
$requestData = json_decode(file_get_contents('php://input'));


//Do something based on the request
switch ($_SERVER["REQUEST_METHOD"])   {

    case "POST":    //Picked up a POST, Its Insert time!
    //YARC Id=6&Name=Sally Hill&City=Vancouver&Address=66 Royal Ave

    //New Customer
    $nc = new Customer();
    $nc->setName($requestData->Name);
    $nc->setAddress($requestData->Address);
    $nc->setCity($requestData->City);

    $result = CustomerMapper::createCustomer($nc);
    //Return the results
    echo json_encode($result);

    break;

    //If there was a request with an id return that customer, if not return all of them!
    case "GET":

        if (isset($requestData->id))    {

            //Return the customer object
            $sc = CustomerMapper::getCustomer($requestData->id);

            //Set the header
            header('Content-Type: application/json');
            //Barf out the JSON version
            echo json_encode($sc->jsonSerialize());

        } else {

            //All the customers!
            $customers = CustomerMapper::getCustomers();
            
            //Walk the customers and add them to a serialized array to return.
            $serializedCustomers = array();

            foreach ($customers as $customer)    {
                $serializedCustomers[] = $customer->jsonSerialize();
            }
            //Return the results

            //Set the header
            header('Content-Type: application/json');
            //Return the entire array
            echo json_encode($serializedCustomers);            
        }
    break;
   
    case "PUT":
    
        //In YARC send the following: Id=6&Name=Sally Hill&City=Vancouver&Address=66 Royal Ave
        //Must be an update, build the new customer object
        $ec = new Customer();
        $ec->setCustomerID($requestData->CustomerID);
        $ec->setName($requestData->Name);
        $ec->setCity($requestData->City);
        $ec->setAddress($requestData->Address);

        $result = CustomerMapper::updateCustomer($ec);
        //Set the header
        header('Content-Type: application/json');
        //Return the number of rows affected
        echo json_encode($result);

    break;

    case "DELETE":
        //In YARC send the request as key=value
        //Pull the ID, send it to delete via the customer mapper and return the result.
        $result = CustomerMapper::deleteCustomer($requestData->id);

        //Set the header
        header('Content-Type: application/json');
        //return the confirmation of deletion
        echo json_encode($result);
        

    break; 

    default:
        echo json_encode(array("message"=> "Você fala HTTP?"));
    break;
}


?>