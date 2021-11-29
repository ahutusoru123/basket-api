# Basket API
Simple basket PHP API leveraging AWS services and serverless technology.
It was built without using a frameworks as requested and aims to be simple 
to extend, run and maintain. 

While building this small app I've experimented with Bref(https://bref.sh/) and Serverless (https://www.serverless.com/)
for running and deploying this application. The PHP runtime is provided by
Bref and the application is running on a AWS Lambda function connected to API Gateway
and DynamoDB. 

## Requirements 
Can be found in architech-labs-code-test.pdf file.

## Functionality
1. Add product to basket for a particular userId.
2. Calculate total basket cost accounting for Special Offers and Delivery costs.

## Application Design
Requirements: 
1. AWS Cli setup for your AWS Admin account (as it needs access to create resources)
2. Serverless
3. Composer

### Deployment & configuration
Everything is managed via the serverless.yml file. 

See https://bref.sh/docs/environment/serverless-yml.html for full documentation. 

Our setup currently has 2 functions configured, one for each functionality. 
Important elements in our setup:
 - **handler** definition which denotes the actual php file to run, in our case "public/add_route.php" and "public/total_route.php"
 - **events** definition which is the routing configuration, here we define the httpApi routing + a scheduled trigger for warming
 - **provider** contains the definition of the aws region info and resource access information to set up for the new role. We also define our env variables here, the table names in our case
 - **custom** is where we define our custom variables, in our case the table names
 - **resources** our needed resources, in our case the dynamodb tables

#### How to deploy
Run commands: 
 - composer install
 - serverless deploy

This should deploy the entire stack to AWS.

#### How to populate
Command: php db/migrations.php

This will then populate the product catalogue and basket configuration in dynamoDb.

#### How to run tests
Command: php test.php

### Application logic
Tables:
 - ProductCatalogue - contains all product info
 - Basket - contains all baskets for all users 
 - Configuration - contains the configuration for the basket functionality (SpecialOffers and Delivery costs)

#### Add product to basket functionality
A product can be added by code to the basket of a user which will be identified by a userId param. 
The user may have 1 active basket at a time, the basket gets deleted after the user checks out or after a set amount of time (not fully implemented yet).

#### Calculate total basket price functionality
The total cost can be computed for all products in a users current basket, accounting for special offers and delivery costs.

##### Special Offers
Since the special offers can require complex flexibility in their configuration, they will be implemented in code. 
The special offers must implement the **Offer** interface and must be added to the database in the configuration to be enabled. 
This is added so that special offers can be enabled/disabled on demand without requiring a new deployment.
The order of the special offers in the configuration will be the order in which they are applied.

**To add new special offer:**
 - Add new Offer class implementing the Offer interface
 - Update the migrations.php file and add the new Offer to the configuration
 - (optional) Disable other offer in migrations.php
 - (option #1 - Config change) Update configuration in DynamoDB manually and add the new Offer
 - (option #2 - Migrations re-run) Run db/migrations.php

##### Delivery costs
Implemented as a key -> value ordered configuration. 
The key is the upper threshold for which the price specified in the value applies. 
This is stored as a configuration value in the database so that it can be modified on the fly without needing redeployment.

Example:
 [50 : 10, 90 : 5]
 * Orders under 50 will be charged 10$ fee
 * Orders under 90 and over 50 will be charged 5$ fee
 * Orders over 90 are not charged extra fee

**To update delivery cost**
 - Update db/migrations.php with the new setting
 - (option #1 - Config change) Update the configuration in DynamoDb manually
 - (option #2 - Migrations re-run) Run db/migrations.php

## Notes
There are a lot of missing elements due to the lack of time to allocate. 