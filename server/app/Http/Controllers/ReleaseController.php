<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReleaseController extends Controller
{
    public function createRelease(Request $request, string $projectId)
    {
        return $this->notImplementedResponse();
    }

    public function getProjectReleases(Request $request, string $projectId)
    {
        return $this->notImplementedResponse();
    }

    public function getReleaseById(Request $request, string $releaseId)
    {
        return $this->notImplementedResponse();
    }

    public function updateReleaseById(Request $request, string $releaseId)
    {
        return $this->notImplementedResponse();
    }

    public function deleteReleaseById(Request $request, string $releaseId)
    {
        return $this->notImplementedResponse();
    }
}
