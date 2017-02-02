# Division 3 Bot

## Overview
- This application built with PHP and Google Sheet API

## Setup Project

- Clone source and install necessary packages

```bash
$ git clone git@github.com:euclid1990/form_2_cw.git my-proj
$ cd my-proj
$ composer install
```

- Edit your BOT information
  - `credentials/chatwork.json` : Chatwork BOT access token and room ID
  - `credentials/sheet_id.json` : Spreadsheet ID of form responses
  - `credentials/client_secret.json` : Download from [Project](https://console.developers.google.com/start/api?id=sheets.googleapis.com) in the Google Developers Console
  - `credentials/sheets.googleapis.com.json` : Google credential path, you must run `$ php main.php` for first time to generate it

## Run Application

- PHP CLI

```
$ php main.php
```

- Crontab

```bash
* * * * * /usr/bin/php5 /path_to/my-proj/main.php
```