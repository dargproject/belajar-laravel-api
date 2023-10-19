<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserIndexResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = UserIndexResource::collection(
            User::query()->paginate(5)
        );

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil ditampilan',
            'data' => $user->items(),
            'meta' => [
                'total' => $user->total(),
                'per_page' => $user->perPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {

        $user = User::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil ditambahkan',
            'data' => new UserResource($user),

        ], Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil ditampilkan',
            'data' => new UserResource($user),

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Diubah',
            'data' => new UserResource($user),

        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Dihapus',
            'data' => [],
        ], Response::HTTP_NO_CONTENT);
    }
}
