<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /** CREATE */

    /**
     * Creating single user
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse {
        $validated = UserService::validateDataCreate($request);
        $user = new User();

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            'message' => 'User created',
        ], Response::HTTP_CREATED);
    }









    /** READ */

    /**
     * Fetches all users
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Fetch user by id
     *
     * @param int $id    id of user
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse {
        $user = User::find($id);

        if (!empty($user)) {
            return response()->json([
                $user
            ]);
        } else {
            return response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Search users
     *
     * @param string $search    String user for searching
     *
     * @return JsonResponse
     */
    public function search(string $search): JsonResponse {
        $users = User::where('name', 'like', '%' . $search . '%');
        $users->orWhere('email', 'like', '%' . $search . '%');

        return response()->json($users);
    }









    /** UPDATE */

    /**
     * Updating of single user
     *
     * @param Request  $request
     * @param int      $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse {
        $validated = UserService::validateDataUpdate($request, $id);
        $user = User::find($id);

        if (!empty($user)) {
            $user->name      = !empty($request->name)
                ? $request->name : $user->name
            ;
            $user->email     = !empty($request->email)
                ? $request->email : $user->email
            ;
            $user->password  = !empty($request->password)
                ? Hash::make($request->password) : $user->password
            ;

            $user->save();

            return response()->json([
                'message' => 'User updated'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }









    /** DELETE */

    /**
     * Deleting single user
     *
     * @param int $id    Id of deleting user
     *
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse {
        $user = User::find($id);

        if (!empty($user)) {
            $user->delete();

            return response()->json([
                'message' => 'User deleted'
            ], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}

