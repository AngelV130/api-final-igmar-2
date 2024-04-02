<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id = null)
    {
        try{
            // Read Categorys from the database and return json response
            if($id){
                $category = Category::find($id);
                if(!$category){
                    return response()->json(['message' => 'No se encontró la categoría'], 404);
                }
                return response()->json($category);
            }
            $categoryes = Category::all()->select('id', 'name');
            return response()->json($categoryes);
        }catch (QueryException $e) {
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
        catch (\Exception $e) {
            Log::channel('slack')->error($e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500,
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            //Create Category, validate and return json response
            $data = request()->validate([
                'name' => 'required|string'
            ],
            [
                'name.required' => 'El campo nombre es requerido',
                'name.string' => 'El campo nombre debe ser una cadena de texto'
            ]);
            $data['status'] = true;
            $category = Category::create($data);
            return response()->json([
                'message' => 'Categoría creada correctamente',
                'category' => $category
            ]);
        }catch (QueryException $e) {
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
        catch (\Exception $e) {
            Log::channel('slack')->error($e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            //Edit Category, validate and return json response
            $data = request()->validate([
                'name' => 'required|string'
            ],
            [
                'name.required' => 'El campo nombre es requerido',
                'name.string' => 'El campo nombre debe ser una cadena de texto'
            ]);
            $category = Category::find($id);
            if(!$category){
                return response()->json(['message' => 'No se encontró la categoría'], 404);
            }
            $category->update($data);
            return response()->json([
                'message' => 'Categoría actualizada correctamente',
                'category' => $category
            ]);
        }catch (QueryException $e) {
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
        catch (\Exception $e) {
            Log::channel('slack')->error($e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            //Delte Category and return json response
            $category = Category::find($id);
            if(!$category){
                return response()->json(['message' => 'No se encontró la categoría'], 404);
            }
            $category->delete();
            return response()->json([
                'message' => 'Categoría eliminada correctamente',
                'category' => $category
            ]);
        }catch (QueryException $e) {
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
        catch (\Exception $e) {
            Log::channel('slack')->error($e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500,
            ], 500);
        }
    }
}
