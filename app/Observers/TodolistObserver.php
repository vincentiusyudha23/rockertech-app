<?php

namespace App\Observers;

use App\Models\Todolist;
use Illuminate\Support\Facades\DB;

class TodolistObserver
{
    /**
     * Handle the Todolist "created" event.
     */
    public function created(Todolist $todolist): void
    {
        DB::table('todolists')->where('status', 1)->increment('index_task');

        $todolist->index_task = 1;
    }

    /**
     * Handle the Todolist "updated" event.
     */
    public function updated(Todolist $todolist): void
    {
        //
    }

    /**
     * Handle the Todolist "deleted" event.
     */
    public function deleted(Todolist $todolist): void
    {
        //
    }

    /**
     * Handle the Todolist "restored" event.
     */
    public function restored(Todolist $todolist): void
    {
        //
    }

    /**
     * Handle the Todolist "force deleted" event.
     */
    public function forceDeleted(Todolist $todolist): void
    {
        //
    }
}
