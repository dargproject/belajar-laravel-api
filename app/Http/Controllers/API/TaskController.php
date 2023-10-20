<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Task\TaskIndexResources;
use App\Http\Resources\Task\TaskResources;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index()
    {
        $Task = TaskIndexResources::collection(
            Task::query()->with('user')->paginate(5)
        );

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil ditampilan',
            'data' => $Task->items(),
            'meta' => [
                'total' => $Task->total(),
                'per_page' => $Task->perPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $extention = $request->file('upload_file')->getClientOriginalExtension();
        $namafile = $request->file('upload_file')->getClientOriginalName();
        $timesstamp = date('d-m-y');
        if (in_array($extention, ['jpg', 'png', 'jpeg'])) {
            $upload_file = $request->file('upload_file')->storeAs('public/images',  auth()->user()->id .'-'. $timesstamp .'-'. $namafile);
        } else {
            $upload_file = $request->file('upload_file')->storeAs('public/files', auth()->user()->id .'-'. $timesstamp .'-'. $namafile);
        }

        $task = Task::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'upload_file' => $upload_file,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan data',
            'data' => new TaskResources($task),
        ], Response::HTTP_CREATED);
    }

    public function show(Task $Task)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil ditampilkan',
            'data' => new TaskResources($Task),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()],
                400);
        }
        $timesstamp = date('d-m-y');
        $task = Task::find($id);
        if (! $task) {
            return response()->json([
                'pesan' => 'Data Tidak Ditemukan'], 404);
        }
        $upload_file = $task->upload_file;
        if ($request->hasFile('upload_file')) {
            File::delete(storage_path("app/$upload_file"));
            $extention = $request->file('upload_file')->getClientOriginalExtension();
            $namafile = $request->file('upload_file')->getClientOriginalName();
        }
        if (in_array($extention, ['jpg', 'png', 'jpeg'])) {
            $upload_file = $request->file('upload_file')->storeAs('public/images',  auth()->user()->id .'-'. $timesstamp .'-'. $namafile);
        } else {
            $upload_file = $request->file('upload_file')->storeAs('public/files', auth()->user()->id .'-'. $timesstamp .'-'. $namafile);
        }

        Task::where('id', $id)->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'upload_file' => $upload_file
        ]);
         // $task->update([
        //     'title' =>  $request->input('title'),
        //     'description' => $request->input('description'),
        // ]);

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Diubah',
            'data' => new TaskResources($task),

        ]);

    }

    public function destroy(Task $Task)
    {
        $Task->delete();
        File::delete(storage_path("app/$Task->upload_file"));

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Dihapus',
            'data' => [],
        ], Response::HTTP_NO_CONTENT);
    }
}
