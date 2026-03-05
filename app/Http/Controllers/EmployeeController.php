<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    // Display all employees
    public function index()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $employees = Employee::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('employees.index', compact('employees'));
    }

    // Show form to create employee
    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $roles = Role::where('name', '!=', 'admin')
            ->where('name', '!=', 'admin gudang')
            ->get();
        
        $departments = [
            'IT',
            'HR',
            'Finance',
            'Marketing',
            'Sales',
            'Operations',
            'Warehouse',
            'Production',
            'Quality Control',
            'Maintenance'
        ];
        
        $positions = [
            'Manager',
            'Supervisor',
            'Staff',
            'Operator',
            'Technician',
            'Analyst',
            'Coordinator',
            'Assistant'
        ];
        
        return view('employees.create', compact('roles', 'departments', 'positions'));
    }

    // Store new employee
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:employees',
            'position' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'hire_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:50',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            
            // Assign role
            $role = Role::find($request->role_id);
            $user->roles()->attach($role);
            
            // Create employee record
            Employee::create([
                'user_id' => $user->id,
                'employee_id' => $request->employee_id,
                'position' => $request->position,
                'department' => $request->department,
                'hire_date' => $request->hire_date,
                'basic_salary' => $request->basic_salary,
                'bank_name' => $request->bank_name,
                'bank_account' => $request->bank_account,
            ]);
            
            DB::commit();
            
            return redirect()->route('employees.index')
                ->with('success', 'Employee created successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create employee: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Display specific employee
    public function show(Employee $employee)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $employee->load('user', 'attendances', 'salaries');
        
        return view('employees.show', compact('employee'));
    }

    // Show edit form
    public function edit(Employee $employee)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $employee->load('user');
        $roles = Role::where('name', '!=', 'admin')
            ->where('name', '!=', 'admin gudang')
            ->get();
        
        $departments = [
            'IT',
            'HR',
            'Finance',
            'Marketing',
            'Sales',
            'Operations',
            'Warehouse',
            'Production',
            'Quality Control',
            'Maintenance'
        ];
        
        $positions = [
            'Manager',
            'Supervisor',
            'Staff',
            'Operator',
            'Technician',
            'Analyst',
            'Coordinator',
            'Assistant'
        ];
        
        return view('employees.edit', compact('employee', 'roles', 'departments', 'positions'));
    }

    // Update employee
    public function update(Request $request, Employee $employee)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->user_id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:employees,employee_id,' . $employee->id,
            'position' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'hire_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:50',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update user account
            $user = $employee->user;
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            
            // Update role if changed
            $currentRole = $user->roles->first();
            if ($currentRole && $currentRole->id != $request->role_id) {
                $user->roles()->detach();
                $newRole = Role::find($request->role_id);
                $user->roles()->attach($newRole);
            }
            
            // Update employee record
            $employee->update([
                'employee_id' => $request->employee_id,
                'position' => $request->position,
                'department' => $request->department,
                'hire_date' => $request->hire_date,
                'basic_salary' => $request->basic_salary,
                'bank_name' => $request->bank_name,
                'bank_account' => $request->bank_account,
            ]);
            
            DB::commit();
            
            return redirect()->route('employees.index')
                ->with('success', 'Employee updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update employee: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Delete employee
    public function destroy(Employee $employee)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        DB::beginTransaction();
        
        try {
            // Delete employee record
            $employee->delete();
            
            // Delete user account
            $employee->user->delete();
            
            DB::commit();
            
            return redirect()->route('employees.index')
                ->with('success', 'Employee deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete employee: ' . $e->getMessage());
        }
    }

    // =============== ADDITIONAL METHODS ===============

    // Show employee attendance history
    public function attendance(Employee $employee)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $attendances = $employee->attendances()
            ->orderBy('date', 'desc')
            ->paginate(20);
        
        return view('employees.attendance', compact('employee', 'attendances'));
    }

    // Show employee salary history
    public function salaryHistory(Employee $employee)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $salaries = $employee->salaries()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);
        
        return view('employees.salary-history', compact('employee', 'salaries'));
    }

    // Deactivate employee
    public function deactivate(Employee $employee)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        // Add deactivation logic here
        // For example, add a status column to employees table
        
        return back()->with('success', 'Employee deactivated successfully.');
    }

    // Activate employee
    public function activate(Employee $employee)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        // Add activation logic here
        
        return back()->with('success', 'Employee activated successfully.');
    }
}