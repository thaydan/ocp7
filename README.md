[![Codacy Badge](https://app.codacy.com/project/badge/Grade/1faf2041e6124da599eb1889426c9902)](https://www.codacy.com/gh/thaydan/ocp7/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=thaydan/ocp7&amp;utm_campaign=Badge_Grade)

# ocp7

BileMo is a BTB smartphone provider. When you have a client account with us, you can access the list of smartphones we offer through our API. You also have the possibility to manage your customers directly from our API. 
Here is how to use it.

# Client usage

## Read the API documentation
To know all about the request entries, the parameters and make tests, go to the documentation by accessing : /api/doc

## Get a Bearer Token
To access the API, you need to be identified. To do this, you must include an access token in each of the requests you make. Here is how to get a token.

Send a request with json content to /api/login_check

Example :  
{  
&nbsp;&nbsp;&nbsp;&nbsp;"username":"your_login",  
&nbsp;&nbsp;&nbsp;&nbsp;"password":"your_password"  
}  

This will return your bearer token.

## Make a request
Some examples :
- Get the list of the available products : /api/product (GET)
- Get the detail of a product : /api/product/{product_id} (GET)

This will return a JSON response.
Don't forget to include the bearer token in the header of your request.

# Developper installation

1. Copy the repository
2. Install dependencies with "composer install"
3. Generate keypair with : "php bin/console lexik:jwt:generate-keypair"
