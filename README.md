Totalizatorius
=====================================
TODO: describe the project

Technical Requirements
---------------------------------

PHP 5.5 or higher

Installation Instructions
-------------------------

1. `composer install`
1. `php app/console doctrine:migrations:migrate --no-interaction`
1. `app/console server:run`

FAQ
===

Sending emails
--------------

My config is:

    mailer_transport: smtp
    mailer_host: smtp.gmail.com
    mailer_port: 587
    mailer_encryption: tls
    mailer_user: <username>@gmail.com
    mailer_password: <password>

To try to send the email do:

`$ php app/console swiftmailer:email:send --from="myname@example.com" --to="myemail@gmail.com" --subject="test swiftmailer" --body="test" --content-type="text/html" --charset="UFT-8" --env=dev`