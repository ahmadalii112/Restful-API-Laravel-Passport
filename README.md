<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Requirements
- PHP 7 or latest version
- Composer
- Laravel Understanding
- Any of them for Coding _VS Code, PhpStorm, Atom, Sublime, Brackets_ etc.
- For API Testing Postman (Recommended) or Insomnias

## Restful API Laravel 8.x

### Step 1 : Creating Database & Reading Records

There is a file in the database/migrations `2018_09_26_163300_student_register.php` 

In App/Providers   `AppServiceProvider.php`  paste the given below code
```php
 public function boot()
    {
        Schema::defaultStringLength(199);
    }
```
To migrate Tables into the Database
```bash
php artisan migrate
```

- Make Controller
  ```bash
  php artisan make:controller County/CountryController
  ```  
- Make Model
  ```bash
  php artisan make:model Country/CountryModel
  ```
- In Routes `api.php` make a route
```php
Route::get('country',[CountryController::class,'country']);
```

In App/Models/Country `CountryModel.php`
```php
class CountryModel extends Model
{
    use HasFactory;
    protected $table = "_z_country";    // country table name
    public $timestamps = false;         // to avoid time
    protected $guarded = [];            // use $fillable or $guarded your choice
}

```
In App/http/Controller/Country `CountryController.php`
```php
public function country()
{
    $country = CountryModel::all();
    return response()->json($country,'200');
}
```

`http://127.0.0.1:8000/api/country` to get Response

## Step 2 : Add a Record - RESTful API

<a href="https://restfulapi.net/http-status-codes/"> HTTP Status Codes List</a>

Routes `api.php`
```php
Route::get('country/{country}',[CountryController::class,'showCountryByID'])->name('country.show');// Show Record by ID
// To add a record
Route::post('country',[CountryController::class,'storeCountryRecord'])->name('country.store');

```
In App/http/Controller/Country `CountryController.php`
```php
// Todo: Show By ID
public function showCountryByID(CountryModel $country)
{
    return response()->json($country,'200'); // 200 is status
}

// Todo: Store Data

public function storeCountryRecord(Request $request)
{
    $country = CountryModel::create($request->all());
    return response()->json($country,'201'); // 201 for Created
}
```

In the Postman Select Post Method

`http://127.0.0.1:8000/api/country/`

Select Body then `form data` or `raw->json Format` of your choice to fill data I choose json
```json
{
"iso": "AH",
"name": "Ahmad",
"dname": "Ali",
"iso3": "AHM",
"position": 1,
"numcode": 0,
"phonecode": 0,
"created": 1,
"register_by": 1,
"modified": 1,
"modified_by": 1,
"record_deleted": 0
}
```
and hit **_Send_** to create into your database then check it by id

## Step 3 : Edit and Delete a Record



In App/http/Controller/Country `CountryController.php`
```php
// Todo: Update Data
public function updateCountryRecord(Request $request, CountryModel $country)
{
    $country->update($request->all());
    return response()->json($country,'200'); // 200 (OK)
}
// Todo: Delete Data
public function deleteCountryRecord(CountryModel $country)
{
    $country->delete();
    return response()->json(null,'204'); // 204 (NO Content)
}
```
- **Update** <br>
in Postman Select Put Method and change any of field by using _**param**_ or _**form data**_ or _**raw->json**_
`http://127.0.0.1:8000/api/country/23`

```json
{
"name": "AAAAAA",
"dname": "bbb",
}
```
and hit **_Send_** to Update data into your database then check it by id

- **Delete** <br>
in Postman Select DELETE Method 
`http://127.0.0.1:8000/api/country/23`

and hit **_Send_** to Delete data from your database then check it by id it exists or not


## Step 4 : Validation

We haven't use any validation 


In App/http/Controller/Country `CountryController.php`


