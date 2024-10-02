<?php

namespace App\Http\Controllers;

use App\Models\Task;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('user')
            ->where('completed', false)
            ->where('status', 'A')
            ->orderBy('datecreate', 'asc') // Ordenar por fecha de creación ascendente
            ->get();

        // Obtener todas las tareas completadas
        $completedTasks = Task::with('user')
            ->where('completed', true)
            ->where('status', 'A')
            ->orderBy('datecreate', 'asc') // También puedes ordenar estas si lo deseas
            ->get();
        //obtener las tareas eliminadas
        $deletedTasks = Task::with('user')
            ->where('status', 'I')
            ->orderBy('datecreate', 'asc')
            ->get();

        return response()->json([
            'tasks' => $tasks,
            'completedTasks' => $completedTasks,
            'deletedTasks' => $deletedTasks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:urgente,importante,necesario',
            'dateexpire' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'completed' => 'boolean',
            'status' => 'in:A,I',
        ]);
        // Asignar el user_id del usuario autenticado

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->category = $request->category;
        $task->datecreate = now()->format('Y-m-d');
        $task->dateexpire = $request->dateexpire;
        $task->user_id = auth()->id(); // Asignar el usuario autenticado
        $task->completed = false; // Por defecto, la tarea no está completada
        $task->status = 'A'; // Por defecto, la tarea está activa

        $path = $request->file('image')->store('images', 'public'); // Almacenar en el disco público
        $url = Storage::url($path); // Obtener la URL de la imagen
        $task->image = $url;

        $task->save();

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'exists:users,id',
            'category' => 'in:urgente,importante,necesario',
            'datecreate' => 'date',
            'dateexpire' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'completed' => 'boolean',
            'status' => 'in:A,I',
        ]);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->category = $request->category;
        $task->dateexpire = $request->dateexpire;

        // Manejo de la imagen
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($task->image) {
                $oldImagePath = str_replace('/storage/', '', $task->image); // Obtener la ruta del almacenamiento
                Storage::delete($oldImagePath); // Eliminar la imagen anterior
            }

            $path = $request->file('image')->store('images', 'public'); // Almacenar la nueva imagen
            $url = Storage::url($path); // Obtener la URL de la nueva imagen
            $task->image = $url;
        }
        $task->save();

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $task = Task::find($id);

        if ($task) {
            $task->status = 'I'; // Cambiar el estatus a 'I' (inactivo o eliminado)
            $task->save();

            return response()->json(['message' => 'Tarea eliminada correctamente'], 200);
        } else {
            return response()->json(['message' => 'Tarea no encontrada'], 404);
        }
    }
}
