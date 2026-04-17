<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InternSession;
use App\Models\SessionTask;

class TaskController extends Controller
{
    /**
     * Show form to edit tasks for a specific intern session
     */
    public function editTasks($sessionId)
    {
        $session = InternSession::with('tasks')->findOrFail($sessionId);

        // If no tasks exist, pre-fill with 5 empty task slots
        if ($session->tasks->isEmpty()) {
            $defaultTasks = collect(range(1, 5))->map(function () {
                return new \App\Models\SessionTask([
                    'task_name' => '',
                    'rating' => null,
                    'description' => '',
                ]);
            });
            $session->setRelation('tasks', $defaultTasks);
        }

        return view('supervisor.tasks.edit', compact('session'));
    }


    /**
     * Update or insert tasks with ratings for a specific intern session
     */
    public function updateTasks(Request $request, $sessionId)
    {
        $request->validate([
            'tasks.*.task_name' => 'required|string|max:255',
            'tasks.*.rating' => 'nullable|integer|min:1|max:5',
            'tasks.*.description' => 'nullable|string|max:2000',
        ]);

        foreach ($request->tasks as $taskData) {
            SessionTask::updateOrCreate(
                ['id' => $taskData['id'] ?? null],
                [
                    'intern_session_id' => $sessionId,
                    'task_name' => $taskData['task_name'],
                    'rating' => $taskData['rating'] ?? null,
                    'description' => $taskData['description'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Tasks updated successfully.');
    }
    public function approve(InternSession $session)
    {
        $session->is_approved = true;
        $session->save();

        return back()->with('success', 'Session approved successfully.');
    }

}
