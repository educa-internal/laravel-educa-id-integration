Tutor ID

Cài đặt
-

- Thêm vào composer.json của project muốn cài

```php
"repositories": [
    {
        "type": "git",
        "url": "ssh://git@gitlab.edupiakid.vn:6969/educa-tutor/laravel-educa-id-integration.git"
    }
]
```

- Sau đó chạy : 
```php
composer require laravel/socialite (vì mỗi service một phiên bản laravel khác nhau nên không biết để mặc định cài socialite bản nào)
composer require tutor/id
```

Sử dụng
-
- Set env
```php
TUTOR_ID_CLIENT_ID=
TUTOR_ID_CLIENT_SECRET=
TUTOR_ID_REDIRECT=

TUTOR_ID_URL_AUTHORIZE=
TUTOR_ID_URL_GET_TOKEN=
TUTOR_ID_URL_GET_USER_BY_TOKEN=
```
- Tạo một service extend abstract class này: 
```php
abstract class AbstractUserService (Tutor\Id\Services\AbstractUserService)
abstract protected function getUserFromTutorUser(User $tutorUser) (lấy thông tin user của hệ thống từ thông tin user của hệ thống tutor id)
abstract protected function createUserFromTutorUser(User $tutorUser) (tạo user của hệ thống từ thông tin user của hệ thống tutor id)
abstract protected function validateTutorRole($tutorRole) (kiểm tra xem tutor role trả về có hợp lệ với role của hệ thống không)
abstract protected function compareAndModifyDeviationsUser(User $tutorUser) (so sánh dữ liệu của user của hệ thống với thông tin của tutor user , nếu sai lệch thì update lại)

2 hàm này có thể override
public function redirectWhenLoginSuccess()
{
    return redirect('/');
}

public function redirectWhenLoginFail()
{
    return redirect('/');
}

```

- Binding service vừa tạo ở trên với interface :
```php
interface UserServiceInterface (Tutor\Id\Services\Contracts\UserServiceInterface)
```
