<?php

namespace App\Http\Controllers;

use App\Models\SalaryComponent;
use Illuminate\Http\Request;

class SalaryComponentController extends Controller
{
    public function index(Request $request)
    {
        $query = SalaryComponent::query();


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }


        if ($request->filled('type') && $request->type != 'Semua') {
            $query->where('type', $request->type);
        }

        $components = $query->latest()->paginate(10)->withQueryString();


        $totalComponents = SalaryComponent::count();
        $totalAllowances = SalaryComponent::where('type', 'allowance')->count();
        $totalDeductions = SalaryComponent::where('type', 'deduction')->count();
        $totalPercentage = SalaryComponent::where('amount_type', 'percentage')->count();

        return view('salary_components.index', compact(
            'components',
            'totalComponents',
            'totalAllowances',
            'totalDeductions',
            'totalPercentage'
        ));
    }

    public function create()
    {
        return view('salary_components.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:20|unique:salary_components,code',
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:allowance,deduction',
            'amount_type' => 'required|in:fixed,percentage',
            'amount'      => 'required|numeric|min:0',
        ]);

        SalaryComponent::create($request->all());

        return redirect()->route('salary-components.index')->with('success', 'Komponen gaji berhasil ditambahkan!');
    }

    public function edit(SalaryComponent $salaryComponent)
    {
        return view('salary_components.edit', compact('salaryComponent'));
    }

    public function update(Request $request, SalaryComponent $salaryComponent)
    {
        $request->validate([
            'code'        => 'required|string|max:20|unique:salary_components,code,' . $salaryComponent->id,
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:allowance,deduction',
            'amount_type' => 'required|in:fixed,percentage',
            'amount'      => 'required|numeric|min:0',
        ]);

        $salaryComponent->update($request->all());

        return redirect()->route('salary-components.index')->with('success', 'Data komponen gaji berhasil diperbarui!');
    }

    public function destroy(SalaryComponent $salaryComponent)
    {
        $salaryComponent->delete();

        return redirect()->route('salary-components.index')->with('success', 'Komponen gaji berhasil dihapus!');
    }
}