```php
 // Todo: Show By ID
    public function showCountryByID($id)
    {
        $country=CountryModel::find($id);
        
        if (is_null($country)){
            return response()->json(["message"=>"No Record Found"],404);
        }
        return response()->json($country,200); // 200 is status
    }

    // Todo: Store Data
    public function storeCountryRecord(Request $request)
    {
        $rules = [
            'name'=>'required|min:3',
            'iso'=>'required|min:2|max:2',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()){
            return response()->json($validator->errors(),400); # 400 (Bad Request)
        }

        $country = CountryModel::create($request->all());
        return response()->json($country,201); // 201 for Created
    }

    // Todo: Update Data
    public function updateCountryRecord(Request $request, $id)
    {
        $country=CountryModel::find($id);
        if (is_null($country)){
            return response()->json("No Record Found",404);
        }
        $country->update($request->all());
        return response()->json($country,200); // 200 (OK)
    }
    // Todo: Delete Data
    public function deleteCountryRecord($id)
    {

        $country=CountryModel::find($id);
        if (is_null($country)){
            return response()->json("No Record Found",404);
        }
        $country->delete();
        return response()->json(null,204); // 204 (NO Content)
    }

```

## Step 5 : Resource Controller
In this we create a Resource Controller by
```bash
php artisan make:controller Country/CountryResourceController -r
```
Routes `api.php`
```php
Route::resource('countryResource',\App\Http\Controllers\Country\CountryResourceController::class);
```
Copy show, update, create and delete from `CountryController` to `CountryResourceController`

```php
class CountryResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $country = CountryModel::all();
        return response()->json($country,'200'); // 200 is status
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required|min:3',
            'iso'=>'required|min:2|max:2',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()){
            return response()->json($validator->errors(),400); # 400 (Bad Request)
        }

        $country = CountryModel::create($request->all());
        return response()->json($country,201); // 201 for Created
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country=CountryModel::find($id);
        if (is_null($country)){
            return response()->json(["message"=>"No Record Found"],404);
        }
        return response()->json($country,200); // 200 is status
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $country=CountryModel::find($id);
        if (is_null($country)){
            return response()->json("No Record Found",404);
        }
        $country->update($request->all());
        return response()->json($country,200); // 200 (OK)
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country=CountryModel::find($id);
        if (is_null($country)){
            return response()->json("No Record Found",404);
        }
        $country->delete();
        return response()->json(null,204); // 204 (NO Content)
    }
}
```
In postman use accordingly for **_CRUD_**
`http://127.0.0.1:8000/api/countryResource/1`

## Step 6 : Token Authentication

A token is a Piece of data which only Server could Possibly have created and which contains enough data to identify a particular user.
```bash
composer require laravel/ui
php artisan ui vue --auth
npm install
php artisan migrate:fresh
npm run serve
```
Register a User `http://127.0.0.1:8000/register`

- Create Middleware
```bash
php artisan make:middleware AuthKey
```

- In App/Http/Middleware `AuthKey.php`
```php
 public function handle(Request $request, Closure $next)
    {
        $token = $request->header('APP_KEY');
        if ($token != 'ABCDEFGHI'){
            return response()->json(['message'=>'App Key Not Found'],401); # Status 401 (Unauthorized)
        }
        return $next($request);
    }
```
in `Kernal.php`
Register your Middleware
```php
protected $middlewareGroups = [
        'web' => [
                ...
         ],
         'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AuthKey::class, // Your Middleware
        ],
      ];

```

In `Postman` Select Get Method `http://127.0.0.1:8000/country`
it  Show error
```json
{
    "message": "App Key Not Found"
}
```
So in Postman at Headers set this

Key          | Value
------------ | -------------
APP_KEY      | ABCDEFGHI

Now Send and get Result
```json
{
    "id": 1,
    "iso": "XX",
    "name": "UNKNOWN",
    "dname": "Unknown",
    "iso3": "XX",
    "position": 1,
    "numcode": 0,
    "phonecode": 0,
    "created": 1617102247,
    "register_by": 1,
    "modified": 1617102247,
    "modified_by": 1,
    "record_deleted": 0
}
```

