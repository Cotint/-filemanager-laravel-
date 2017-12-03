<?php

//Route::get('/filemanager/getAll','Cotint\fileManager\FileController@getAll');
Route::get('/filemanager/videos','Cotint\fileManager\FileController@getAllVideos');
Route::get('/filemanager/images','Cotint\fileManager\FileController@getAllImages');
Route::get('/filemanager/docs','Cotint\fileManager\FileController@getAllDocs');
Route::get('/filemanager/archives','Cotint\fileManager\FileController@getAllArchives');
Route::get('/filemanager/all','Cotint\fileManager\FileController@getAll');
Route::get('/filemanager/search','Cotint\fileManager\FileController@search');

Route::post('/filemanager/save-meta','Cotint\fileManager\FileController@saveMeta');
Route::get('/filemanager/{path}','Cotint\fileManager\FileController@index');
Route::post('/filemanager/delete','Cotint\fileManager\FileController@delete');
Route::post('/filemanager/upload/{type}','Cotint\fileManager\FileController@upload')->name('fileManagerUpload');