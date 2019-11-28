Kagescan Manga Engine
=====

Simple online manga reader with PHP and vanilla JS.
This don't use any database else some json configuration files.

Version 2.1

## Configure

This code was made for Kagescan's website. If you wish to use this code in your
project, you should edit some files.
This repository comes with 1 mangas that includes 3 samples chapters.

### Templates

In the top of the main.PHP file, you can change strings used in this script in
order to i18n. You can also change templates inside the same script.

This script requires MaterializeCSS lib only.
It also uses pace.JS but this one is optionnal.

### Add Mangas with Chapters

1. Create a folder with the name of the manga (ex: `kagerou-days/`)
2. Create a folder for a chapter (ex: `kagerou-days/9.1/`)
3. Add pages of the chapters (ex: `kagerou-days/9.1/1.webp`, `kagerou-days/9.1/2.webp`)
   Supported extensions are `.webp, .jpg, .png, .svg`. It is possible to add
   extensions by modifying the core script.
   Pages will be displayed with natural sorting (`1.jpg, 2.webp, 3.jpg, 10.jpg, ...`)
4. Update the manga.json file. (ex: `kagerou-days/manga.json`)
   This file has a structure like this :  
    ```json
    {
      "title": "Title of the manga",
      "innerHTML": "HTMl to insert to the page of the manga",
      "mangaName": "Name of the manga",
      "volumes": [
        {
          "name": "Volume Name",
          "id":"Volume number/ID",
          "coverArt": "Path to CoverArt",
          "summary": "Summary",
          "chapters": [
            {
              "id": "Folder name of the chapter",
              "name": "Chapter Name",
              "previewImg": "preview image for the chapter",
              "summary": "summary of chapter"
            },
            {"_": "Add more chapters here"}
          ]
        },
        {"_": "Add more Volumes here"}
      ]
    }
    ```

### Register Mangas

Once a manga is ready to post, you need to configure the file `index.json`
