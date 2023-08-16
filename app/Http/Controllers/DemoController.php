<?php

namespace App\Http\Controllers;

use App\Demos\Contracts\ExampleSingleton;
use App\Http\Requests\DemoRequest;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class DemoController extends Controller
{
    public function __construct(public ExampleSingleton $example)
    {
    }

    public function index() {
        $this->example->printSomething();
        return "Hello World";
    }

    public function indexQuery(Request $request) {
        $name = ($request->query('name') !== null || !empty($request->query('name'))) ? $request->query('name') :'guest';
        return "Hello $name";
    }

    public function indexResponse(Request $request) :Response
    {
        return response(content: json_encode([
            'full_url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'host' => $request->host(),
            'path' => $request->path(),
        ]),status: 200,headers: [
            'CUSTOM_KEY' => 'Custom value',
        ])->header('KEY','VALUE')->withHeaders([
            'Content-Type' => 'application/json',
            'X-SECRET-KEY' => 'secret'
        ]);
    }

    public function indexJSONResponse(Request $request): JsonResponse
    {
        if (!$request->expectsJson()) {
            return response()->json([
                'message' => 'only support response json',
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'full_url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'host' => $request->host(),
            'path' => $request->path(),
        ], 200);
    }


    public function indexViewResponse(Request $request) :Response
    {
        return response()->view(view: 'demo',data :[
            'data' => [
                'fullUrl' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'userAgent' => $request->userAgent(),
                'host' => $request->host(),
                'path' => $request->path(),
            ]
        ], status: ResponseAlias::HTTP_OK
        );
    }

    public function indexBinaryFileResponse(Request $request): BinaryFileResponse
    {
        $filePath = storage_path('app/public/images/' . ($request->query('image') ?? 'image.jpg'));

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeType = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            default => 'application/octet-stream',
        };

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
        ]);
    }

    public function setCookie(DemoRequest $request) : Response
    {
        $data = $request->validated();
        $cookie = cookie(name: 'user_data',value: serialize($data),minutes: 5,path: '/');
        return response(content: json_encode([
            'message' => 'Cookie has been successfully set with key user_data',
        ]),status: ResponseAlias::HTTP_OK,headers: ['Content-Type'=>'application/json'])->cookie($cookie);
    }

    public function clearCookie(Request $request) : Response
    {
        return response(content: json_encode([
            'message' => 'Cookie has been successfully clear with key user_data',
        ]),status: ResponseAlias::HTTP_OK,headers: ['Content-Type'=>'application/json'])->withoutCookie('user_data');
    }

    public function getCookie(Request $request) : JsonResponse
    {
        $value = $request->cookie('user_data');
        if (empty($value)) {
            return response()->json([
                'message' => 'Cookie with key user_data is empty',
            ], ResponseAlias::HTTP_NO_CONTENT);
        }
        $data = unserialize($value);
        return response()->json($data, ResponseAlias::HTTP_OK);
    }

    public function redirect(string $name,Request $request) : RedirectResponse
    {
        try {
            $args = $request->query->all()??[];
            return response()->redirectToRoute(route: $name,parameters: [...$args]);
        } catch (RouteNotFoundException $exception) {
            throw new HttpResponseException(response: response(content: $exception->getMessage(), status: 404));
        } catch (\Exception $exception) {
            throw new HttpResponseException(response: response(content: $exception->getMessage(), status: 500));
        }

    }

    public function redirectMethod(string $method,Request $request) : RedirectResponse
    {
        try {
            $args = $request->query->all()??[];
            return response()->redirectToAction(action: [DemoController::class,$method],parameters: [...$args]);
        } catch (\InvalidArgumentException $exception) {
            throw new HttpResponseException(response: response(content: $exception->getMessage(), status: 404));
        } catch (\Exception $exception) {
            throw new HttpResponseException(response: response(content: $exception->getMessage(), status: 500));
        }
    }

    public function redirectGoogle(Request $request) : RedirectResponse
    {
        return redirect()->away('https://google.com');
    }

    public function indexBinaryDownloadResponse(Request $request) :BinaryFileResponse
    {
        $filePath = storage_path('app/public/images/' . ($request->query('image') ?? 'image.jpg'));

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath);
    }

    public function store(DemoRequest $request): string
    {
        // The validation is already handled by DemoRequest
        // No need to call validate() again since FormRequest handles it automatically

        // Get the validated data
        $validatedData = $request->validated();

        // Return proper JSON response
        return json_encode($validatedData);
    }

    public function stores(Request $request) :?string
    {
        try {

            // Define the validation rules
            $rules = [
                'friends' => 'required|array',
                'friends.*.name' => 'required|string',
                'friends.*.address' => 'required|string',
            ];

            // Validate the request with the defined rules
            $request->validate($rules);

            // Return the names of all friends in a JSON response
            return json_encode(['data' => $request->input('friends.*.name')]);
        } catch (ValidationException $exception) {
            throw new HttpResponseException(response: response(content: $exception->errors(), status: 422));
        }
    }

    public function storesOnly(Request $request) :?string
    {
        try {

            // Define the validation rules
            $rules = [
                'friends' => 'required|array',
                'friends.*.name' => 'required|string',
                'friends.*.address' => 'required|string',
            ];

            // Validate the request with the defined rules
            $request->validate($rules);

            // Return the names of all friends in a JSON response
            return json_encode(['data' => $request->only(['friends'])]);
        } catch (ValidationException $exception) {
            throw new HttpResponseException(response: response(content: $exception->errors(), status: 422));
        }
    }

    public function storesMerge(Request $request) :?string
    {
        try {

            // Define the validation rules
            $rules = [
                'friends' => 'required|array',
                'friends.*.name' => 'required|string',
                'friends.*.address' => 'required|string',
            ];

            // Validate the request with the defined rules
            $request->validate($rules);
            $request->merge(['enemy' => null]);

            // Return the names of all friends in a JSON response
            return json_encode(['data' => $request->input()]);
        } catch (ValidationException $exception) {
            throw new HttpResponseException(response: response(content: $exception->errors(), status: 422));
        }
    }

    public function storeTask(TaskRequest $request) :?string
    {
        $data = $request->validated();
        $photo = ($data['image'] instanceof UploadedFile) ?$data['image'] :$request->file('image');
        $photo->storePubliclyAs('images',$photo->getClientOriginalName(),'public');

        return json_encode([
            'data' => $request->except('image'),
            'message' => "successfully store '" . $photo->getClientOriginalName() . "' to " . storage_path('app/public/images')
        ]);
    }

}
