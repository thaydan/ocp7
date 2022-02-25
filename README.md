[![Codacy Badge](https://app.codacy.com/project/badge/Grade/1faf2041e6124da599eb1889426c9902)](https://www.codacy.com/gh/thaydan/ocp7/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=thaydan/ocp7&amp;utm_campaign=Badge_Grade)

# ocp7

BileMo is a BTB smartphone provider, when you have a client account with us, you can access the list of smartphones we offer through our API. You also have the possibility to manage your customers directly from our API. 
Here is how to use it.

# Client usage

## Read the API documentation
To know all about the request entries, the parameters and make tests, go to the documentation by accessing : /api/doc

## Get a JWT authentication token
Send a GET or POST request with json content to /api/login_check

Example :  
{  
&nbsp;&nbsp;&nbsp;&nbsp;"username":"your_login",  
&nbsp;&nbsp;&nbsp;&nbsp;"password":"your_password"  
}  

This will return your token.

## Add your token in your requests
[explain]

## Make a request
For example :
- Get the list of the available products : /api/product
- Get the detail of a product : /api/product/{product_id} 

This will return a JSON response.

# Developper installation

1. Copy the repository
2. Install dependencies with "composer install"
3. Generate keypair with : "php bin/console lexik:jwt:generate-keypair"
