<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\User;
use App\Models\UniversityStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UniversityDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $university = University::where('user_id', $user->id)
            ->with(['activeStudents'])
            ->first();
        
        if (!$university) {
            return redirect()->route('dashboard')->with('error', 'University account not found');
        }

        $stats = [
            'total_students' => $university->current_students_count,
            'max_students' => $university->max_students,
            'active_departments' => $university->universityStudents()->distinct('department')->count('department'),
            'monthly_usage' => $this->getMonthlyUsage($university),
            'api_calls' => $this->getApiCalls($university),
        ];

        return view('university.dashboard', compact('university', 'stats'));
    }

    public function students()
    {
        $user = Auth::user();
        $university = University::where('user_id', $user->id)->first();
        
        if (!$university) {
            return redirect()->route('dashboard')->with('error', 'University account not found');
        }

        $students = $university->universityStudents()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('university.students', compact('university', 'students'));
    }

    public function addStudent(Request $request)
    {
        $user = Auth::user();
        $university = University::where('user_id', $user->id)->first();
        
        if (!$university) {
            return back()->with('error', 'University account not found');
        }

        if (!$university->canAddMoreStudents()) {
            return back()->with('error', 'Student limit reached. Please upgrade your plan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'student_id' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:100',
            'program' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Create user account for student
            $studentUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('student' . rand(1000, 9999)), // Temporary password
                'role' => 'user',
                'account_status' => 'active',
                'account_type' => 'customer', // Students are customers
            ]);

            // Link student to university
            UniversityStudent::create([
                'university_id' => $university->id,
                'user_id' => $studentUser->id,
                'student_id' => $request->student_id,
                'department' => $request->department,
                'program' => $request->program,
                'status' => 'active',
                'enrolled_at' => now(),
            ]);

            $university->incrementStudentCount();

            DB::commit();

            // Send welcome email to student
            // $studentUser->notify(new StudentWelcomeNotification($university));

            return back()->with('success', 'Student added successfully. Welcome email sent.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add student: ' . $e->getMessage());
        }
    }

    public function removeStudent($studentId)
    {
        $user = Auth::user();
        $university = University::where('user_id', $user->id)->first();
        
        if (!$university) {
            return back()->with('error', 'University account not found');
        }

        $universityStudent = UniversityStudent::where('university_id', $university->id)
            ->where('id', $studentId)
            ->first();

        if (!$universityStudent) {
            return back()->with('error', 'Student not found');
        }

        $universityStudent->update(['status' => 'inactive']);
        $university->decrementStudentCount();

        return back()->with('success', 'Student removed successfully');
    }

    public function subscription()
    {
        $user = Auth::user();
        $university = University::where('user_id', $user->id)->first();
        
        if (!$university) {
            return redirect()->route('dashboard')->with('error', 'University account not found');
        }

        return view('university.subscription', compact('university'));
    }

    private function getMonthlyUsage($university)
    {
        // Get monthly translation usage for all university students
        return DB::table('translations')
            ->join('university_students', 'translations.user_id', '=', 'university_students.user_id')
            ->where('university_students.university_id', $university->id)
            ->whereMonth('translations.created_at', now()->month)
            ->count();
    }

    private function getApiCalls($university)
    {
        // Get API calls count if implemented
        return 0;
    }
}
