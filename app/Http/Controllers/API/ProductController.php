<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $data_validator = [
            'name' => 'required|string|max:255|unique:products',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'id_product_category' => 'required|numeric|exists:product_categories,id',
        ];

        $validator = Validator::make($request->all(), $data_validator);

        if ($validator->fails()) {
            $response = [
                'code' => 400,
                'message' => $validator->errors()->all(),
                'data' => [],
            ];
            return response()->json($response, 200);
        } else {
            $product = Product::create($request->all());
            $response = [
                'code' => 201,
                'message' => 'Product created',
                'data' => $product,
            ];
            return response()->json($response, 200);
        }
    }

    public function read($id = '')
    {
        if (empty($id)) {
            $products = Product::all();
            $response = [
                'code' => 200,
                'message' => 'Products read',
                'data' => $products,
            ];
            return response()->json($response, 200);
        } else {
            $product = Product::find($id);
            if ($product) {
                $response = [
                    'code' => 200,
                    'message' => 'Product read',
                    'data' => $product,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'code' => 404,
                    'message' => 'Product not found',
                    'data' => [],
                ];
                return response()->json($response, 200);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $data_validator = [
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'id_product_category' => 'required|numeric|exists:product_categories,id',
        ];

        $validator = Validator::make($request->all(), $data_validator);

        if ($validator->fails()) {
            $response = [
                'code' => 400,
                'message' => $validator->errors()->all(),
                'data' => [],
            ];
            return response()->json($response, 200);
        } else {
            $product = Product::find($id);
            if ($product) {
                $product->update($request->all());
                $response = [
                    'code' => 200,
                    'message' => 'Product updated',
                    'data' => $product,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'code' => 404,
                    'message' => 'Product not found',
                    'data' => [],
                ];
                return response()->json($response, 200);
            }
        }
    }

    public function delete(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            $response = [
                'code' => 200,
                'message' => 'Product deleted',
                'data' => [],
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'code' => 404,
                'message' => 'Product not found',
                'data' => [],
            ];
            return response()->json($response, 200);
        }
    }
}
