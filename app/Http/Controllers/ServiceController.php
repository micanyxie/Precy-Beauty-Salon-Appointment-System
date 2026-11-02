<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // Use 'with' to eager load the category and avoid "N+1" performance issues
        $query = Service::with('category');
        $services = Service::with('category')->orderByRaw('LOWER(name) ASC')->get();
        $archivedServices = Service::onlyTrashed()->with('category')->get();


        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $services = $query->orderByRaw('LOWER(name) ASC')->get();

        if ($request->ajax()) {
            $html = '';
            foreach ($services as $service) {
                $price = number_format($service->price, 2);
                $categoryName = $service->category->name ?? 'Uncategorized';

                // We must pass category_id to the Edit Modal
                $html .= "
            <tr class='hover:bg-green-50/30 transition group'>
                <td class='py-4 px-8'>
                    <span class='font-bold text-gray-700 uppercase tracking-wide text-sm'>{$service->name}</span>
                </td>
                <td class='py-4 px-8 text-center'>
                    <span class='font-bold text-gray-700 text-sm italic'>Php {$price}</span>
                </td>
                <td class='py-4 px-8 text-center'>
                    <span class='text-gray-500 text-sm font-medium italic'>{$service->duration}</span>
                </td>
                <td class='py-4 px-8 text-center'>
                    <span class='px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold uppercase tracking-tighter'>
                        {$categoryName}
                    </span>
                </td>
                <td class='py-4 px-8 text-right'>
                    <div class='flex justify-end space-x-2'>
                        <button onclick=\"openEditModal({$service->id}, '{$service->name}', {$service->price}, '{$service->duration}', {$service->service_category_id})\" class='p-1.5 text-blue-500 hover:bg-blue-50 rounded-md transition'>
                            <i data-lucide='pencil-line' class='w-4 h-4'></i>
                        </button>
                        <button onclick=\"openDeleteModal({$service->id}, '{$service->name}')\" class='p-1.5 text-red-500 hover:bg-red-50 rounded-md transition'>
                            <i data-lucide='trash-2' class='w-4 h-4'></i>
                        </button>
                    </div>
                </td>
            </tr>";
            }
            return response($html);
        }

        return view('services.index', compact('services', 'archivedServices'));
    }

    public function store(Request $request)
    {
        // It is HIGHLY recommended to validate here to prevent the SQL error you saw
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        Service::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'service_category_id' => $request->service_category_id,
        ]);

        return redirect()->route('services.index')->with('success', 'Service added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'service_category_id' => $request->service_category_id,
        ]);

        return redirect()->route('services.index')->with('success', 'Service updated successfully!');
    }

    public function restore($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();

        return redirect()->route('services.index')->with('success', 'Service restored successfully!');
    }
}
