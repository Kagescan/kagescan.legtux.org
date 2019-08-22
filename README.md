# Kagescan

(Kagescan's website)[https://kagescan.legtux.org] public source code.  
Under the MIT license, please read the file COPYING for more informations.

This repository contains the public source code of kagescan website.  
It will be updated each times a major improvement is made.
When a page gets a major improvement, his source-code will be published in this repository

At the moment, thoses parts are published : 
* Music

## Requirements
At the moment, Kagescan runs using PHP version 7, and one database called "kagescan".

## Configuration
Configure `/res/private/db.php`, that contains the master password of kagescan's database.
Configure `/res/private/.htpasswd`, that contains the passwords to access the directory `/res/private` using http.
