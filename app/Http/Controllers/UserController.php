<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\JsonService;
use App\Providers\PermissionService;
use App\Providers\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        $data = ['message' => 'User created'];
        return JsonService::sendJsonResponse($data, Response::HTTP_CREATED);
    }









    // READ

    /**
     * Fetches all users
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        $users = User::all();

        return JsonService::sendJsonResponse($users, Response::HTTP_OK);
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
            return JsonService::sendJsonResponse([$user], Response::HTTP_OK);
        } else {
            $data = ['message' => 'User not found'];
            return JsonService::sendJsonResponse($data, Response::HTTP_NOT_FOUND);
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
        $users = User::where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->get()
        ;

        return JsonService::sendJsonResponse($users, Response::HTTP_OK);
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
            if (!PermissionService::checkIfUserIsAllowed($id)) {
                $data = ['message' => 'Forbidden update of user.'];
                return JsonService::sendJsonResponse($data, Response::HTTP_FORBIDDEN);
            }
            if ($validated['name'] !== null) {
                $user->name = $validated['name'];
            }
            if ($validated['email'] !== null) {
                $user->email = $validated['email'];
            }
            if ($validated['password'] !== null) {
                $user->password = $validated['password'];
            }

            $user->save();

            $data = ['message' => 'User updated.'];
            return JsonService::sendJsonResponse($data, Response::HTTP_OK);
        } else {
            $data = ['message' => 'User not found.'];
            return JsonService::sendJsonResponse($data, Response::HTTP_NOT_FOUND);
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
            if (!PermissionService::checkIfUserIsAllowed($id)) {
                $data = ['message' => 'Forbidden delete of user.'];
                return JsonService::sendJsonResponse($data, Response::HTTP_FORBIDDEN);
            }
            $user->delete();

            $data = ['message' => 'User deleted'];
            return JsonService::sendJsonResponse($data, Response::HTTP_NO_CONTENT);
        } else {
            $data = ['message' => 'User not found'];
            return JsonService::sendJsonResponse($data, Response::HTTP_NOT_FOUND);
        }
    }
}
