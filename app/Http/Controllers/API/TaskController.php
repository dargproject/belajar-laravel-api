<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Task\TaskIndexResources;
use App\Http\Resources\Task\TaskResources;
use App\Models\Task;
use Illuminate\Http\Request;
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

        if (in_array($extention, ['jpg', 'png', 'jpeg'])) {
            $upload_file = $request->file('upload_file')->store('public/images');
        } else {
            $upload_file = $request->file('upload_file')->store('public/files');
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

    public function destroy(Task $Task)
    {
        $Task->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Dihapus',
            'data' => [],
        ], Response::HTTP_NO_CONTENT);
    }
}
