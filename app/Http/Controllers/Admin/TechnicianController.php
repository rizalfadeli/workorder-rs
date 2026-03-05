<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function index()
    {
        $technicians = Technician::orderBy('name')->paginate(10);
        return view('admin.technicians.index', compact('technicians'));
    }

    public function create()
    {
        return view('admin.technicians.form', ['technician' => new Technician()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'specialty' => ['nullable', 'string', 'max:255'],
        ]);

        Technician::create($request->only('name', 'phone', 'specialty') + ['is_active' => true]);

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Teknisi berhasil ditambahkan.');
    }

    public function edit(Technician $technician)
    {
        return view('admin.technicians.form', compact('technician'));
    }

    public function update(Request $request, Technician $technician)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $technician->update($request->only('name', 'phone', 'specialty', 'is_active'));

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Data teknisi berhasil diperbarui.');
    }

    public function destroy(Technician $technician)
    {
        $technician->delete();
        return redirect()->route('admin.technicians.index')
            ->with('success', 'Teknisi berhasil dihapus.');
    }
}