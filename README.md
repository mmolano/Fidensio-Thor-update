# Thor (WebApp client)

This web application is used by Jerome (pressing)

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

- Php 7.4
- Mysql
- Composer
- Completed data base

### Installing

**Step 1:** Download the folder Edgar
```
git clone https://gitlab.com/fidensio/Thor.git
```

**Step 2 [Optional]:** Change branch
```
git checkout dev
```

**Step 3:** Create a .env file
<br />
*You can copy the ".env.example" and rename it to ".env" but be careful not to forget to replace the data*
```
cp .env.example .env
```

**Step 4:** install npm dependencies
```
npm i
```

**Step 5:** Generate an app encryption key
```
php artisan key:generate
```

<br />

**Launch the server**
```
php artisan serv
```
*or*
```
sudo php artisan serv --port=80
```

## Deployment

**Warning:** Npm production mode
```
npm run prod
```

**Step 1:** Change APP_ENV in .env to production

    APP_ENV=production

**Step 2:** Change APP_DEBUG in .env by false

    APP_DEBUG=false

**Step 3:** Redirect the subdomain "xxx.fidensio.com" to the server (look at the following link) :

https://www.notion.so/fidensio/Cr-e-une-nouvelle-entreprise-dans-Edgar-6b2472b2bba74455b4189b775c50c87e


**The application is now deploy**

## Built With

* [Laravel](https://laravel.com/docs/5.8) - The web framework used
* [Stripe Stripe-php](https://stripe.com/docs) - Use for payments
* [Mailjet Mailjet-apiv3-php](https://dev.mailjet.com/email/guides/?php#getting-started) - Use send transactional email and sms

## Authors

Project done by the develop team of Fidensio

* **[Developer Full-Stack] Miguel Molano** (miguel@fidensio.com) - *Initial Work*
