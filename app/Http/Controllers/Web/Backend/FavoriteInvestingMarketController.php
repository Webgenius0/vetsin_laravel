<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\FavoriteInvestingMarket;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;

class FavoriteInvestingMarketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = FavoriteInvestingMarket::latest();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('title', 'LIKE', "%$searchTerm%");
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    $page_content       = $data->title;
                    $short_page_content = strlen($page_content) > 60 ? substr($page_content, 0, 60) . '...' : $page_content;
                    return '<p>' . $short_page_content . '</p>';
                })
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= " checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="' . route('favorite-investing-markets.edit', ['id' => $data->id]) . '" class="text-white btn btn-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class="text-white btn btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.favorite_investing_markets.index');
    }

    public function create()
    {
        return view('backend.layouts.favorite_investing_markets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        try {
            FavoriteInvestingMarket::create([
                'title'  => $request->input('title'),
                'status' => 'active',
            ]);

            return to_route('favorite-investing-markets.index')->with('t-success', 'Market Created');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = FavoriteInvestingMarket::findOrFail($id);
        return view('backend.layouts.favorite_investing_markets.edit', compact('data'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        try {
            FavoriteInvestingMarket::where('id', $id)->update([
                'title' => $request->input('title'),
            ]);

            return to_route('favorite-investing-markets.index')->with('t-success', 'Market Updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function status(int $id): JsonResponse
    {
        $data = FavoriteInvestingMarket::findOrFail($id);
        if ($data->status == 'inactive') {
            $data->status = 'active';
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'inactive';
            $data->save();

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $data = FavoriteInvestingMarket::findOrFail($id);
        $data->delete();

        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
}
