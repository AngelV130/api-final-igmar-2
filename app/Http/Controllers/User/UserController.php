<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PDOException;
use Exception;

class UserController extends Controller
{

    /**
     * Vista de todos los usuarios (Solo de Administrador)
     */
    public function users() {
        $users = User::with('roles')->get();
        $users = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->roles->name,
                'status' => $user->status == 1 ? 'Activo' : 'Inactivo'
            ];
        });
        return response()->json([
            'users' => $users,
            'status' => 200
        ]);
    }

    /**
     * Vista de la informacion del usuario
     */
    public function getuser (Request $request, $id) {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => 'No se encontró el usuario',
                'status' => 404
            ], 404);
        }
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'rol' => $user->rol,
                'status' => $user->status
            ],
            'status' => 200
        ]
        );
    }

    /**
     * Visa de la informacion del usuario
     */
    public function perfil (Request $request) {
        $user = User::with('roles')->find($request->user()->id);
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->roles->name,
                'status' => $user->status == 1 ? 'Activo' : 'Inactivo'
            ],
            'status' => 200
        ]
        );
    }

    // Get Roles
    public function getRoles() {
        $roles = Roles::all();
        return response()->json([
            'roles' => $roles,
            'status' => 200
        ]);
    }

    /**
     * Actualizar la informacion del usuario
     */
    public function edit (Request $request, $id) {
        try{
            $request->validate([
                'name' => 'required|string',
                'rol' => 'required|numeric', // 'numeric' es para validar que sea un número
                'status' => 'required|boolean',
            ],[
                'name.required' => 'El nombre es requerido',
                'name.string' => 'El nombre no es valido',
                'status.required' => 'El estado es requerido',
                'status.boolean' => 'El estado no es valido',
                'rol.required' => 'El rol es requerido',
                'rol.numeric' => 'El rol no es valido'
            ]);
            $user = User::find($id);
            if(!$user){
                return response()->json([
                    'message' => 'No se encontró el usuario',
                    'status' => 404
                ], 404);
            }
            $user->name = $request->name;
            $user->status = $request->status;
            $user->rol = $request->rol;
            $user->save();
            return response()->json([
                'message' => 'Usuario actualizado correctamente',
                'status' => 200
            ]);
        }
        catch (QueryException $e) {
            Log::channel('slack')->error($e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500
            ], 500);
        } catch (PDOException $e) {
            Log::error('Error de PDO: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500
            ], 500);
        }catch(ValidationException $e){
            return response()->json([
                'messageError' => 'Error de validacion',
                'status' => 401,
                'errors' => $e->errors()
            ], 401);
        }
        catch (Exception $e) {
            Log::channel('slack')->error($e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500,
            ], 500);
        }
    }

    /**
     * Eliminar un usuario
     */
    public function destroy($id) {
        try{
            $user = User::find($id);
            if(!$user){
                return response()->json([
                    'message' => 'No se encontró el usuario',
                    'status' => 404
                ], 404);
            }
            $user->status = 0;
            $user->save();
            return response()->json([
                'message' => 'Usuario eliminado correctamente',
                'status' => 200
            ]);
        }
        catch (QueryException $e) {
            Log::channel('slack')->error($e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500
            ], 500);
        } catch (PDOException $e) {
            Log::error('Error de PDO: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500
            ], 500);
        }catch(ValidationException $e){
            return response()->json([
                'messageError' => 'Error de validacion',
                'status' => 401,
                'errors' => $e->errors()
            ], 401);
        }
        catch (Exception $e) {
            Log::channel('slack')->error($e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500,
            ], 500);
        }
    }   
}
