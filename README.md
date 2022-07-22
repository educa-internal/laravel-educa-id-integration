Tutor ID

Cài đặt
-

- Thêm vào composer.json của project muốn cài

```php
"repositories": [
    {
        "type": "git",
        "url": "https://gitlab.edupiakid.vn/educa-tutor/laravel-educa-id-integration.git"
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
TUTOR_ID_REDIRECT=

TUTOR_ID_URL_AUTHORIZE=
TUTOR_ID_URL_GET_TOKEN=
TUTOR_ID_URL_GET_USER_BY_TOKEN=
TUTOR_ID_URL_LOGOUT=
```
- Tạo một service extend abstract class này: 
```php
abstract class AbstractUserService (Tutor\Id\Services\AbstractUserService)

#kiểm tra tutor role xem có hợp lệ với role của hệ thống không 
#nếu hợp lệ , trả về array chứa các role hệ thống (sau khi transform từ tutor role) và sẽ gắn role hệ thống vào properties $systemRoles của user , có thể dùng hàm getSystemRoles() để lấy giá trị
abstract protected function validateTutorRoleAndGetSystemRole(array $tutorRoles): ?array; 

#lấy ra thông tin user hệ thống từ thông tin tutor user
abstract protected function getSystemUserFromTutorUser(TutorUser $tutorUser): ?Model;

#tạo user hệ thống từ thông tin tutor user
abstract protected function createSystemUserFromTutorUser(TutorUser $tutorUser): ?Model;

#so sánh thông tin của user hệ thống với tutor user (name , email , role ... )
abstract protected function compareSystemUserWithTutorUser(Model $systemUser, $tutorUser): ?bool;

#nếu có sai lệch thông tin thì update lại thông tin của user hệ thống cho đúng với tutor user
abstract protected function updateSystemUserFromTutorUser(Model $systemUserr, $tutorUser): ?Model;

#các hàm này có thể override
public function redirectWhenLoginSuccess()
{
    return redirect('/');
}

#message là tin nhắn thông báo vì sao login fail
public function redirectWhenLoginFail($message)
{
    return redirect('/');
}

public function redirectWhenLogout()
{
    return redirect('/');
}
```

- Binding service vừa tạo ở trên với interface :
```php
interface UserServiceInterface (Tutor\Id\Services\Contracts\UserServiceInterface)
```

- Nếu trong quá trình xử lý logic đăng nhập có fail ở đâu , muốn quay trở lại trang login và thông báo fail thì sử dụng throw exception TutorIdException , exception này đã hander sẽ redirect về function redirectWhenLoginFail và truyền message của exception 
```php
public function redirectWhenLoginFail($message)
```
```php
throw new TutorIdException('message');
```
