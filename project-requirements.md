# Challenge overview

The challenge is to develop a payment processing system integrated with the Asaas approval environment, taking into account that the customer must access a page where they will select the payment option between Boleto, Card or Pix.

Documentation: https://asaasv3.docs.apiary.io/#

## Sandbox Credentials:

Create an account at Asaas Sandbox ( https://sandbox.asaas.com/ ), in the Account Configuration->Integrations part you will get the Sandbox API Key to start the integration.

Basically, the system must have the payment option and a form with the necessary inputs to process the payment and a 'finalize payment' button, and if the payment is successful, direct to a thank you page.

## The system should meet the following needs:

* The system should be developed using the PHP language in the Laravel framework.
* Treat the Data in the Request of the requisition so that it does not happen that data is missing or different from what is necessary.
* Request response to be tailored via Resources as needed.
* Standardization of API requests for integration with Asaas.
* Payment processing with boleto, credit card and pix.
* If the payment is a boleto, show a button with the boleto link on the thank you page.
* If payment is Pix, display QRCode and Copy and Paste on the thank you page.
* In case of refusal of the card or error in the request, show a friendly message in the return to facilitate the understanding of the non-processing of the payment.
* It is not necessary to care about the quality of the front, use a very basic bootstrap
* Use good programming practices
* Use best git practices
* Document how to run the project

## Optional:

* Automated tests with test coverage information
* Persistence of data in a relational database, preferably MySql.

**All knowledge applied in the Test will be analyzed, so don't just rely on the basic needs that must be met.**
