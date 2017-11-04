<?php

namespace Cotint\FileManager\models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    protected $fillable = [
        'title',
        'description',
        'alt',
    ];

    protected $table = 'filemanager';

    const TYPE_VIDEOS = [
        'video/mp4',
        'video/m4a',
        'video/mpeg',
    ];

    const TYPE_IMAGES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
    ];

    const TYPE_DOCS = [
        'application/msword', //.doc
        'application/pdf', //.pdf
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //.docx
        'application/vnd.ms-excel', //.xls
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', //.xlsx
        'application/vnd.ms-excel.sheet.macroEnabled.12', //.xlsm
        'application/vnd.ms-powerpoint', //.ppt
        'application/vnd.openxmlformats-officedocument.presentationml.presentation', //.pptx
    ];

    const TYPE_ARCHIVE = [
        'application/gzip',
        'application/x-7z-compressed',
        'application/vnd.android.package-archive',
        'application/x-rar-compressed',
        'application/zip',
    ];

    const TYPE_ALL = '*.*';

    public static function getAllByType($type)
    {
        $files = self::whereIn('mime_type', $type)->get();

        return $files;
    }

    public static function getAll()
    {
        return self::all();
    }
}
