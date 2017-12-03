<?php

namespace Cotint\FileManager;

use App\Http\Controllers\Controller;
use Cotint\FileManager\models\File;
use Cotint\FileManager\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    public function upload(Request $request, $type = null)
    {
        try {
            $uploadedFile = $request->file('file');
            if ($type == 'image') {
                $this->validate($request, [
                    'file' => 'mimes:jpeg,gif,bmp,png,webp,image/x-icon'
                ]);
            }

            if ($type == 'archive') {
                $this->validate($request, [
                    'file' => 'mimes:application/x-7z-compressed,application/zip,application/x-tar,application/x-rar-compressed'
                ]);
            }
            if ($type == 'docs') {
                $this->validate($request, [
                    'file' => 'mimes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ]);
            }
            if ($type == 'videos') {
                $this->validate($request, [
                    'file' => 'mimes:application/mp4,video/x-msvideo,video/x-flv,'
                ]);
            }
            $file = new File();
            $file->name = $uploadedFile->getClientOriginalName();
            $file->size = $uploadedFile->getClientSize();
            $file->mime_type = $uploadedFile->getMimeType();
            $file->save();
            $uploadedFile->move(public_path('uploaded_files'), $uploadedFile->getClientOriginalName());

            return json_encode([
                'result' => 'ok',
                'filename' => $this->fileService->getFileThumb(url('/uploaded_files/' . $uploadedFile->getClientOriginalName())),
                'id' => $file->id,
            ]);
        }catch (ValidationException $exception){
            return response()->json([
                'result' => 'error',
                'message' => 'نوع فایل اشتباه است'
            ], 421);
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

    public function search(Request $request)
    {
        $query = $request->get('query');

        $files = File::where('name', 'like', '%'.$query.'%')
            ->orWhere('title', 'like', '%'.$query.'%')
            ->orWhere('alt', 'like', '%'.$query.'%')
            ->orWhere('description', 'like', '%'.$query.'%')->get();

        $mocks = $this->fillArrayForDropzone($files);

        return response()->json($mocks);
    }

    public function getAllDocs()
    {
        $files = File::getAllByType(File::TYPE_DOCS);

        $mocks = $this->fillArrayForDropzone($files);

        return response()->json($mocks);
    }

    public function getAllVideos()
    {
        $files = File::getAllByType(File::TYPE_VIDEOS);
        $mocks = $this->fillArrayForDropzone($files);

        return response()->json($mocks);
    }

    public function getAllImages()
    {
        $files = File::getAllByType(File::TYPE_IMAGES);

        $mocks = $this->fillArrayForDropzone($files);
        return response()->json($mocks);
    }

    public function getAllArchives()
    {
        $files = File::getAllByType(File::TYPE_ARCHIVE);
        $mocks = $this->fillArrayForDropzone($files);

        return response()->json($mocks);
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
