<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\AgePreference;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class AgePreferenceController extends Controller {
    public function index(Request $request): View|JsonResponse {
        if ($request->ajax()) {
            $data = AgePreference::latest();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('title', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                              <a href="' . route('age_preference.edit', ['id' => $data->id]) . '" type="button" class="text-white btn btn-primary" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a>
                              <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="text-white btn btn-danger" title="Delete">
                              <i class="bi bi-trash"></i>
                            </a>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make();
        }
        return view('backend.layouts.settings.age_preference.index');
    }

    public function create(): View|RedirectResponse {
        if (User::find(auth()->user()->id)) {
            return view('backend.layouts.settings.age_preference.create');
        }
        return redirect()->route('age_preference.index');
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

                AgePreference::create([
                    'title' => $request->title,
                ]);
            }
            return redirect()->route('age_preference.index')->with('t-success', 'Age Preference created successfully.');
        } catch (Exception) {
            return redirect()->route('age_preference.index')->with('t-error', 'Age Preference failed created.');
        }
    }

    public function edit(int $id): View|RedirectResponse {
        if (User::find(auth()->user()->id)) {
            $data = AgePreference::find($id);
            return view('backend.layouts.settings.age_preference.edit', compact('data'));
        }
        return redirect()->route('age_preference.index');
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

                $data = AgePreference::findOrFail($id);
                $data->update([
                    'title' => $request->title,
                ]);

                return redirect()->route('age_preference.index')->with('t-success', 'Age Preference Updated Successfully.');
            }
        } catch (Exception) {
            return redirect()->route('age_preference.index')->with('t-error', 'Age Preference failed to update');
        }
        return redirect()->route('age_preference.index');
    }

    public function status(int $id): JsonResponse {
        $data = AgePreference::findOrFail($id);
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

    public function destroy(int $id): JsonResponse {
        $page = AgePreference::findOrFail($id);
        $page->delete();
        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
}
