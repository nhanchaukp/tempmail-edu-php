# Tempmail Edu PHP SDK (nhanchaukp/tempmail-edu-php)

A lightweight PHP SDK for TempMail.Id.Vn API to make integration simple and composer-installable.

Yêu cầu:
- PHP 8.0+
- Composer

Cài đặt:
```bash
composer require nhanchaukp/tempmail-edu-php
```

Nếu bạn muốn dùng .env để cung cấp access token (không cần gọi login):
1. Cài phpdotenv (nếu chưa có):
```bash
composer require vlucas/phpdotenv
```

2. Tạo file `.env` trong project của bạn (ví dụ copy .env.example):
```
TEMPMail_ACCESS_TOKEN=your_access_token_here
```

3. Trong mã ứng dụng, trước khi tạo client, load .env:
```php
<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
```

4. Sử dụng client bình thường — nếu biến môi trường `TEMPMail_ACCESS_TOKEN` được đặt, SDK sẽ tự động đính kèm header Authorization: Bearer <token> vào các request, bạn không cần gọi login:
```php
use Nhanchaukp\TempmailEdu\Config;
use Nhanchaukp\TempmailEdu\Http\GuzzleClient;
use Nhanchaukp\TempmailEdu\TempmailEdu;

$config = new Config(baseUri: 'https://tempmail.id.vn/api', timeout: 10);
$http = new GuzzleClient($config);
$client = new TempmailEdu($http);

// Now you can call protected endpoints without login(), provided the token in .env is valid
$user = $client->getUser();
print_r($user);
```

Ghi chú:
- Token từ `.env` sẽ được dùng tự động nếu có.
- Bạn vẫn có thể gọi `$client->login($email, $password)` để lấy token và SDK sẽ tự động lưu token cho các request tiếp theo.
- Bạn có thể đặt token thủ công bằng `$client->setAccessToken('...')`.

Ví dụ đầy đủ: xem `examples/example.php`.
