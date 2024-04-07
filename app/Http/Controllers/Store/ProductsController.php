<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

// PDO Exepcion
use Illuminate\Database\QueryException;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use PDOException;
use Exception;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id = null)
    {
        try{
            //Read Products whit Category from the database and return json response
            if($id){
                $product = Product::find($id);
                if(!$product){
                    return response()->json(['message' => 'Producto no encontrado'], 404);
                }
                return response()->json([
                    'name' => $product->name,
                    'precio' => $product->precio,
                    'category_id' => $product->category_id
                ]);
            }

            $products = Product::with('category')->get()->where('status', true);
            $products = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'nombre' => $product->name,
                    'precio' => $product->precio,
                    'categoria' => $product->category->name, // Accede al nombre de la categoría
                ];
            });

            return response()->json($products);
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
            //Create Product, validate and return json response
            $data = request()->validate([
                'name' => 'required|string',
                'precio' => 'required|numeric',
                'category_id' => 'required|numeric',
            ],
            [
                'name.required' => 'El campo nombre es requerido',
                'name.string' => 'El campo nombre debe ser una cadena de texto',
                'precio.required' => 'El campo precio es requerido',
                'precio.numeric' => 'El campo precio debe ser un número',
                'category_id.required' => 'El campo category_id es requerido',
                'category_id.numeric' => 'El campo category_id debe ser un número',
            ]);
            $data['status'] = true;
            $product = Product::create($data);
            return response()->json([
                'message' => 'Producto creado correctamente',
                'product' => $product
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
        catch (Exception $e) {
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
            //Edit Product, validate and return json response
            $data = request()->validate([
                'name' => 'required|string',
                'precio' => 'required|numeric',
                'category_id' => 'required|numeric',
            ],
            [
                'name.required' => 'El campo nombre es requerido',
                'name.string' => 'El campo nombre debe ser una cadena de texto',
                'precio.required' => 'El campo precio es requerido',
                'precio.numeric' => 'El campo precio debe ser un número',
                'category_id.required' => 'El campo category_id es requerido',
                'category_id.numeric' => 'El campo category_id debe ser un número',
            ]);
            $product = Product::find($id);
            if(!$product){
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }
            $product->update($data);
            return response()->json([
                'message' => 'Producto actualizado correctamente',
                'product' => $product
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
        catch (Exception $e) {
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
            //Delete Product and return json response
            $product = Product::find($id);
            if(!$product){
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }
            $product->status = false;
            $product->save();
            return response()->json($product);
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
        catch (Exception $e) {
            Log::channel('slack')->error($e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'status'=> 500,
            ], 500);
        }
    }
}
