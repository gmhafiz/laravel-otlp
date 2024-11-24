<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepositories;
use App\Models\User;
use Illuminate\Http\Request;
use OpenTelemetry\API\Globals;

class UserController extends Controller
{
    private UserRepositories $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepositories();
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $tracer = Globals::tracerProvider()->getTracer('');

        $root = $tracer->spanBuilder('index')->startSpan();
        $scope = $root->activate();

        try {
            return $this->userRepo->all($tracer);
        } catch (\Exception $e) {
            $root->recordException($e);
            throw $e;
        } finally {
            $scope->detach();
            $root->end();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
