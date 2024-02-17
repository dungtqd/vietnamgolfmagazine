<?php

namespace App\Http\Controllers;

use App\Models\AlertModel;
use App\Models\ZoneModel;
use App\Traits\ResponseFormattingTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $alerts = AlertModel::all();
        $total = $alerts->count();
        $response = $this->_formatBaseResponseWithTotal(200, $alerts, $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $alert = AlertModel::create($request->all());
        $response = $this->_formatBaseResponse(201, $alert, 'Tạo mới cảnh báo thành công');
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alert = AlertModel::find($id);
        $response = $this->_formatBaseResponse(200, $alert, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $alert = AlertModel::findOrFail($id);
        $alert->update($request->all());
        $response = $this->_formatBaseResponse(200, $alert, 'Cập nhật dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $alert = AlertModel::findOrFail($id);
            $alert->delete();
        } catch (Exception $e) {
            $response = $this->_formatBaseResponse(400, $e, "Xoá dữ liệu thất bại");
            return response()->json($response);
        }
        $response = $this->_formatBaseResponse(204, $alert, 'Xoá dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getByUser($id)
    {
        $alert = DB::table('alerts as p')
            ->select('p.id',
                'p.name AS alertName',
                'p.description AS alertDescription',
                'p.zone_id AS zone_id',
                'zo.name AS zoneName',
                'zo.lat AS lat',
                'zo.long AS `long`',
                'zo.status AS zoneStatus',
                'zo.alert AS zoneAlert',
                'us.name AS `userName`',
                'us2.name AS `friendName`',
                'p.happened_at')
            ->join('users as us', 'us.id', '=', 'p.user_id')
            ->join('zones as zo', 'zo.id', '=', 'p.zone_id')
            ->join('users as us2', 'us2.id', '=', 'p.friend_id')
            ->where('p.user_id', '=', $id)
//            ->whereIn('ca.name', array_column($filters, 'value'))
            ->orderBy('p.happened_at', 'DESC')
//            ->take($perPage)
            ->get();

        $total = $alert->count();
        $response = $this->_formatBaseResponseWithTotal(200, $alert, $total, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }
}
