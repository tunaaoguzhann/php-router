# PHP Router ve Postman Senkronizasyonu

Bu proje, PHP tabanlı bir router sistemi ve Postman API dokümantasyonu otomatik senkronizasyonu sağlar.

## 🚀 Özellikler

- RESTful routing desteği (GET, POST, PUT, DELETE)
- Otomatik Postman koleksiyon senkronizasyonu
- Esnek rota yapılandırması
- Çevre değişkenleri (.env) desteği

## 📋 Gereksinimler

- PHP 8.0 veya üzeri
- Composer
- Postman API anahtarı
- Postman Koleksiyon ID'si

## ⚙️ Kurulum

1. Projeyi klonlayın:
```bash
git clone [proje-url]
```

2. Bağımlılıkları yükleyin:
```bash
composer install
```

3. `.env` dosyasını oluşturun ve gerekli değişkenleri ayarlayın:
```env
BASE_URL=http://localhost:8000
POSTMAN_API_KEY=your_api_key
POSTMAN_COLLECTION_ID=your_collection_id
```

## 🔧 Kullanım

### Router Kullanımı

```php
$router = new Router();

// Basit rota tanımlama
$router->get('/users', function() {
    return ['users' => []];
});

// Açıklamalı rota tanımlama
$router->post('/users', 'UserController@create', [
    'description' => 'Yeni kullanıcı oluşturur'
]);
```

### Postman Senkronizasyonu

Router'a eklenen her yeni rota otomatik olarak Postman koleksiyonunuza senkronize edilir. Senkronizasyon, Router nesnesinin yok edilmesi sırasında otomatik olarak gerçekleşir.

## 🏗️ Proje Yapısı
