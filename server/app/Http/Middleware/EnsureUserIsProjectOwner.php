<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsProjectOwner extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $projectId = $request->route()->parameter('projectId');
        if (!$projectId) {
            throw new \Exception("'" . static::class . "'" . " can only be passed to routes that take a 'projectId' route parameter");
        }

        if (!is_string($projectId)) {
            return $this->badRequestResponse('Project ID must be a string');
        }

        $project = Project::find($projectId);
        $isOwner = $project?->owner_id === $request->user()->id;

        if (!$project || !$isOwner) {
            return $this->notFoundResponse('Project not found');
        }

        $request->attributes->set('project', $project);

        return $next($request);
    }
}
