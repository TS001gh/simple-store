<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;


class CheckOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current action method
        $action = $request->route()->getActionMethod();

        // Define the actions to exclude
        $excludedActions = ['index', 'show'];

        if (in_array($action, $excludedActions)) {
            return $next($request);
        }
        $id = $request->route('id');

        $model = null;

        // dd(backpack_user()->id);
        if ($request->is('admin/product/*')) {
            $model = \App\Models\Product::find($id);
        } elseif ($request->is('admin/category/*')) {
            $model = \App\Models\Category::find($id);
        }


        if ($model && $model->user_id !== backpack_user()->id && backpack_user()->role !== "admin") {
            $name = Str::singular($model->getTable());
            return redirect("/admin/{$name}")->with('error', 'ليس مصرح لك');
        }


        return $next($request);
    }
}
