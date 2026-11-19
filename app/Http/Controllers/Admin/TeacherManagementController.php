namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherManagementController extends Controller
{
    // List all teachers
    public function index()
    {
        $teachers = Teacher::with('user')->latest()->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    // Show form to create a new teacher manually
    public function create()
    {
        return view('admin.teachers.create');
    }

    // Store manually created teacher
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($validated) {
            $teacherRole = Role::where('slug', 'teacher')->firstOrFail();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'], // hashed automatically via model cast
                'role_id' => $teacherRole->id,
                'is_active' => true, // Admin-created accounts are active immediately
                'email_verified_at' => now(),
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'TCH-' . strtoupper(uniqid()),
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'address' => $validated['address'],
                'status' => true,
            ]);
        });

        return redirect()->route('admin.teachers.index')
            ->with('status', 'Teacher added and activated successfully.');
    }

    // Edit teacher details
    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    // Update teacher details
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'status' => ['boolean'],
        ]);

        DB::transaction(function () use ($validated, $teacher) {
            $teacher->user->update([
                'name' => $validated['name'],
                'is_active' => $validated['status'] ?? $teacher->user->is_active,
            ]);

            $teacher->update([
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'address' => $validated['address'],
                'status' => $validated['status'] ?? $teacher->status,
            ]);
        });

        return redirect()->route('admin.teachers.index')
            ->with('status', 'Teacher profile updated successfully.');
    }

    // Delete teacher and user account
    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {
            $user = $teacher->user;
            $teacher->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.teachers.index')
            ->with('status', 'Teacher account deleted successfully.');
    }
}