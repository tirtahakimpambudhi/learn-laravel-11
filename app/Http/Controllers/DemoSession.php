<?php

namespace App\Http\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DemoSession extends Controller
{

    /*
     * Check the exists data from session with key is id
     * and return not found if not found
     */
    private function existsSession(string $id,Request $request) : ?JsonResponse
    {
        if (!$request->session()->exists($id)) {
            return response()->json(data: [
                'errors' => [
                    [
                        'title' => "Session not found",
                        'status' => ResponseAlias::HTTP_NOT_FOUND,
                        'code' => 'NOT_FOUND',
                        'detail' => "session data with key id $id not found"
                    ]
                ]
            ],status: ResponseAlias::HTTP_NOT_FOUND);
        }
        return null;
    }

    /**
     * Display a listing of the resource.
     */
        public function index(Request $request) :JsonResponse
    {
        return response()->json(data: [
            'status' => ResponseAlias::HTTP_OK,
            'code' => 'STATUS_OK',
            'data' =>  $request->session()->except(['_token'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $id = Str::uuid()->toString();
            $rules = [
                'name' => 'required|string|max:255',
                'age' => 'required|integer|between:1,100',
            ];
            $data = $request->validate($rules);
            $request->session()->put($id, $data);
            return response()->json(data: [
                'status' => ResponseAlias::HTTP_CREATED,
                'code' => 'STATUS_CREATED',
                'data' => $request->session()->get($id)
            ]);
        } catch (ValidationException $exception) {
            throw new HttpResponseException(response: response(content: $exception->errors(), status: 422));
        } catch (\Exception $exception) {
            throw new HttpResponseException(response: response(content: $exception->getMessage(), status: 500));
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id,Request $request) : JsonResponse
    {
        $notExist = $this->existsSession($id,$request);
        if (!is_null($notExist)) {
            return $notExist;
        }
        return response()->json(data: [
            'data' => $request->session()->get($id),
            'status' => ResponseAlias::HTTP_OK,
            'code' => 'STATUS_OK',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( string $id,Request $request) : JsonResponse
    {
        $notExist = $this->existsSession($id,$request);
        if (!is_null($notExist)) {
            return $notExist;
        }
        $rules = [
            'name' => 'required|string|max:255',
            'age' => 'required|integer|between:1,100',
        ];
        $data = $request->validate($rules);
        $request->session()->put($id, $data);
        return response()->json(data: [
            'status' => ResponseAlias::HTTP_CREATED,
            'code' => 'STATUS_CREATED',
            'data' => $request->session()->get($id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,Request $request) : JsonResponse
    {
        $notExist = $this->existsSession($id,$request);
        if (!is_null($notExist)) {
            return $notExist;
        }
        $request->session()->forget($id);
        return response()->json(data: [
            'status' => ResponseAlias::HTTP_NO_CONTENT,
            'code' => 'STATUS_NO_CONTENT',
            'data' => null
        ]);
    }
}
