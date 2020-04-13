# Kagescan
<p align="center">
  <img src="https://kagescan.legtux.org/res/img/logo.NoOutline.min.jpg"/>
</p>

[Kagescan's website](https://kagescan.legtux.org) public source code.  
Under the MIT license, please read the file COPYING for more informations.

This repository contains the public source code of kagescan website.  

**This is the development repository !! This contains experimental/unstested/unfinished features.**

Last stable (published) commit :
* Music

Finished but not published :
* The manga reader

Programming :
* The chapter/volume select page

## Requirements
~~At the moment, Kagescan runs using PHP version 7, and one database called "kagescan".~~

The current code don't use any databases

## Configuration
Configure `/res/private/db.php`, that contains the master password of kagescan's database.
Configure `/res/private/.htpasswd`, that contains the passwords to access the directory `/res/private` using http.
