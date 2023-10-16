# fruity-app

A simple app that loads fruit data from fruityvice.com and displays to a web page

## Local Setup

#### Pre-conditions for local machine
* PHP version > 7.2.5
* MySQL version > 5.7
* Gmail account

#### Setup

1 - Clone the repository

```
cd ~/Sites
git clone ssh://git@github.com:mjbdelacruz/FruityApp.git
```

2 - Run composer
```
composer install -o
```

3 - Update **.env** file and set the local env vars based on your needs
```
// Configure database:

// For local override change: db_user, db_password also database port connection in case it is not 3306
DATABASE_URL_READ="mysql://db_user:db_password@127.0.0.1:3306/fruityvice?serverVersion=5.7"
DATABASE_URL_WRITE="mysql://db_user:db_password@127.0.0.1:3306/fruityvice?serverVersion=5.7"

// Configure mailer:
// Specify sender and receiver email by changing MAILER_TO and MAILER_FROM.
// Also specify MAILER_FROM's App password to MAILER_FROM_APP_PASSWORD
// NOTE: Do not use your Gmail's password as it won't work. You can generate an App Password by following the steps here https://support.google.com/accounts/answer/185833

MAILER_TO="testTo@gmail.com"
MAILER_FROM="testFrom@gmail.com"
MAILER_FROM_APP_PASSWORD="abcdefghojkl"
```

#### Getting Started

1 - Load database schema
```
// Change db_user and db_password
mysql -u{db_user} -p{db_password} < migrations/schema.sql
```

2 - Load data from fruityvice.com to the database
```
php bin/console fruits:fetch
```

3 - Run the built-in PHP server

```
cd public/
php -S 0.0.0.0:8090
```

4 - Index can now be viewed in the browser
```
http://0.0.0.0:8090/fruits
```