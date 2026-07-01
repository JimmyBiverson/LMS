<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Exam;
use App\Models\LmsClass;
use App\Models\Result;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolManagementController extends Controller
{
    public function classes(): View
    {
        $classes = LmsClass::with('teacher', 'course')->withCount('students')->latest()->get();
        $teachers = User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_ADMIN])
            ->where('status', User::STATUS_ACTIVE)->get();
        $courses = Course::where('status', 'Active')->get();
        return view('admin.school.classes', compact('classes', 'teachers', 'courses'));
    }

    public function storeClass(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:50',
            'section' => 'nullable|string|max:50',
            'teacher_id' => 'nullable|exists:users,id',
            'course_id' => 'nullable|exists:courses,id',
            'room' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:1|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        LmsClass::create($validated);
        return back()->with('success', 'Class created successfully!');
    }

    public function updateClass(Request $request, LmsClass $class): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:50',
            'section' => 'nullable|string|max:50',
            'teacher_id' => 'nullable|exists:users,id',
            'course_id' => 'nullable|exists:courses,id',
            'room' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:1|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        $class->update($validated);
        return back()->with('success', 'Class updated successfully!');
    }

    public function destroyClass(LmsClass $class): RedirectResponse
    {
        $class->delete();
        return back()->with('success', 'Class deleted successfully!');
    }

    public function attendances(): View
    {
        $classes = LmsClass::where('status', 'active')->get();
        $courses = Course::where('status', 'Active')->get();
        $students = User::where('role', User::ROLE_STUDENT)->where('status', 'active')->get();
        $attendances = Attendance::with('student', 'class', 'course')->latest()->paginate(20);
        return view('admin.school.attendances', compact('classes', 'courses', 'students', 'attendances'));
    }

    public function storeAttendance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:users,id',
            'course_id' => 'nullable|exists:courses,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:500',
        ]);

        Attendance::updateOrCreate(
            ['class_id' => $validated['class_id'], 'student_id' => $validated['student_id'], 'date' => $validated['date']],
            $validated
        );

        return back()->with('success', 'Attendance recorded successfully!');
    }

    public function markAttendanceBatch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'statuses' => 'required|array',
            'statuses.*' => 'required|in:present,absent,late,excused',
        ]);

        foreach ($validated['statuses'] as $studentId => $status) {
            Attendance::updateOrCreate(
                ['class_id' => $validated['class_id'], 'student_id' => $studentId, 'date' => $validated['date']],
                ['status' => $status, 'course_id' => $request->course_id]
            );
        }

        return back()->with('success', 'Attendance marked successfully!');
    }

    public function exams(): View
    {
        $classes = LmsClass::where('status', 'active')->get();
        $courses = Course::where('status', 'Active')->get();
        $exams = Exam::with('course', 'class', 'results')->latest()->get();
        return view('admin.school.exams', compact('classes', 'courses', 'exams'));
    }

    public function storeExam(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'class_id' => 'nullable|exists:classes,id',
            'exam_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|numeric|min:0',
            'exam_type' => 'required|in:midterm,final,quiz,assignment',
            'description' => 'nullable|string',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        Exam::create($validated);
        return back()->with('success', 'Exam created successfully!');
    }

    public function updateExam(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'class_id' => 'nullable|exists:classes,id',
            'exam_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|numeric|min:0',
            'exam_type' => 'required|in:midterm,final,quiz,assignment',
            'description' => 'nullable|string',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        $exam->update($validated);
        return back()->with('success', 'Exam updated successfully!');
    }

    public function destroyExam(Exam $exam): RedirectResponse
    {
        $exam->delete();
        return back()->with('success', 'Exam deleted successfully!');
    }

    public function results(): View
    {
        $exams = Exam::with('course', 'class')->latest()->get();
        $courses = Course::where('status', 'Active')->get();
        $students = User::where('role', User::ROLE_STUDENT)->where('status', 'active')->get();
        $results = Result::with('exam', 'student', 'course')->latest()->paginate(20);
        return view('admin.school.results', compact('exams', 'courses', 'students', 'results'));
    }

    public function storeResult(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'marks' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $validated['total_marks'] = $exam->total_marks;
        $percentage = ($validated['marks'] / $exam->total_marks) * 100;

        if ($percentage >= 90) $validated['grade'] = 'A+';
        elseif ($percentage >= 80) $validated['grade'] = 'A';
        elseif ($percentage >= 70) $validated['grade'] = 'B';
        elseif ($percentage >= 60) $validated['grade'] = 'C';
        elseif ($percentage >= 50) $validated['grade'] = 'D';
        else $validated['grade'] = 'F';

        Result::updateOrCreate(
            ['exam_id' => $validated['exam_id'], 'student_id' => $validated['student_id']],
            $validated
        );

        return back()->with('success', 'Result saved successfully!');
    }

    public function updateResult(Request $request, Result $result): RedirectResponse
    {
        $validated = $request->validate([
            'marks' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        $exam = $result->exam;
        $percentage = ($validated['marks'] / $exam->total_marks) * 100;

        if ($percentage >= 90) $validated['grade'] = 'A+';
        elseif ($percentage >= 80) $validated['grade'] = 'A';
        elseif ($percentage >= 70) $validated['grade'] = 'B';
        elseif ($percentage >= 60) $validated['grade'] = 'C';
        elseif ($percentage >= 50) $validated['grade'] = 'D';
        else $validated['grade'] = 'F';

        $result->update($validated);
        return back()->with('success', 'Result updated successfully!');
    }

    public function destroyResult(Result $result): RedirectResponse
    {
        $result->delete();
        return back()->with('success', 'Result deleted successfully!');
    }

    public function timetables(): View
    {
        $classes = LmsClass::where('status', 'active')->get();
        $courses = Course::where('status', 'Active')->get();
        $teachers = User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_ADMIN])
            ->where('status', User::STATUS_ACTIVE)->get();
        $timetables = Timetable::with('class', 'course', 'teacher')->latest()->get();
        return view('admin.school.timetables', compact('classes', 'courses', 'teachers', 'timetables'));
    }

    public function storeTimetable(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:100',
        ]);

        Timetable::create($validated);
        return back()->with('success', 'Timetable entry created!');
    }

    public function destroyTimetable(Timetable $timetable): RedirectResponse
    {
        $timetable->delete();
        return back()->with('success', 'Timetable entry deleted!');
    }

    public function parents(): View
    {
        $parents = User::where('role', User::ROLE_PARENT)->with('children')->latest()->get();
        $students = User::where('role', User::ROLE_STUDENT)->where('status', 'active')->get();
        return view('admin.school.parents', compact('parents', 'students'));
    }

    public function storeParent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        $parent = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => bcrypt($validated['password']),
            'role' => User::ROLE_PARENT,
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        if (!empty($validated['student_ids'])) {
            $parent->children()->sync($validated['student_ids']);
        }

        return back()->with('success', 'Parent account created!');
    }

    public function updateParent(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== User::ROLE_PARENT) {
            return back()->with('error', 'User is not a parent.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        $user->update($validated);

        if ($request->has('student_ids')) {
            $user->children()->sync($validated['student_ids'] ?? []);
        }

        return back()->with('success', 'Parent updated successfully!');
    }

    public function destroyParent(User $user): RedirectResponse
    {
        if ($user->role !== User::ROLE_PARENT) {
            return back()->with('error', 'User is not a parent.');
        }
        $user->children()->detach();
        $user->delete();
        return back()->with('success', 'Parent deleted successfully!');
    }
}
