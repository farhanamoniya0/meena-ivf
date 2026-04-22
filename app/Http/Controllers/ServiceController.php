<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('category')->orderBy('name')->paginate(30);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_code' => 'nullable|string|max:20|unique:services,service_code',
            'name'         => 'required|string|max:200',
            'category'     => 'nullable|string|max:100',
            'charge'       => 'required|numeric|min:0',
            'description'  => 'nullable|string',
            'status'       => 'required|in:active,inactive',
        ]);
        if (empty($data['service_code'])) {
            $last = Service::orderBy('id','desc')->first();
            $next = $last ? ((int) preg_replace('/\D/', '', $last->service_code ?? '0')) + 1 : 1;
            $data['service_code'] = 'SVC-' . str_pad($next, 3, '0', STR_PAD_LEFT);
        }
        Service::create($data);
        return redirect()->route('services.index')->with('success', 'Service added successfully.');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'service_code' => 'nullable|string|max:20|unique:services,service_code,'.$service->id,
            'name'         => 'required|string|max:200',
            'category'     => 'nullable|string|max:100',
            'charge'       => 'required|numeric|min:0',
            'description'  => 'nullable|string',
            'status'       => 'required|in:active,inactive',
        ]);
        $service->update($data);
        return redirect()->route('services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->update(['status' => 'inactive']);
        return back()->with('success', 'Service deactivated.');
    }

    public function list()
    {
        $services = Service::active()->orderBy('category')->orderBy('name')->get(['id','service_code','name','category','charge']);
        return response()->json($services);
    }
}
