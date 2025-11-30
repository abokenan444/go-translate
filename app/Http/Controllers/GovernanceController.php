<?php

namespace App\Http\Controllers;

use App\Services\Governance\GovernanceService;
use Illuminate\Http\Request;

class GovernanceController extends Controller
{
    public function __construct(protected GovernanceService $gov) {}

    public function auditLogs()
    {
        return response()->json(['success' => true, 'data' => $this->gov->recent(100)]);
    }
}
