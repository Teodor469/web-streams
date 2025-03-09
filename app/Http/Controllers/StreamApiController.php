<?php

namespace App\Http\Controllers;

use App\Http\Resources\StreamResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Stream;
use Illuminate\Support\Facades\Validator;

class StreamApiController extends Controller
{
    public function index()
    {
        $stream = Stream::get();
        return StreamResource::collection($stream);
    }


    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:655',
            'tokens_price' => 'required|integer',
            'type_id' => 'nullable|exists:stream_types,id',
            'date_expiration' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $stream = Stream::create([
            'title' => $request->title,
            'description' => $request->description,
            'tokens_price' => $request->tokens_price,
            'type_id' => $request->type_id,
            'date_expiration' => $request->date_expiration,
        ]);

        return response()->json([
            'message'=> 'Stream created successfully',
            'data' => new StreamResource($stream),
        ], 201);

    }


    public function show($id)
    {
        $stream = Stream::find($id);

        if (!$stream) {
            return response()->json(['message'=> 'stream not found'], 404);
        }

        return new StreamResource($stream);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:655',
            'tokens_price' => 'required|integer',
            'type_id' => 'nullable|exists:stream_types,id',
            'date_expiration' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $stream = Stream::find($id);

        $stream->update([
            'title' => $request->title,
            'description' => $request->description,
            'tokens_price' => $request->tokens_price,
            'type_id' => $request->type_id,
            'date_expiration' => $request->date_expiration,
        ]);

        return response()->json([
            'message' => 'Web stream updated successfully',
            'date' => new StreamResource($stream),
        ], 201);
    }


    public function destroy($id)
    {
        $stream = Stream::find($id);

        if (!$stream) {
            return response()->json([
                'message' => 'Stream does not exist'
            ], 404);
        }

        $stream->delete();

        return response()->json([
            'message' => "Stream deleted successfully"
        ], 200);
    }
}
