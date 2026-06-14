<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\ChatbotController;
use Illuminate\Http\Request;

$req = new Request();
$req->merge([
    'message' => 'I have a skin allergy and itchiness',
    'city' => 'Jaipur',
    'locale' => 'en'
]);

$controller = app(ChatbotController::class);
$response = $controller->handleMessage($req);
file_put_contents('scratch_chat_response.json', json_encode(json_decode($response->getContent(), true), JSON_PRETTY_PRINT));
echo "Done!\n";
