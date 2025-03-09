<?php

namespace App\Http\Controllers;

use App\Http\Resources\StreamResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Stream;
use Illuminate\Support\Facades\Validator;

class StreamApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stream = Stream::get();
        return StreamResource::collection($stream);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stream = Stream::find($id);

        if (!$stream) {
            return response()->json(['message'=> 'stream not found'], 404);
        }

        return new StreamResource($stream);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
