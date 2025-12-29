<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\Saving\UserCarSavingService;
use App\Models\UserCar;

echo "Testing automatic car activation on pricing...\n";

// Create test user cars for the same user
$userId = 1;

$userCar1 = UserCar::create([
    'user_id' => $userId,
    'brand' => 'Toyota',
    'model' => 'Camry',
    'year' => 2020,
    'fuel_type' => 'petrol',
    'transmission' => 'automatic',
    'user_expected_price' => 300000,
    'status' => 'priced',
    'is_active' => true, // Currently active
]);

$userCar2 = UserCar::create([
    'user_id' => $userId,
    'brand' => 'Honda',
    'model' => 'Civic',
    'year' => 2019,
    'fuel_type' => 'petrol',
    'transmission' => 'automatic',
    'user_expected_price' => 250000,
    'status' => 'pending', // Waiting for pricing
    'is_active' => false,
]);

$userCar3 = UserCar::create([
    'user_id' => $userId,
    'brand' => 'Ford',
    'model' => 'Focus',
    'year' => 2021,
    'fuel_type' => 'diesel',
    'transmission' => 'manual',
    'user_expected_price' => 280000,
    'status' => 'priced',
    'is_active' => false,
]);

echo "Created 3 test cars for user $userId:\n";
echo "- Car 1 (ID: {$userCar1->id}): Status={$userCar1->status}, Active={$userCar1->is_active}\n";
echo "- Car 2 (ID: {$userCar2->id}): Status={$userCar2->status}, Active={$userCar2->is_active}\n";
echo "- Car 3 (ID: {$userCar3->id}): Status={$userCar3->status}, Active={$userCar3->is_active}\n\n";

// Price car 2 (which is pending)
echo "Pricing Car 2 (pending -> priced)...\n";
$pricingData = [
    'id' => $userCar2->id,
    'fair_price' => 240000,
    'admin_notes' => 'تم تسعير السيارة تلقائياً',
    'status' => 'priced'
];

try {
    $service = app(UserCarSavingService::class);
    $result = $service->saveAndCommit($pricingData);
    echo "✅ Pricing successful!\n\n";

    // Refresh all cars from database
    $car1_refreshed = UserCar::find($userCar1->id);
    $car2_refreshed = UserCar::find($userCar2->id);
    $car3_refreshed = UserCar::find($userCar3->id);

    echo "After pricing Car 2:\n";
    echo "- Car 1 (ID: {$car1_refreshed->id}): Status={$car1_refreshed->status}, Active=" . ($car1_refreshed->is_active ? 'true' : 'false') . "\n";
    echo "- Car 2 (ID: {$car2_refreshed->id}): Status={$car2_refreshed->status}, Active=" . ($car2_refreshed->is_active ? 'true' : 'false') . "\n";
    echo "- Car 3 (ID: {$car3_refreshed->id}): Status={$car3_refreshed->status}, Active=" . ($car3_refreshed->is_active ? 'true' : 'false') . "\n";

    // Verify expectations
    $expectations = [
        'Car 2 should be priced' => $car2_refreshed->status === 'priced',
        'Car 2 should be active' => $car2_refreshed->is_active === true,
        'Car 1 should be deactivated' => $car1_refreshed->is_active === false,
        'Car 3 should remain inactive' => $car3_refreshed->is_active === false,
    ];

    echo "\nValidation Results:\n";
    foreach ($expectations as $test => $result) {
        echo ($result ? "✅" : "❌") . " $test\n";
    }

    $all_passed = array_sum($expectations) === count($expectations);
    echo "\n" . ($all_passed ? "🎉 All tests passed! Auto-activation works correctly!" : "❌ Some tests failed.");

} catch (\Exception $e) {
    echo "❌ Pricing failed: " . $e->getMessage() . "\n";
}


