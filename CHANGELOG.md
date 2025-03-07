2.2.8 / March 2025
==================
* Added check for incorrectly deleted customers.
* Slow down credit limit cron to prevent hitting request rate limit

2.2.7 / March 2025
==================
* Added Hokodo company info by environment to the customers grid
* Added API keys scope for Credit Limit update cron

2.2.6 / September 2024
==================
* Added ability to assign Hokodo company to customer onSave based on Taxvat or other selected customer attribute.

2.2.5 / February 2024
==================
* Updated organisation creation process

2.2.4 / February 2024
==================
* Adapt metadata for better scoring
* Added dynamic environment switch

2.2.3 / September 2023
==================
* Fix the issue when some credit rejection messages were stored incorrectly in database

2.2.2 / September 2023
==================
* Changed order status flow

2.2.1 / August 2023
==================
* Changed temp order id pattern
* Updated php compatibility

2.2 / July 2023
==================
* Code refactoring
* Removed unnecessary marketing API calls

2.1.17 / July 2023
==================
* Analytics compatibility update
* Added Api Integration tests
* Code refactoring

2.1.16 / June 2023
==================
* API compatibility update
* Code refactoring

2.1.15 / May 2023
==================
* Fix backend issues
* Added notification when postcode is empty or address is missing

2.1.14 / April 2023
==================
* Remove SDK notifications in browser's console
* Refactored SDK rendering process
* Added credit limit rejection reason to the admin

2.1.13 / April 2023
==================
* Fix order documents endpoint

2.1.12 / April 2023
==================
* Import to support Company id
* Fix SDK issue

2.1.11 / April 2023
==================
* Added auto assign company to users registered after first checkout
* Changed the way Payment method 'By Default' selection

2.1.10 / March 2023
==================
* Change currency source for Hokodo Order create request
* Disable fetch transaction support

2.1.9 / March 2023
==================
* Fix segment tracking

2.1.8 / March 2023
==================
* Added country restriction to company search component

2.1.7 / February 2023
==================
* Module supports upfront payment

2.1.6 / February 2023
==================
* Credit limits are updated everyday 
* Bug fixing

2.1.5 / February 2023
==================
* Fix compatibility for 2.3.7

2.1.4 / January 2023
==================
* Discount banners
* Credit limit are available in Admin
* Pre-matching feature

2.1.3 / January 2023
==================
* Upgrade payment information display
* Upgrade Analytics
* Display Hokodo conditionally
* Update copyrights

2.1.2 / December 2022
==================
* Change handling PDF

2.1.1 / December 2022
==================
* Fix CSS for Payment Method title
* SOAP fix

2.1 / December 2022
==================
* Released Post Sale API V2
* Added partial Invoicing

2.0.13 / December 2022
==================
* Added company search to the customer edit page in the Admin
* Added company search to the customer account
* Refactored module and fixed bugs

2.0.12 / November 2022
==================
* Fix issue with enetring discount before payment method is selected
* Fix php8.1 int type rounding issue

2.0.11 / November 2022
==================
* Refactor Static and Credit limit marketing banners
* Fix UI issues with marketing banners

2.0.10 / November 2022
==================
* Fixed checkout component not loaded when refreshing the page

2.0.9 / November 2022
==================
* Added Magento T&Cs to be displayed

2.0.8 / November 2022
==================
* Credit Advertised Amount for banner config added

2.0.7 / November 2022
==================
* Marketing banners redevelopment
* Added guest to be able checkout with Hokodo

2.0.6 / October 2022
==================
* Logos display control
* Tests adjustments

2.0.5 / October 2022
==================
* Fix hokodo order shipping tax calculation
* Fix Order Placed after events broke other payment methods

2.0.4 / October 2022
==================
* Payment method title configuration added
* Automatic capture

2.0.3 / October 2022
==================
* Removed marketing text from payment method block
* Fix hokodo order shipping tax calculation
* Added variations of the payment method title design

2.0.1 / October 2022
==================
* Added Customer Groups filtering for Hokodo BNPL
* Upadated composer dependencies
* Fixed marketing visibility settings

2.0.0 / October 2022
==================
* Complete frontend redevelopment.
* Introducing the latest JS SDK from Hokodo.
* Checkout flow redevelopment.
* Introducing Hokodo marketing banners.

1.3.3 / July 2022
==================
* Version 1.3.3
Composer dependency changed
Product details page banner adjustments

* Version 1.3.2
Admin configuration changes
Product details page banner changes

* Version 1.3.1
Change titling
Fix issue with coupons cannot be applied
Fix issue with customer email doesn't change
Added Hokodo Ad banners
Fix Webhook issue

1.3.0 / June 2022
==================
* Version 1.3.0
Modulve version bumped up


* Version 1.2.6
Frontend changes


1.2.5 / June 2022
==================
* Version 1.2.5
Tax calculation fixes

* Version 1.2.4
Webhook functionality fix for store code in urls

* Version 1.2.3
Multiple shipments admin panel fix

* Version 1.2.21
Fix for segment integration

* Version 1.2.2
Refunds handled
Fixed issues with search doesn't work sometimes
Fixed Order update in Admin when using multistore
Added Segment tracking

* Version 1.2.1
Resolved multistore set-up for admin
Fix issue when sometimes payment method was visible permanently on checkout
Change status update for Payment in admin

1.2.0 / May 2022
==================
* Version 1.2.0
Module made public, minor version bumped up

* Version 1.1.38
Documentation endpoint added

* Version 1.1.37
Store scope added to configuration

* Version 1.1.36
Order status after issuing an invoice

1.1.35 / April 2022
==================
* Version 1.1.35
Tax calculation and discounts changes

* Version 1.1.34
Create new org changed

* Version 1.1.33
Error for 0 value items fixed

* Version 1.1.32
Create new org for all guest users

* Version 1.1.31
Keep existing org for logged in customers

* Version 1.1.30
Native REST API URL used for Magento shipments

* Version 1.1.29
New organization creation

* Version 1.1.28
REST API covered for shipping

* Version 1.1.27
Post Sale Action calculation fix

* Version 1.1.26
ACL modified

* Version 1.1.25
ACL added, database error fixed when running se:up

* Version 1.1.24

1.1.23 / March 2022
==================
* Version 1.1.23
* Version 1.1.22
* Version 1.1.21
* Version 1.1.20
* Version 1.1.19
* Version 1.1.18
* Version 1.1.17

1.1.16 / February 2022
==================
* Version 1.1.16
* Version 1.1.15
* Version 1.1.14
* Version 1.1.13
* Version 1.1.12
* Version 1.1.11
* Version 1.1.10
* Version 1.1.9
* Version 1.1.8
* Version 1.1.7
* Version 1.1.6
* Version 1.1.5
* Version 1.1.4
* Version 1.1.3
* Version 1.1.2
* Version 1.1.1
