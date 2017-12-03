<?php
/**
 * Created by PhpStorm.
 * User: Saeed Moqadam <saeed.moqadam@gmail.com>
 * Date: 10/25/17
 * Time: 11:35 AM.
 */

namespace Cotint\FileManager\Services;

class FileService
{
    /**
     * image extensions
     * @var array
     */
    private $imageExtensions = [
        'jpg', 'png', 'jpeg', 'gif', 'svg',
    ];

    private $videoExtensions = [
      'mp4','avi','flv'
    ];
    /**
     * document extensions
     * @var array
     */
    private $docsExtensions = [
        'doc', 'docx', 'pdf', 'xlsx', 'xls',
    ];

    /**
     * archive extensions
     * @var array
     */
    private $archiveExtensions = [
        'rar', 'zip', 'tar', 'gz',
    ];

    /**
     * Get file thumbnail. if the file does not an image it return an icon.
     *
     * @param $fileName
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getFileThumb($fileName)
    {
        //TODO: return thumbnail not original file
        if ($this->isImage($fileName)) {
            return $fileName;
        }

        return $this->getIconByExtension($fileName);
    }

    /**
     * get extension based on file type.
     *
     * @param $file
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getIconByExtension($file)
    {
        if ($this->isDoc($file)) {
            //TODO: move hardcoded icon path to config
            return url('/cotint/fileManager/images/doc.png');
        }

        if ($this->isArchive($file)) {
            //TODO: move hardcoded icon path to config
            return url('/cotint/fileManager/images/archive.png');
        }
//        dd($this->isVideo($file));
        if ($this->isVideo($file)) {
            //TODO: move hardcoded icon path to config
            return url('/cotint/fileManager/images/video.png');
        }

        //TODO: move hardcoded icon path to config
        return url('/cotint/fileManager/images/unknown.png');
    }

    /**
     * return file extension.
     *
     * @param $fileName
     *
     * @return mixed
     */
    public function getFileType($fileName)
    {
        $fileInfo = pathinfo($fileName);

        return $fileInfo['extension'];
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isImage($file)
    {
        if (in_array($this->getFileType($file), $this->imageExtensions)) {
            return true;
        }

        return false;
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isVideo($file)
    {
        if (in_array($this->getFileType($file), $this->videoExtensions)) {
            return true;
        }

        return false;
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isDoc($file)
    {
        if (in_array($this->getFileType($file), $this->docsExtensions)) {
            return true;
        }

        return false;
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isArchive($file)
    {
        if (in_array($this->getFileType($file), $this->archiveExtensions)) {
            return true;
        }

        return false;
    }
}
