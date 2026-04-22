<?php

namespace Tests\Feature\Vibe\Stage06;

use App\Models\AISuggestion;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Services\AIAnalyticsService;
use App\Services\AISuggestionService;
use App\Services\AnalyticsService;
use App\Services\DataCollectorService;
use Illuminate\Support\Facades\DB as Database;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesStage06Schema;
use Tests\TestCase;

class TrackingAnalyticsAiTest extends TestCase
{
    use CreatesStage06Schema;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        Database::purge('sqlite');
        Database::setDefaultConnection('sqlite');
        Database::reconnect('sqlite');

        $pdo = Database::connection('sqlite')->getPdo();
        $pdo->sqliteCreateFunction('DAYNAME', function ($date) {
            if (empty($date)) {
                return null;
            }

            return date('l', strtotime((string) $date));
        });
        $pdo->sqliteCreateFunction('HOUR', function ($date) {
            if (empty($date)) {
                return null;
            }

            return (int) date('H', strtotime((string) $date));
        });

        $this->createStage06Schema();
    }

    public function test_product_detail_route_has_track_view_middleware(): void
    {
        $route = app('router')->getRoutes()->getByName('web.detail');

        $this->assertNotNull($route);
        $this->assertContains('track.product.view', $route->gatherMiddleware());
    }

    public function test_activity_tracker_logs_view_search_add_remove_and_purchase(): void
    {
        $user = $this->createStage05User();
        $product = $this->createStage06Product();

        $this->actingAs($user, 'web');

        \App\Services\ActivityTracker::trackView($product->id);
        \App\Services\ActivityTracker::trackSearch('gao huu co');
        \App\Services\ActivityTracker::trackAddToCart($product->id, 2);
        \App\Services\ActivityTracker::trackRemoveFromCart($product->id, 1);
        \App\Services\ActivityTracker::trackPurchase($product->id, 1);

        $this->assertDatabaseHas('activity_logs', [
            'activity_type' => 'view',
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'activity_type' => 'search',
            'user_id' => $user->id,
            'search_query' => 'gao huu co',
        ]);

        $this->assertSame(5, ActivityLog::query()->count());
    }

    public function test_analytics_service_calculates_conversion_abandonment_trending_and_combos(): void
    {
        $productA = $this->createStage06Product(['name' => 'Tao', 'price' => 30000]);
        $productB = $this->createStage06Product(['name' => 'Le', 'price' => 25000]);

        ActivityLog::query()->insert([
            [
                'activity_type' => 'view',
                'product_id' => $productA->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'activity_type' => 'view',
                'product_id' => $productA->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'activity_type' => 'view',
                'product_id' => $productA->id,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'activity_type' => 'view',
                'product_id' => $productA->id,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'activity_type' => 'purchase',
                'product_id' => $productA->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'activity_type' => 'add_to_cart',
                'product_id' => $productA->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'activity_type' => 'add_to_cart',
                'product_id' => $productA->id,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'activity_type' => 'view',
                'product_id' => $productA->id,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'activity_type' => 'view',
                'product_id' => $productA->id,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'activity_type' => 'view',
                'product_id' => $productB->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);

        Order::query()->insert([
            [
                'id' => 9001,
                'user_id' => null,
                'payment_type' => 'COD',
                'status' => 'SUCCESS',
                'payment_status' => 'PAID',
                'shipping_fee' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9002,
                'user_id' => null,
                'payment_type' => 'COD',
                'status' => 'SUCCESS',
                'payment_status' => 'PAID',
                'shipping_fee' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('order_products')->insert([
            ['order_id' => 9001, 'product_id' => $productA->id, 'quantity' => 1, 'price' => $productA->price],
            ['order_id' => 9001, 'product_id' => $productB->id, 'quantity' => 1, 'price' => $productB->price],
            ['order_id' => 9002, 'product_id' => $productA->id, 'quantity' => 1, 'price' => $productA->price],
            ['order_id' => 9002, 'product_id' => $productB->id, 'quantity' => 2, 'price' => $productB->price],
        ]);

        $service = new AnalyticsService();

        $conversionRate = $service->getConversionRate($productA->id, 30);
        $abandonmentRate = $service->getCartAbandonmentRate($productA->id, 30);
        $trending = $service->getTrendingProducts(30);
        $associations = $service->getFrequentlyBoughtTogether(2);

        $this->assertSame(16.67, $conversionRate);
        $this->assertSame(50.0, $abandonmentRate);
        $this->assertNotEmpty($trending);
        $this->assertSame($productA->id, $trending[0]['product_id']);
        $this->assertNotEmpty($associations);
        $this->assertSame(2, $associations[0]['count']);
    }

    public function test_data_collector_collects_metrics_and_formats_prompt(): void
    {
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Rau cu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $product = $this->createStage06Product([
            'name' => 'Ca rot',
            'category_id' => $categoryId,
            'status' => 1,
        ]);

        ActivityLog::query()->insert([
            [
                'activity_type' => 'view',
                'product_id' => $product->id,
                'search_query' => null,
                'quantity' => null,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'activity_type' => 'add_to_cart',
                'product_id' => $product->id,
                'search_query' => null,
                'quantity' => 1,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'activity_type' => 'purchase',
                'product_id' => $product->id,
                'search_query' => null,
                'quantity' => 1,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'activity_type' => 'search',
                'product_id' => null,
                'search_query' => 'ca rot',
                'quantity' => null,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);

        Order::query()->insert([
            'id' => 9101,
            'payment_type' => 'COD',
            'status' => 'SUCCESS',
            'payment_status' => 'PAID',
            'shipping_fee' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('order_products')->insert([
            'order_id' => 9101,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $collector = new DataCollectorService();
        $data = $collector->collectForAI();
        $prompt = $collector->formatForPrompt();

        $this->assertArrayHasKey('overall_stats', $data);
        $this->assertArrayHasKey('products', $data);
        $this->assertArrayHasKey('time_patterns', $data);
        $this->assertStringContainsString('=== TỔNG QUAN HỆ THỐNG ===', $prompt);
        $this->assertStringContainsString('=== PHÂN TÍCH THỜI GIAN', $prompt);
    }

    public function test_ai_analytics_service_parses_and_validates_json_suggestions(): void
    {
        $service = new AIAnalyticsService();

        $openAiStub = new class {
            public function generateContent($prompt, $useCache = true)
            {
                return '[{"type":"pricing","product_id":11,"title":"Giam gia","description":"Mo ta","action":"Giam 10%","priority":3,"reasoning":"Ly do"},{"type":"invalid","product_id":11,"title":"Sai","description":"Sai","action":"Sai","priority":1}]';
            }
        };

        $collectorStub = new class {
            public function formatForPrompt()
            {
                return 'stub data';
            }
        };

        $this->setProtectedProperty($service, 'openai', $openAiStub);
        $this->setProtectedProperty($service, 'dataCollector', $collectorStub);

        $result = $service->analyzeAndGenerateSuggestions();

        $this->assertCount(1, $result);
        $this->assertSame('pricing', $result[0]['type']);
        $this->assertSame(3, $result[0]['priority']);
    }

    public function test_ai_suggestion_service_generates_and_dismisses_suggestions(): void
    {
        $product = $this->createStage06Product(['name' => 'Bi do']);

        $service = new AISuggestionService();

        $aiStub = new class ($product) {
            private $product;

            public function __construct($product)
            {
                $this->product = $product;
            }

            public function analyzeAndGenerateSuggestions()
            {
                return [[
                    'type' => 'inventory',
                    'product_id' => $this->product->id,
                    'title' => 'Ton kho cao',
                    'description' => 'Can day hang',
                    'action' => 'Khuyen mai 15%',
                    'priority' => 2,
                    'reasoning' => 'Luot mua thap',
                ]];
            }
        };

        $this->setProtectedProperty($service, 'aiAnalytics', $aiStub);

        $service->generateAllSuggestions();

        $suggestions = $service->getActiveSuggestions();

        $this->assertCount(1, $suggestions);
        $this->assertSame('inventory', $suggestions->first()->suggestion_type);

        $service->dismissSuggestion($suggestions->first()->id);

        $this->assertDatabaseHas('ai_suggestions', [
            'id' => $suggestions->first()->id,
            'is_dismissed' => 1,
        ]);
    }

    public function test_admin_ai_dashboard_endpoints_work_with_admin_auth(): void
    {
        $admin = $this->createStage06Admin();
        $product = $this->createStage06Product(['name' => 'Ca chua']);

        AISuggestion::query()->create([
            'suggestion_type' => 'trending',
            'product_id' => $product->id,
            'title' => 'San pham dang hot',
            'description' => 'Tang stock',
            'action_recommendation' => 'Nhap them hang',
            'priority' => 3,
            'is_active' => true,
            'is_dismissed' => false,
        ]);

        ActivityLog::query()->insert([
            [
                'activity_type' => 'view',
                'product_id' => $product->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'activity_type' => 'purchase',
                'product_id' => $product->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'activity_type' => 'add_to_cart',
                'product_id' => $product->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.ai.dashboard'))
            ->assertOk();

        $this->actingAs($admin, 'admin')
            ->getJson(route('admin.ai.analytics', ['product_id' => $product->id]))
            ->assertOk()
            ->assertJsonStructure(['conversion_rate', 'abandonment_rate', 'pricing_analysis']);

        $suggestionId = AISuggestion::query()->firstOrFail()->id;

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.ai.dismiss'), ['suggestion_id' => $suggestionId])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('ai_suggestions', [
            'id' => $suggestionId,
            'is_dismissed' => 1,
        ]);
    }

    public function test_stage06_dish_endpoints_and_ai_command_work(): void
    {
        DB::table('cong_thuc')->insert([
            'tenmonan' => 'Canh chua ca loc',
            'congthuc' => 'Nguyen lieu: ca loc, dua, ca chua',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->getJson(route('web.search.dish', ['query' => 'Canh chua']))
            ->assertOk()
            ->assertJsonFragment(['tenmonan' => 'Canh chua ca loc']);

        $this->getJson(route('web.get.recipe', ['tenmonan' => 'Canh chua ca loc']))
            ->assertOk()
            ->assertJsonPath('congthuc', 'Nguyen lieu: ca loc, dua, ca chua');

        $this->artisan('ai:generate-suggestions')->assertExitCode(0);
    }

    private function setProtectedProperty(object $target, string $property, $value): void
    {
        $reflection = new \ReflectionClass($target);
        $propertyRef = $reflection->getProperty($property);
        $propertyRef->setAccessible(true);
        $propertyRef->setValue($target, $value);
    }
}