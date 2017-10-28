## File manager ro laravel

a simple and powerfull file manager for laravel. 

## Dependencies

this package depends on `jqurey` and `bootstrap` frameworks. 

## Installation

1. installing package by composer:
add `repositories` section and the package name in `composer.json` 

    ```
    "repositories":[
        {
            "type":"vcs",
            "url":"git@gitlab.com:cotint/cotint/filemanager-laravel.git"
        }
    ],
    "require": {
        ...
        "Cotint/FileManager": "@dev"
    }
    ```
    
now run `composer update`

1. add the following line in config/app.php on `providers` section:

        \Cotint\FileManager\FileManagerServiceProvider::class
        

2. add assets to your layout
        
        <link href="/cotint/fileManager/css/kamadatepicker.min.css" rel="stylesheet" type="text/css">
        <link href="/cotint/fileManager/css/dropzone.css" rel="stylesheet" type="text/css">
        <link href="/cotint/fileManager/css/style.css" rel="stylesheet" type="text/css">
        .......
        <script src="/cotint/fileManager/js/kamadatepicker.min.js"></script>
        <script src="/cotint/fileManager/js/dropzone.js"></script>
        <script src="/cotint/fileManager/js/filemanager.js"></script>
        
3. assign `fileManager` class to an html tag:
 
        <button type="button" class="fileManager btn">Select File</button>

4. run `php artisan vendor:publish` command

5. run `php artisan migrate`. all images store in `filemanager` table.

## Usage

When you click on `Select File` button, a modal would be open. In this modal you upload your files or select existing files. Also you can change name, title and description of 
a file. 

Select a file then click on `Save Changes` to add the selected file into your page. This action add some hidden fields as bellow:

        <input name="files[1][url]" value="http://yourdomain.com/uploaded_files/sunset.jpg" type="hidden">
        <input name="files[1][id]" value="1" type="hidden">
        <input name="files[1][name]" value="sunset" type="hidden">
        <input name="files[1][alt]" value="Sunset" type="hidden"> 
        <input name="files[1][description]" value="a beautiful sunset" type="hidden">


