<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->get();
        
        return Inertia::render('Suppliers/Index', [
            'suppliers' => $suppliers
        ]);
    }

    public function create()
    {
        return Inertia::render('Suppliers/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'active' => 'boolean'
        ]);
        
        Supplier::create($validated);
        
        return redirect()->route('suppliers.index')
            ->with('message', 'Proveedor creado exitosamente');
    }

    public function edit(Supplier $supplier)
    {
        return Inertia::render('Suppliers/Edit', [
            'supplier' => $supplier
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'active' => 'boolean'
        ]);
        
        $supplier->update($validated);
        
        return redirect()->route('suppliers.index')
            ->with('message', 'Proveedor actualizado exitosamente');
    }

    public function destroy(Supplier $supplier)
    {
        // Verificar si el proveedor tiene productos asociados
        if ($supplier->products()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'No se puede eliminar el proveedor porque tiene productos asociados');
        }
        
        $supplier->delete();
        
        return redirect()->route('suppliers.index')
            ->with('message', 'Proveedor eliminado exitosamente');
    }
}