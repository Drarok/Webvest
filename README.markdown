# Webvest

A detailed, read-only view of Harvest’s daily timers.

## About

This simple web application allows you to view your daily [Harvest][harvest] timers, exposing the most important detail of each.

I decided to create this when I realised that the [Harvest API][harvest api] returns more detail than the official website or apps reveal. Oftentimes, I found myself turning to the API to find out when I'd stopped one timer (and forgotten to start another) to work out how long I'd spent on a task. No longer!

## Set Up

These instructions assume you already have [Composer][composer] installed globally.

    git clone https://github.com/Drarok/Webvest.git
    composer install
    cp app/config/harvest.json.sample app/config/harvest.json
    $EDITOR !$

Fill in your details, and access index.php in a browser!

[harvest]: http://www.getharvest.com
[harvest api]: https://github.com/harvesthq/api
[composer]: https://getcomposer.org
