<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
{
    // Display all salaries (Admin only)
    public function index()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $salaries = Salary::with('employee.user')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(20);
        
        return view('salaries.index', compact('salaries'));
    }

    // Show form to create salary (Admin)
    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $employees = Employee::with('user')->get();
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        $currentYear = date('Y');
        $years = range($currentYear - 5, $currentYear + 1);
        
        return view('salaries.create', compact('employees', 'months', 'years'));
    }

    // Store new salary (Admin)
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if salary already exists for this period
        $existing = Salary::where('employee_id', $request->employee_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Salary already exists for this period.');
        }

        // Calculate total
        $total = $request->basic_salary 
               + ($request->allowances ?? 0)
               + ($request->overtime_pay ?? 0)
               - ($request->deductions ?? 0);

        Salary::create([
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'year' => $request->year,
            'basic_salary' => $request->basic_salary,
            'allowances' => $request->allowances ?? 0,
            'deductions' => $request->deductions ?? 0,
            'overtime_pay' => $request->overtime_pay ?? 0,
            'total' => $total,
            'status' => 'draft',
            'notes' => $request->notes,
        ]);

        return redirect()->route('salaries.index')
            ->with('success', 'Salary record created successfully.');
    }

    // Display specific salary
    public function show(Salary $salary)
    {
        // Only admin or the employee can view
        if (!auth()->user()->hasRole('admin') && 
            $salary->employee->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('salaries.show', compact('salary'));
    }

    // Show edit form (Admin)
    public function edit(Salary $salary)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $employees = Employee::with('user')->get();
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        $years = range(date('Y') - 5, date('Y') + 1);
        
        return view('salaries.edit', compact('salary', 'employees', 'months', 'years'));
    }

    // Update salary (Admin)
    public function update(Request $request, Salary $salary)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Recalculate total
        $total = $request->basic_salary 
               + ($request->allowances ?? 0)
               + ($request->overtime_pay ?? 0)
               - ($request->deductions ?? 0);

        $salary->update([
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'year' => $request->year,
            'basic_salary' => $request->basic_salary,
            'allowances' => $request->allowances ?? 0,
            'deductions' => $request->deductions ?? 0,
            'overtime_pay' => $request->overtime_pay ?? 0,
            'total' => $total,
            'notes' => $request->notes,
        ]);

        return redirect()->route('salaries.index')
            ->with('success', 'Salary record updated successfully.');
    }

    // Delete salary (Admin)
    public function destroy(Salary $salary)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $salary->delete();

        return redirect()->route('salaries.index')
            ->with('success', 'Salary record deleted successfully.');
    }

    // =============== EMPLOYEE SALARY METHODS ===============

    // Show employee's own salary history
    public function mySalary()
    {
        $user = auth()->user();
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'Employee record not found.');
        }

        $salaries = Salary::where('employee_id', $employee->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        return view('salaries.my-salary', compact('salaries'));
    }

    // Generate payslip
    public function payslip(Salary $salary)
    {
        $user = auth()->user();
        
        // Check if user is authorized
        if (!auth()->user()->hasRole('admin') && 
            $salary->employee->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }
        
        return view('salaries.payslip', compact('salary'));
    }

    // =============== SALARY PROCESSING METHODS ===============

    // Process salary (change status to processed)
    public function process(Salary $salary)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $salary->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Salary marked as processed.');
    }

    // Mark salary as paid
    public function pay(Salary $salary)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $salary->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        return back()->with('success', 'Salary marked as paid.');
    }

    // Bulk process salaries for a month
    public function bulkProcess(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . date('Y'),
        ]);

        $count = Salary::where('month', $request->month)
            ->where('year', $request->year)
            ->where('status', 'draft')
            ->update([
                'status' => 'processed',
                'processed_at' => now(),
            ]);

        return back()->with('success', "{$count} salaries processed successfully.");
    }
}