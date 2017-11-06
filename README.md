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

        <input name="file_url" value="http://yourdomain.com/uploaded_files/sunset.jpg" type="hidden">
        <input name="file_id" value="1" type="hidden">
        <input name="file_name" value="sunset" type="hidden">
        <input name="file_alt" value="Sunset" type="hidden"> 
        <input name="file_description" value="a beautiful sunset" type="hidden">

## Options

### add multiple files

sometimes you need to add more than one image to your form. For this, just add `data-type="multi"` to your button:

        <button type="button" data-type="multi" class="fileManager btn">Select File</button>

Now you can add multiple files to your form. For access to all files these fields will be added to the current form:

        <input name="file[0][url]" value="http://yourdomain.com/uploaded_files/sunset.jpg" type="hidden">
        <input name="file[0][id]" value="1" type="hidden">
        <input name="file[0][name]" value="sunset" type="hidden">
        <input name="file[0][alt]" value="Sunset" type="hidden"> 
        <input name="file[0][description]" value="a beautiful sunset" type="hidden">
        
        <input name="file[1][url]" value="http://yourdomain.com/uploaded_files/sunset.jpg" type="hidden">
        <input name="file[1][id]" value="1" type="hidden">
        <input name="file[1][name]" value="sunset" type="hidden">
        <input name="file[1][alt]" value="Sunset" type="hidden"> 
        <input name="file[1][description]" value="a beautiful sunset" type="hidden">


### change name attribute

by adding `data-name` attribute to button element:

        <button type="button" data-type="multi" data-name="my_files" class="fileManager btn">Select File</button>
        
and the following hidden fields will be added to you form:

        <input name="my_files[0][url]" value="http://yourdomain.com/uploaded_files/sunset.jpg" type="hidden">
        <input name="my_files[0][id]" value="1" type="hidden">
        <input name="my_files[0][name]" value="sunset" type="hidden">
        <input name="my_files[0][alt]" value="Sunset" type="hidden"> 
        <input name="my_files[0][description]" value="a beautiful sunset" type="hidden">
        
        <input name="my_files[1][url]" value="http://yourdomain.com/uploaded_files/sunset.jpg" type="hidden">
        <input name="my_files[1][id]" value="1" type="hidden">
        <input name="my_files[1][name]" value="sunset" type="hidden">
        <input name="my_files[1][alt]" value="Sunset" type="hidden"> 
        <input name="my_files[1][description]" value="a beautiful sunset" type="hidden">

## Edit a form contains files

for editing forms contain some images you can do it in this way:

        @if ($shop->icon_id or old('icon_id'))
            <div data-target="icon" class="prev-images">
                <span class="image"  data-id="{{ $shop->logo_id }}" src="{{ url('/uploaded_files/'.$shop->icon->name) }}" alt=""></span>
            </div>
        @endif