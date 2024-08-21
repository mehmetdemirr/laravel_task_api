<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\CustomPaginator;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Varsayılan olarak 20
        $tasks = Task::orderBy("id","desc")->paginate($perPage);
        $customPaginator = new CustomPaginator(
            $tasks->items(),
            $tasks->total(),
            $tasks->perPage(), 
            $tasks->currentPage(), [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);
        return response()->json($customPaginator->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request)
    {
        $task = Task::create($request->validated());

        return response()->json([
            'success'=> true ,
            "data" => $task,
            "error" => null,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);

        if(is_null($task))
        {
            return response()->json([
                'success'=> false,
                'data'=> null,
                'error'=> 'Task bulunamadı',
            ],404);
        }
        else
        {
            return response()->json([
                'success'=> true,
                'data'=> $task,
                'error'=> null,
            ],200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $task = Task::find($id);
        if(is_null($task))
        {
            return response()->json([
               'success'=> false,
                'data'=> null,
                'error'=> 'Task bulunamadı',
            ],404);
        }
        else
        {
            $task->update($request->validated());
            return response()->json([
               'success'=> true,
                'data'=> $task,
                'error'=> null,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if(is_null($task))
        {
            return response()->json([
                'success'=> false,
                 'data'=> null,
                 'error'=> 'Task bulunamadı',
             ],404);
        }
        else
        {
            $task->delete();
            return response()->json([
                'success'=> true,
                 'data'=> "Başarıyla silindi",
                 'error'=> null,
             ],200);
        }
    }

    public function change_completed(string $id)
    {
        $task = Task::find($id);
        if(is_null($task))
        {
            return response()->json([
                'success'=> false,
                'data'=> null,
                'error'=> 'Task bulunamadı',
             ],404);
        }
        else
        {
            $is_completed = !$task->is_completed;
            $task->is_completed = $is_completed;
            $task->save();
            return response()->json([
                'success'=> true,
                'data'=> "is_completed $is_completed olarak değişti",
                'error'=> null,
             ],200);
        }
    }
}
