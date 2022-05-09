# Installation

1. Clone this Repository in your PHP Project
```cmd
git clone https://github.com/maximilianosinski/simple-api-php-library.git
```
2. Change your Project name (The name of your Project's Directory [not the full path]) and your Domain.
3. Include "lib/config.php" in every PHP file you have, where you wan't to use the functions: 
```php
include "lib/config.php";
```

### Features

- Can make HTTP/S responses with custom parameters.
- Can make HTTP/S requests (GET, POST and more).
- Lightweight functions for the server's file system.
- Can translate text to almost any language. Once with specified target language country code and an automatic country code detection of the request/user.
- Can return information of the request/user (IP, UserAgent, IP GeoLocation).

# Simple API PHP Library

![](https://img.icons8.com/color/200/api.png)

![](https://img.shields.io/github/stars/maximilianosinski/simple-api-php-library) ![](https://img.shields.io/github/license/maximilianosinski/simple-api-php-library) ![](https://img.shields.io/github/tag/maximilianosinski/simple-api-php-library) ![](https://img.shields.io/github/release/maximilianosinski/simple-api-php-library)

## Classes
- Responses
- Requests
- FileSystem
- Typography
- User

Examples
=============

User
-------------
Getting a Users IP Address.
```php
<?php
$IP = User::IP();
?>
```

------------

Getting the Users IP GeoLocation.
```php
<?php
$GetIPGeoLocation = User::IPGeoLocation();
if($GetIPGeoLocation[0]){
   $Country = $GetIPGeoLocation["country"];
   echo "Users country: $Country";
}
?>
```
Typography
-------------
Translating a custom Text.
```php
<?php
$Translate = Typography::Translate("How are you doing?", "de");
if($Translate["success"]){
	$TranslatedText = $Translate["data"];
	echo $TranslatedText;
}
?>
```
How-To: Handle Errors
=============
Example
-------------
```php
<?php
$Translate = Typography::Translate("How are you doing?", "de");
if($Translate["success"]){
	$TranslatedText = $Translate["data"];
	echo $TranslatedText;
} else {return $Translate;}
?>
```
How-To: Give back a Response
=============
Example
-------------
```php
<?php
$Translate = Typography::Translate("How are you doing?", "de");
if($Translate["success"]){
	$TranslatedText = $Translate["data"];
	Responses::ResponseByReturn($Translate);
} else {Responses::ResponseByReturn($Translate);}
?>
```
