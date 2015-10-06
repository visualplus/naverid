# 네아로 (네이버 아이디 로그인)

## 설치방법

```
composer require visualplus/naverid
```

패키지 설치 후 서비스 프로바이더를 config/app.php에 등록해주세요.

```
\Visualplus\Naverid\ServiceProvider::class
```

이 패키지는 laravel/socialite를 사용합니다.

그러므로 socialite 설정을 하지 않았다면 추가로 아래 설정도 해주세요.

config/app.php에 서비스 프로바이더 등록
```
Laravel\Socialite\SocialiteServiceProvider::class,
```

config/app.php에 aliases 등록
```
'Socialite' => Laravel\Socialite\Facades\Socialite::class,
```

## 사용방법

laravel/socialite와 사용방법이 동일합니다.

config/services.php 에 설정 등록

```
'naverid' => [
	'client_id'		=> '',
	'client_secret' => '',
	'redirect'		=> '',
]
```

네이버 로그인
```
Route::get('auth/naverid', function() {
	return Socialite::driver("naverid")->redirect();
});
```

로그인 후
```
Route::get('auth/naverid/redirect', function() {
	dd(Socialite::driver("naverid")->user());
});
```