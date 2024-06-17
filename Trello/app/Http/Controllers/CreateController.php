<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CreateController extends Controller
{
    public function create(Request $request)
    {
        $validator = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:To Do,In Progress,Done',
            'priority' => 'required|in:Low,Medium,High',
        ]);
    
        $userId = Auth::id(); 
    
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'user_id' => $userId, 
        ]);
    

        return response()->json($task);
    }
    

    public function show(Request $request)
    {
        $user_id = $request->user()->id;

        
        $tasks = Task::where('user_id', $user_id)
        ->orderByRaw("FIELD(priority, 'High', 'Medium', 'Low')")
        ->get();
        
        
        return response()->json($tasks);
    }
    
    public function filter(Request $request)
    {
        $priority = $request->input('priority');

        // Если приоритет выбран, фильтруем по нему, иначе возвращаем все задачи
        $tasks = $priority ? Task::where('priority', $priority)->get() : Task::all();
    
        return response()->json($tasks);
    }

    public function detal($id)
    {
        $task = Task::findOrFail($id);

        return response()->json($task);
    }


    public function delete($id)
    {
        // Найдем задачу по ID
        $task = Task::find($id);

        // Проверим, существует ли задача
        if ($task) {
            // Удалим задачу
            $task->delete();
            
            // Вернем ответ с успешным удалением
            return response()->json(['message' => 'Task deleted successfully.']);
        } else {
            // Если задача не найдена, вернем ответ с ошибкой
            return response()->json(['message' => 'Task not found.'], 404);
        }
    }


    public function update(Request $request, $id)
    {
        // Валидация данных запроса
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:To Do,In Progress,Done',
        ]);
    
        // Находим задачу по ID
        $task = Task::findOrFail($id);
    
        // Обновляем атрибуты задачи
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->priority = $request->input('priority');
        $task->status = $request->input('status'); // Обновляем статус задачи
    
        // Сохраняем обновленную задачу
        $task->save();
    
        // Возвращаем успешный ответ
        return response()->json(['message' => 'Task updated successfully']);
    }
    
    


}
