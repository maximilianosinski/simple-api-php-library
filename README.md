# Simple API PHP Library

Just a simple API **PHP library** with basic **functions** and **config**.


# Usage

Just add the lib/config.php to any PHP function or script you have.

    <?php
    include "lib/config.php";
    ?>

## Features

My Library supports:

 - HTTP/S Responses.
 - Custom Function Returns.
 - Easy File System functions.

# Example

## FileSystem
    <?php
    include "lib/config.php";
    $Result = FileSystem::WriteContent("my/local/file.txt", "Hello World!";
    Responses::ResponseByReturn($Result);
    ?>
