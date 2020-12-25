# System Requirements
- php7
- postgreSQL

# Installation
1. Go to app directory by `cd 57ff43800f34b5cf0fa2c632566e933b`.
2. Make sure you already install [php7](https://www.php.net/downloads.php) and [postgreSQL](https://www.postgresql.org/download/) on your machine.

# Using Application
Before you can send an email, you need to signup/register your email. Make sure you used an active email address. It will used to validate your email before you can sending an email. After you finish with signup and validate the email, you need to login to get token for your account. This token will used for every request such as get list of mails and send an email.

## SignUp to system
Request URL     : "http://localhost/57ff43800f34b5cf0fa2c632566e933b/authenticate/signup.php"
Request Method  : "POST"
Request Header  : "Content-Type: application/json; charset=UTF-8"
Request Body    :
    ```yaml
    {
        "username": "test@mail.com",
        "password": "SuperSecretPassword"
    }
    ```
Response Success:
    ```yaml
    {
        "message": "User has been created! You need to validate your email on mailjet to start send email."
    }
    ```
Response Failed :
    ```yaml
    {
        "message": "{$FAILED_MESSAGE}"
    }
    ```


## Login
Request URL     : "http://localhost/57ff43800f34b5cf0fa2c632566e933b/authenticate/login.php"
Request Method  : "POST"
Request Header  : "Content-Type: application/json; charset=UTF-8"
Request Body    :
    ```yaml
    {
        "username": "test@mail.com",
        "password": "SuperSecretPassword"
    }
    ```
Response Success:
    ```yaml
    {
        "message": "Login Success!",
        "token": "{$USER_TOKEN}"
    }
    ```
Response Failed :
    ```yaml
    {
        "message": "{$FAILED_MESSAGE}"
    }
    ```

## Get Email List
Request URL     : "http://localhost/57ff43800f34b5cf0fa2c632566e933b/mail/list.php"
Request Method  : "POST"
Request Header  : "Content-Type: application/json; charset=UTF-8"
Request Body    :
    ```yaml
    {
        "token": "{$USER_TOKEN}"
    }
    ```
Response Failed :
    ```yaml
    {
        "message": "{$FAILED_MESSAGE}"
    }
    ```

## Send an Email
Request URL     : "http://localhost/57ff43800f34b5cf0fa2c632566e933b/mail/send_mail.php"
Request Method  : "POST"
Request Header  : "Content-Type: application/json; charset=UTF-8"
Request Body    :
    ```yaml
    {
        "token": "{$USER_TOKEN}",
        "mail_to": "target@mail.com",
        "mail_cc": "",
        "mail_subject": "Test mail",
        "mail_body": "I am trying to send an email to you",
        "mail_attachment": "{$BASE64_STRING}"
    }
    ```
Response Failed :
    ```yaml
    {
        "message": "{$FAILED_MESSAGE}"
    }
    ```