## Step 7 : Basic Authentication

So we create a Middleware
```bash
php artisan make:middleware AuthBasic
```

- In App/Http/Middleware `AuthBasic.php`
```php
public function handle(Request $request, Closure $next)
{
    # onceBasic => this is HTTP basic Authentication without setting a user identifier cookie in the session
    if (Auth::onceBasic()) {
        return response()->json(['message'=>'Authentication Failed'],401); # Status 401 (Unauthorized)
    } else {
        return $next($request);
    }
}
```
in `Kernal.php`
Register your Middleware
```php
protected $middlewareGroups = [
        'web' => [
                ...
         ],
         'api' => [
              'throttle:api',
              \Illuminate\Routing\Middleware\SubstituteBindings::class,
           //  App\Http\Middleware\AuthKey::class,
              App\Http\Middleware\AuthBasic::class, # Your Middleware
          ],
      ];
```

In PostMan Select Authorization there are various Options from them use Basic Auth

Enter Your Login and Password in my case 

login: `test@admin.com`

password: `admin123`

then it shows results otherwise it throw error `Authentication Failed` which we set in our middleware







## Step 8 : OAuth2

1. OAuth is Open-standard authorization protocol or framework
1. OAuth doesn't share password data but instead uses authorization tokens to prove an identity between consumers and service provider



## Step 9 : OAuth2 Setup Passport

```bash
composer require laravel/passport
php artisan migrate:fresh
php artisan passport:install
```
- App\Models\ `User.php`

```php
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```
- App\Providers\ `AuthServiceProvider`
```php
protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }
    }
```
- config/ `auth.php`
```php 
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```

## Step 10 : OAuth2 Implementation

comment the AuthBasic Middleware form the `Kernal.php`
```php
'api' => [
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
//            AuthKey::class,
//            AuthBasic::class,
],
```
- App\Providers\ `AuthServiceProvider`

```php
public function boot()
{
    $this->registerPolicies();

    if (! $this->app->routesAreCached()) {
        Passport::routes();
        // Expiry of Access Token
        $startTime = date("Y-m-d H:i:s");
        $endTime  = date("Y-m-d H:i:s",strtotime('+7 days +1 hour +30 minutes +45 seconds',strtotime($startTime)));
        $expTime = \DateTime::createFromFormat("Y-m-d H:i:s", $endTime);
        Passport::tokensExpireIn($expTime);
    }
}
```

Now 
```bash
php artisan passport:client

 Which user ID should the client be assigned to?:
 > 3

 What should we name the client?:
 > abc

 Where should we redirect the request after authorization? [http://learning_restapi.test/auth/callback]:
 >

New client created successfully.
Client ID: 3
Client secret: Di5zMZU5LlcK6nwTzI0x08qOHQPLshR0hz3wcExd

```
now in the Postman Get with request `http://127.0.0.1:8000/api/countryResource/1`
in the preview tab it show the login page
- In the new tab of Postman
  `http://127.0.0.1:8000/oauth/token` with Post Request
  
```json
{
    "error": "unsupported_grant_type",
    "error_description": "The authorization grant type is not supported by the authorization server.",
    "hint": "Check that all required parameters have been provided",
    "message": "The authorization grant type is not supported by the authorization server."
}
```
- Now Select the Body and Form Data here we wil send some key and value

