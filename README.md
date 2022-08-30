# Fairhate API

A Simple PHP API to work with Fairgate

## Features

- Get all contatcs

## Requirements

- Socket enabled or curl extension installed
- PHP 5.3+

## Installation

```bash
composer require splattner/fairgateapi:^1.0
```

## Usage

```php
$fairgate = new FairgateAPI("https://mein.fairgate.ch/<myaccount>","<username>","<password>");
$allMembers = $fairgate->getContactList();
```
