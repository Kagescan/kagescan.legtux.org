# Kagescan

## Deprecation Notice

This repository contains some code of the original Kagescan's website (kagescan.legtux.org) with a bunch of unreleased new features (such as the new `scan/` engine), written using PHP.  
**It has been deprecated in favor of the (planned) new Kagescan's website
See more at the [Kagescan/code.kagescan.fr](https://github.com/Kagescan/code.kagescan.fr) repository**

----------------------------

<p align="center">
  <img src="https://kagescan.legtux.org/res/img/logo.NoOutline.min.jpg"/>
</p>

[Kagescan's website](https://kagescan.legtux.org) public source code.  
Under the MIT license, please read the file COPYING for more informations.

This repository contains the public source code of kagescan website.  

**This is the development repository !! This contains experimental/unstested/unfinished features.**

Stable and published in kagescan.legtux.org :
* Music

Finished but not published :
* The manga reader

Not finished :
* The chapter/volume select page

## Requirements
~~At the moment, Kagescan runs using PHP version 7, and one database called "kagescan".~~

The current code don't use any databases

## Configuration
Configure `/res/private/db.php`, that contains the master password of kagescan's database.
Configure `/res/private/.htpasswd`, that contains the passwords to access the directory `/res/private` using http.
