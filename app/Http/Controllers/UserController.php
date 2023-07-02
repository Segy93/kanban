<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/** CRUD methods for managing users */
class UserController extends Controller
{
    // CREATE

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

        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->password = $validated['password'];

        $user->save();

        return response()->json([
            'message' => 'User created',
        ], Response::HTTP_CREATED);
    }









    // READ

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
     * @param string $search    String used for searching
     *
     * @return JsonResponse
     */
    public function search(string $search): JsonResponse {
        $users = User::where('name', 'like', '%' . $search . '%');
        $users->orWhere('email', 'like', '%' . $search . '%');

        return response()->json($users);
    }









    // UPDATE

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
            if (Auth::id() !== $id) {
                return response()->json([
                    'message' => 'Forbidden update of user.'
                ], Response::HTTP_FORBIDDEN);
            }
            $user->name      = is_null($validated['name'])
                ? $user->name : $validated['name']
            ;
            $user->email     = is_null($validated['email'])
                ? $user->email : $validated['email']
            ;
            $user->password  = is_null($validated['password'])
                ? $user->password : $validated['password']
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









    // DELETE

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
            if (Auth::id() !== $id) {
                return response()->json([
                    'message' => 'Forbidden delete of user.'
                ], Response::HTTP_FORBIDDEN);
            }
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
