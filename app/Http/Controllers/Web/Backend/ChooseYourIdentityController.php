<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChooseYourIdentity;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ChooseYourIdentityController extends Controller {

    public function index(Request $request): View|JsonResponse {
        if ($request->ajax()) {
            $data = ChooseYourIdentity::latest();
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
                              <a href="' . route('choose_your_identity.edit', ['id' => $data->id]) . '" class="text-white btn btn-primary" title="Edit">
                              <i class="bi bi-pencil"></i></a>
                              <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class="text-white btn btn-danger" title="Delete">
                              <i class="bi bi-trash"></i></a>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make();
        }
        return view('backend.layouts.settings.choose_your_identity.index');
    }

    public function create(): View|RedirectResponse {
        if (User::find(auth()->user()->id)) {
            return view('backend.layouts.settings.choose_your_identity.create');
        }
        return redirect()->route('choose_your_identity.index');
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

                $data = new ChooseYourIdentity();
                $data->title = $request->title;
                $data->save();
            }
            return redirect()->route('choose_your_identity.index')->with('t-success', 'Choose Your Identity created successfully.');
        } catch (Exception) {
            return redirect()->route('choose_your_identity.index')->with('t-error', 'Choose Your Identity creation failed.');
        }
    }

    public function edit(int $id): View|RedirectResponse {
        if (User::find(auth()->user()->id)) {
            $data = ChooseYourIdentity::find($id);
            return view('backend.layouts.settings.choose_your_identity.edit', compact('data'));
        }
        return redirect()->route('choose_your_identity.index');
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

                $data = ChooseYourIdentity::findOrFail($id);
                $data->update([
                    'title' => $request->title,
                ]);

                return redirect()->route('choose_your_identity.index')->with('t-success', 'Choose Your Identity updated successfully.');
            }
        } catch (Exception) {
            return redirect()->route('choose_your_identity.index')->with('t-error', 'Choose Your Identity update failed.');
        }
        return redirect()->route('choose_your_identity.index');
    }

    public function status(int $id): JsonResponse {
        $data = ChooseYourIdentity::findOrFail($id);
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
        $data = ChooseYourIdentity::findOrFail($id);
        $data->delete();
        return response()->json(['t-success' => true, 'message' => 'Deleted successfully.']);
    }
}
