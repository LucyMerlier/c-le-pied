# CHECKPOINT 4 - C'est le pied!

## What is "C'est le pied!"?

"C'est le pied!" is an Instagram-like application for feet pics. here you will find pics of your friends feet, of cat's paws, or even furniture's feet.

## Prerequisites

1. Check composer is installed
2. Check yarn & node are installed

## Install

1. Clone this project
2. Run `composer install`
3. Run `yarn install`
4. Run `yarn encore dev` to build assets
5. Create `.env.local` from the already existing `.env` file
8. Configure the DATABSE_URL with your information in .env.local file
9. Run `symfony console d:d:d --force`, then run `symfony console d:d:c` to create the database
10. Run `symfony console d:m:m` to run migrations
11. Run `symfony server:start` and open your project
12. Go to `localhost:8000`

## Working

1. Run `symfony server:start` to launch your local php web server
2. Run `yarn run dev --watch` to launch your local server for assets

## Windows Users

If you develop on Windows, you should edit you git configuration to change your end of line rules with this command :

`git config --global core.autocrlf true`

## Built With

* [Symfony](https://github.com/symfony/symfony)

## Authors

Developped in 2 days (more or less) by Lucy Merlier, with the help of her cat, Poubelle.

## License

NONE