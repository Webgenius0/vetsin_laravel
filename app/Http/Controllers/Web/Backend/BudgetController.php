<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class BudgetController extends Controller {

    public function index(Request $request): View|JsonResponse {
        if ($request->ajax()) {
            $data = Budget::latest();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('title', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label"></label></div>';
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group">
                              <a href="' . route('budget.edit', ['id' => $data->id]) . '" class="text-white btn btn-primary" title="Edit">
                              <i class="bi bi-pencil"></i></a>
                              <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class="text-white btn btn-danger" title="Delete">
                              <i class="bi bi-trash"></i></a>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make();
        }
        return view('backend.layouts.settings.budget.index');
    }

    public function create(): View|RedirectResponse {
        if (User::find(auth()->user()->id)) {
            return view('backend.layouts.settings.budget.create');
        }
        return redirect()->route('budget.index');
    }

    public function store(Request $request): RedirectResponse {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $data = new Budget();
                $data->title = $request->title;
                $data->save();
            }
            return redirect()->route('budget.index')->with('t-success', 'Budget created successfully.');
        } catch (Exception) {
            return redirect()->route('budget.index')->with('t-error', 'Budget creation failed.');
        }
    }

    public function edit(int $id): View|RedirectResponse {
        if (User::find(auth()->user()->id)) {
            $data = Budget::find($id);
            return view('backend.layouts.settings.budget.edit', compact('data'));
        }
        return redirect()->route('budget.index');
    }

    public function update(Request $request, int $id): RedirectResponse {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'title' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $data = Budget::findOrFail($id);
                $data->update(['title' => $request->title]);

                return redirect()->route('budget.index')->with('t-success', 'Budget updated successfully.');
            }
        } catch (Exception) {
            return redirect()->route('budget.index')->with('t-error', 'Budget update failed.');
        }
        return redirect()->route('budget.index');
    }

    public function status(int $id): JsonResponse {
        $data = Budget::findOrFail($id);
        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();
            return response()->json(['success' => false, 'message' => 'Unpublished Successfully.', 'data' => $data]);
        } else {
            $data->status = 'active';
            $data->save();
            return response()->json(['success' => true, 'message' => 'Published Successfully.', 'data' => $data]);
        }
    }

    public function destroy(int $id): JsonResponse {
        $data = Budget::findOrFail($id);
        $data->delete();
        return response()->json(['t-success' => true, 'message' => 'Deleted successfully.']);
    }
}
