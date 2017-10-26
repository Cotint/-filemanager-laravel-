<?php

Route::get('/filemanager/getAll','Cotint\fileManager\FileController@getAll');
Route::get('/filemanager/all','Cotint\fileManager\FileController@getAll');
Route::post('/filemanager/save-meta','Cotint\fileManager\FileController@saveMeta');
Route::get('/filemanager/{path}','Cotint\fileManager\FileController@index');
Route::post('/filemanager/delete','Cotint\fileManager\FileController@delete');
Route::post('/filemanager/upload','Cotint\fileManager\FileController@upload')->name('fileManagerUpload');