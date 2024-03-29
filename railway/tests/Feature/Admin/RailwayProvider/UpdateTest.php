<?php

namespace Tests\Feature;

use App\Events\UpdateRailwayProviderRequestCreated;
use App\Http\Controllers\Helpers\FormToken;
use App\Models\RailwayProviderEventStream;
use App\Models\RailwayProvider;
use App\Models\RailwayProviderDetail;
use App\Models\UpdateRailwayProviderRequest;
use Database\Seeders\Test\Admin\RailwayProvider\FixedSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_html_response_where_edit_admin_railway_providers_path(): void
    {
        $railwayProviderEventStream = RailwayProviderEventStream::factory()
            ->create();
        $railwayProvider = RailwayProvider::factory()
            ->state(function () use ($railwayProviderEventStream) {
                return [
                    'railway_provider_event_stream_id' => $railwayProviderEventStream['id'],
                ];
            })
            ->create();
        $railwayProviderDetail = RailwayProviderDetail::factory()
            ->state(function () use ($railwayProvider) {
                return [
                    'railway_provider_id' => $railwayProvider['id'],
                    'name' => 'provider',
                ];
            })
            ->create();

        $response = $this->get("/admin/railway_providers/{$railwayProvider->id}/edit");
        $response->assertStatus(200);
    }

    public function test_get_validation_error_too_long_railway_provider_name_when_put(): void
    {
        $railwayProviderEventStream = RailwayProviderEventStream::factory()
            ->create();
        $railwayProvider = RailwayProvider::factory()
            ->state(function () use ($railwayProviderEventStream) {
                return [
                    'railway_provider_event_stream_id' => $railwayProviderEventStream['id'],
                ];
            })
            ->create();
        $railwayProviderDetail = RailwayProviderDetail::factory()
            ->state(function () use ($railwayProvider) {
                return [
                    'railway_provider_id' => $railwayProvider['id'],
                    'name' => 'provider',
                ];
            })
            ->create();

        $response = $this->put("/admin/railway_providers/{$railwayProvider['id']}", [
            'token' => FormToken::make(),
            'name' => 'too long railway provider name for test',
        ]);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_get_validation_error_duplicate_token_when_put(): void
    {
        $railwayProviderEventStream = RailwayProviderEventStream::factory()
            ->create();
        $railwayProvider = RailwayProvider::factory()
            ->state(function () use ($railwayProviderEventStream) {
                return [
                    'railway_provider_event_stream_id' => $railwayProviderEventStream['id'],
                ];
            })
            ->create();
        $updateRailwayProviderRequest = UpdateRailwayProviderRequest::factory()
            ->state(function () use ($railwayProviderEventStream, $railwayProvider) {
                return [
                    'railway_provider_event_stream_id' => $railwayProviderEventStream['id'],
                    'railway_provider_id' => $railwayProvider['id'],
                    'token' => FormToken::make(),
                    'name' => 'provider'
                ];
            })
            ->create();

        $response = $this->put("/admin/railway_providers/{$railwayProvider['id']}", [
            'token' => $updateRailwayProviderRequest['token'],
            'name' => 'provider',
        ]);
        $response->assertSessionHasErrors(['token']);
    }

    public function test_create_update_railway_provider_request_when_put(): void
    {
        $railwayProviderEventStream = RailwayProviderEventStream::factory()->create();
        $railwayProvider = RailwayProvider::factory()->state(function () use ($railwayProviderEventStream) {
            return [
                'railway_provider_event_stream_id' => $railwayProviderEventStream['id'],
            ];
        })->create();
        $railwayProviderDetail = RailwayProviderDetail::factory()->state(function () use ($railwayProvider) {
            return [
                'railway_provider_id' => $railwayProvider['id'],
            ];
        })->create();

        Event::fake();
        $response = $this->put("/admin/railway_providers/{$railwayProvider['id']}", [
            'token' => 'target token',
            'name' => 'target name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('update_railway_provider_requests', [
            'token' => 'target token',
            'name' => 'target name',
        ]);
        Event::assertDispatched(UpdateRailwayProviderRequestCreated::class);
    }
}
