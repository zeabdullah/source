<?php

use App\Http\Middleware\EnsureUserIsProjectOwner;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('throws an exception if a projectId route parameter is not provided', function () {
    $user = User::factory()->create();

    [$request, $route] = createTestRequestAndRoute();

    $request->setUserResolver(fn() => $user);
    $request->setRouteResolver(fn() => $route);

    $middleware = new EnsureUserIsProjectOwner();

    expect(fn() => $middleware->handle($request, fn() => response('ok')))
        ->toThrow(
            \Exception::class,
            "'" . EnsureUserIsProjectOwner::class . "'" . " can only be passed to routes that take a 'projectId' route parameter"
        );
});

it("returns a 404 if the project DOES NOT exist", function () {
    $user = User::factory()->create();

    [$request, $route] = createTestRequestAndRoute();

    $request->setUserResolver(fn() => $user);
    $request->setRouteResolver(function () use ($route) {
        $route->setParameter('projectId', '999');
        return $route;
    });

    $middleware = new EnsureUserIsProjectOwner();
    $response = $middleware->handle($request, fn() => response('ok'));

    expect($response->getStatusCode())->toBe(404);
    expect($response->getData())->toMatchObject([
        'payload' => null,
        'message' => 'Project not found'
    ]);
});

it("returns a 404 if the user is NOT the project owner", function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    [$request, $route] = createTestRequestAndRoute();

    $request->setUserResolver(fn() => $user);
    $request->setRouteResolver(function () use ($project, $route) {
        $route->setParameter('projectId', (string) $project->id);
        return $route;
    });

    $middleware = new EnsureUserIsProjectOwner();
    $response = $middleware->handle($request, fn() => response('ok'));

    expect($response->getStatusCode())->toBe(404);
    expect($response->getData())->toMatchObject([
        'payload' => null,
        'message' => 'Project not found'
    ]);
});

it("passes if the user IS the project owner", function () {
    $user = User::factory()->create();
    $project = Project::factory()->recycle($user)->create();

    [$request, $route] = createTestRequestAndRoute();

    $request->setUserResolver(fn() => $user);
    $request->setRouteResolver(function () use ($project, $route) {
        $route->setParameter('projectId', (string) $project->id);
        return $route;
    });

    $middleware = new EnsureUserIsProjectOwner();
    $response = $middleware->handle($request, fn() => response('ok'));

    expect($response->getContent())->toBe('ok');
});
