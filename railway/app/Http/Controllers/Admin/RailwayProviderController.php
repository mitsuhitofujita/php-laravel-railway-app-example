<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\FormToken;
use App\Http\Requests\StoreRailwayProviderRequest as StoreRequest;
use App\Http\Requests\EditRailwayProviderRequest as EditRequest;
use App\Http\Requests\UpdateRailwayProviderRequest as UpdateRequest;
use App\Models\RailwayProviderEventStream as EventStream;
use App\Models\StoreRailwayProviderRequest as StoreModel;
use App\Models\UpdateRailwayProviderRequest as UpdateModel;
use App\Models\RailwayProvider;
use App\Models\RailwayProviderDetail;
use Illuminate\Validation\ValidationException;

class RailwayProviderController extends Controller
{
    public function index()
    {
        return view('admin.railway_providers.index');
    }

    public function create()
    {
        return view('admin.railway_providers.create', ['initialParams' => [
            'token' => FormToken::make(),
        ]]);
    }

    public function store(StoreRequest $request)
    {
        if (StoreModel::existsToken($request->input('token'))) {
            throw ValidationException::withMessages([
                'token' => ['このトークン使用済みです。フォームを再読み込みし、入力し直してください。'],
            ]);
        }

        $eventStream = new EventStream();
        $eventStream->save();

        (new StoreModel())->fill(
            array_merge(
                $request->all(),
                ['railway_provider_event_stream_id' => $eventStream['id']],
            )
        )->save();
        return view('admin.railway_providers.index');
    }

    public function edit(EditRequest $request)
    {
        $railwayProvider = RailwayProvider::findOrFail($request->input('id'));
        $railwayProviderDetail = RailwayProviderDetail::where(['railway_provider_id' => $railwayProvider->id])->orderBy('id', 'desc')->first();

        return view('admin.railway_providers.edit', [
            'id' => $railwayProvider['id'],
            'initialParams' => [
                'token' => FormToken::make(),
                'name' => $railwayProviderDetail['name'],
            ]
        ]);
    }

    public function update(UpdateRequest $request)
    {
        if (UpdateModel::existsToken($request->input('token'))) {
            throw ValidationException::withMessages([
                'token' => ['このトークン使用済みです。フォームを再読み込みし、入力し直してください。'],
            ]);
        }

        $railwayProvider = RailwayProvider::findOrFail($request->input('id'));

        (new UpdateModel())->fill(
            array_merge(
                $request->all(),
                [
                    'railway_provider_id' => $request->input('id'),
                    'railway_provider_event_stream_id' => $railwayProvider['railway_provider_event_stream_id'],
                ],
            )
        )->save();
        return view('admin.railway_providers.index');
    }
}
