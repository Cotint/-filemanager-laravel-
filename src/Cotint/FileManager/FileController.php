<?php

namespace Cotint\FileManager;

use App\Http\Controllers\Controller;
use Cotint\fileManager\models\File;
use Cotint\fileManager\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index(Request $request, $path)
    {
        $files = File::all();

        return view('fileManager::index', ['files' => $files]);
    }

    public function upload(Request $request)
    {
        try {
            $uploadedFile = $request->file('file');
            $file = new File();
            $file->name = $uploadedFile->getClientOriginalName();
            $file->size = $uploadedFile->getClientSize();
            $file->mime_type = $uploadedFile->getMimeType();
            $file->save();
            $uploadedFile->move(public_path('uploaded_files'), $uploadedFile->getClientOriginalName());

            return json_encode([
                'result' => 'ok',
                'filename' => $this->fileService->getFileThumb(url('/uploaded_files/'.$uploadedFile->getClientOriginalName())),
                'id' => $file->id,
            ]);
        } catch (\Exception $e) {
            return json_encode(['result' => 'error', 'message' => $e->getTraceAsString().'::'.$e->getLine()]);
        }
    }

    public function getAll()
    {
        $files = File::getAll();
        $mocks = $this->fillArrayForDropzone($files);

        return response()->json($mocks);
    }

    public function getAllDocs()
    {
        $files = File::getAllByType(File::TYPE_DOCS);

        return view('fileManager::index', ['files' => $files]);
    }

    public function getAllVideos()
    {
        $files = File::getAllByType(File::TYPE_VIDEOS);

        return view('fileManager::index', ['files' => $files]);
    }

    public function getAllImages()
    {
        $files = File::getAllByType(File::TYPE_IMAGES);

        return view('fileManager::index', ['files' => $files]);
    }

    public function getAllArchives()
    {
        $files = File::getAllByType(File::TYPE_ARCHIVE);

        return view('fileManager::index', ['files' => $files]);
    }

    public function delete(Request $request)
    {
        $fileName = basename($request->get('file'));
        File::where('name', '=', $fileName)->delete();
        @unlink(public_path('uploaded_files').'/'.$fileName);

        return response()->json(['msg' => 'true']);
    }

    private function fillArrayForDropzone($files)
    {
        $mocks = [];
        foreach ($files as $file) {
            $fileName = url('/uploaded_files/'.basename($file->name));
            array_push($mocks, [
                'name' => $this->fileService->getFileThumb($fileName),
                'size' => $file->size,
                'id' => $file->id,
                'title' => $file->title,
                'desc' => $file->description,
                'alt' => $file->alt,
            ]);
        }

        return $mocks;
    }

    public function saveMeta(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Just ajax request allowed!'], 403);
        }

        $file = File::find($request->get('id'));
        $file->title = $request->get('title');
        $file->description = $request->get('description');
        $file->alt = $request->get('alt');
        $file->save();

        return response()->json(['message' => 'successfully saved']);
    }
}
