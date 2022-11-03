<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the task.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Task::where('status', 0)->orderBy('due_date');
            $filter = $request->input('filter', '');
            $search = $request->input('search', '');

            if (strlen($search)) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            switch ($filter) {
                case 'today' :
                    $query->where('due_date', Carbon::now()->format('Y-m-d'));
                    break;
                case 'this_week' :
                    $query->whereBetween('due_date', [Carbon::now()->startOfWeek()->format('Y-m-d'), Carbon::now()->endOfWeek()->format('Y-m-d')]);
                    break;
                case 'last_week' :
                    $query->whereBetween('due_date', [Carbon::now()->startOfWeek()->subDays(7)->format('Y-m-d'), Carbon::now()->endOfWeek()->subDays(7)->format('Y-m-d')]);
                case 'overdue' :
                    $query->whereDate('due_date', '<', Carbon::now()->format('Y-m-d'));
                    break;
                default :
                    // Do nothing
            }

            $tasks = $query->get();

            return response()->json([
                'status' => true,
                'message' => null,
                'errors' => null,
                'data' => $tasks,
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => null,
                'data' => null,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created task in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'parent_id' => 'exists:tasks,id',
            'due_date' => 'required|date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "There are some errors",
                'errors' => $validator->errors(),
                'data' => null,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $task = new Task;
            $task->title = $request->title;
            $task->description = $request->description;
            $task->parent_id = $request->parent_id;
            $task->status = 0;
            $task->due_date = $request->due_date;

            $task->save();

            return response()->json([
                'status' => true,
                'message' => "Task saved successfully",
                'errors' => null,
                'data' => null,
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => null,
                'data' => null,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified task.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $task = Task::where('id', $id)->with('subTasks')->first();

            if (empty($task)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot find any task with the given id',
                    'errors' => null,
                    'data' => null,
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => true,
                'message' => null,
                'errors' => null,
                'data' => $task,
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => null,
                'data' => null,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified task and its subtasks from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $task = Task::where('id', $id)->first();

            if (empty($task)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot find any task with the given id',
                    'errors' => null,
                    'data' => null,
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            $task->subTasks()->delete();
            $task->delete($id);

            return response()->json([
                'status' => true,
                'message' => 'The task has been deleted successfully',
                'errors' => null,
                'data' => null,
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => null,
                'data' => null,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mark the specified task and its subtasks as complete.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function complete($id)
    {
        try {
            $task = Task::where('id', $id)->first();

            if (empty($task)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot find any task with the given id',
                    'errors' => null,
                    'data' => null,
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            $task->status = 1;
            $task->save();

            Task::where('parent_id', $id)->update(['status' => 1]);

            return response()->json([
                'status' => true,
                'message' => 'The task has been marked as completed',
                'errors' => null,
                'data' => null,
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => null,
                'data' => null,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