Key             | Value
------------    | -------------
grant_type      | client_credentials
client_id       | 3
client_secret   | Di5zMZU5LlcK6nwTzI0x08qOHQPLshR0hz3wcExd
Send Press and it genrate access_token and token type is bearer
```json
{
    "token_type": "Bearer",
    "expires_in": 610245,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiMTMwN2IzYmI2NWZhMmVjNzk0MGU4OWZkNjVmNzg5YzliMDhiZDNkNWVmMWNlMTQ4MTE3N2JlNjkxZWE3Y2EyODY3YWZlZjA1NDhmYTJjNzMiLCJpYXQiOjE2MTcxODg5NjcuNjYzNzc1LCJuYmYiOjE2MTcxODg5NjcuNjYzNzc5LCJleHAiOjE2MTc3OTkyMTIuMDkzOTc5LCJzdWIiOiIiLCJzY29wZXMiOltdfQ.wr-Myvk5MPqYG-mfgxJGVkgeg9hDo9bzYtd7c0KiF9NJZAWgt-kZxS2vfgBa3xDyn1pjiqevqq4d3cff2OqrmvnvN2ETx8NwSHAyL-pZw1tNQ9rn-nXZ81D-dGVAhGDjLH4mXRWqQMLX1CVbZco0HfFcbodSJe-FAlCNINdTPQxbnEZ7VW_SdjDtaP9vgklu7nRebdpYegShUQQJWJI1coQLfTPzkUS9AvWTrLCfWLZR88mcFw8-vQ0BjW51hxCtf-pdRzC-49gTiwTWf8s__FMFndi9RxqCHynW0cg-jxEZliknll5Qgv5rtLPG96fsAI9sFPGMV1jJDiLwwfJWSbDrqKpDp6avKT6jcB5HkkJnsr6L2wzfbvsYxSdioRHDylba0Mfai24pgNikdCGOIabH_Q6J5j1RHx-g2QaZd03qYgqi5AXJK7JI1i218ybgii0Ejj70ZZkOkwiarzZkCK8E0T9L8fSEklH4f4UNTouhTLYz6Ciir2D_UC75DJoUUg_Wk0DbCYx9dzBI05QrnoFeVuRZOVZrxJVqotWwtW08sLrYbvfMVPzTDMFgJDgWzUUM9IQ1LSi9vCwYGD-sLR-75PXOczm51uAP1g41stU13fjHwtFIz6e8nJuM1HYlUA9N5MzEt4G33cIWT-D2nYTlMGfc4lNfINT82jgctAM"
}
```
- Lets Start terminal again
```bash
php artisan passport:client

 Which user ID should the client be assigned to?:
 > 1

 What should we name the client?:
 > learning Laravel

 Where should we redirect the request after authorization? [http://learning_restapi.test/auth/callback]:
 > http://127.0.0.1:8001/callback

New client created successfully.
Client ID: 4
Client secret: 8qZm2hRfCfodBEx5gd5gAwtfGZBs31LGg1F3Gkas
```

1. You need to add the CheckClientCredentials middleware to the $routeMiddleware property of your app/Http/ `Kernel.php` file:
```php

use Laravel\Passport\Http\Middleware\CheckClientCredentials;


protected $routeMiddleware = [
//...
'client' => CheckClientCredentials::class,
];
```
2. In Routes/ `api.php` 
```php 
Route::apiResource('countryResource', \App\Http\Controllers\Country\CountryResourceController::class)->middleware('client');
```

In the Postman `http://127.0.0.1:8000/api/countryResource/1` Get Request in Headers key and value

Key             | Value
------------    | -------------
Accept          | application/json
Authorization   | Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.........

and now hit send to get result
```json
{
    "id": 1,
    "iso": "XX",
    "name": "UNKNOWN",
    "dname": "Unknown",
    "iso3": "XX",
    "position": 1,
    "numcode": 0,
    "phonecode": 0,
    "created": 1617183605,
    "register_by": 1,
    "modified": 1617183605,
    "modified_by": 1,
    "record_deleted": 0
}
```
## Step 11 :  Pagination

































## ---------------------- END ----------------------

------------------------------------------------------

## Playlist Link
[![RestAPI Laravel](https://img.youtube.com/vi/rDJ7BebkNso/0.jpg)](https://www.youtube.com/watch?v=rDJ7BebkNso&list=PLYVcyg3AF-zvDDXBLDyn9UJSgAYMWpUS3&index=1)

But it is in Old Version I do it in Laravel 8
