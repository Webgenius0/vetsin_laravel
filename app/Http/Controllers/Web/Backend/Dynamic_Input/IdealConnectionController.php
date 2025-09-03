<?php

namespace App\Http\Controllers\Web\Backend\Dynamic_Input;

use App\Http\Controllers\Controller;
use App\Models\IdealConnection;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class IdealConnectionController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = IdealConnection::latest();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('title', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('info', function ($data) {
                    return $data->info ? \Illuminate\Support\Str::limit($data->info, 50) : '';
                })
                ->addColumn('status', function ($data) {
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= " checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                              <a href="' . route('admin.ideal_connection.edit', ['id' => $data->id]) . '" type="button" class="text-white btn btn-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                              </a>
                              <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="text-white btn btn-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                              </a>
                            </div>';
                })
                ->rawColumns(['info', 'status', 'action'])
                ->make();
        }
        return view('backend.layouts.dynamic_input.ideal_connection.index');
    }

    public function create(): View|RedirectResponse
    {
        if (User::find(auth()->user()->id)) {
            return view('backend.layouts.dynamic_input.ideal_connection.create');
        }
        return redirect()->route('admin.ideal_connection.index');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|string',
                    'info' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                IdealConnection::create([
                    'title' => $request->title,
                    'info' => $request->info,
                    'status' => 'active',
                ]);
            }
            return redirect()->route('admin.ideal_connection.index')->with('t-success', 'Ideal Connection created successfully.');
        } catch (Exception) {
            return redirect()->route('admin.ideal_connection.index')->with('t-error', 'Ideal Connection failed created.');
        }
    }

    public function edit(int $id): View|RedirectResponse
    {
        if (User::find(auth()->user()->id)) {
            $data = IdealConnection::find($id);
            return view('backend.layouts.dynamic_input.ideal_connection.edit', compact('data'));
        }
        return redirect()->route('admin.ideal_connection.index');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'title' => 'nullable|string',
                    'info' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $data = IdealConnection::findOrFail($id);
                $data->update([
                    'title' => $request->title,
                    'info' => $request->info,
                ]);

                return redirect()->route('admin.ideal_connection.index')->with('t-success', 'Ideal Connection Updated Successfully.');
            }
        } catch (Exception) {
            return redirect()->route('admin.ideal_connection.index')->with('t-error', 'Ideal Connection failed to update');
        }
        return redirect()->route('admin.ideal_connection.index');
    }

    public function status(int $id): JsonResponse
    {
        $data = IdealConnection::findOrFail($id);
        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $page = IdealConnection::findOrFail($id);
        $page->delete();
        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
}
