<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helper;
use Tests\TestCase;

final class AuthControllerTest extends TestCase
{
  use RefreshDatabase;

  public function testLogin()
  {
    $password = 'admin123';
    $user = Helper::createUser($password);


    $response = $this->post(route('auth.login'), [
      'email' => $user->email,
      'password' => $password
    ]);

    $response->assertJsonStructure([
      'data' => [
        'token'
      ]
    ]);
  }

  public function testAccess()
  {
    $response = $this->get(route('auth.me'));

    $response->assertJsonFragment([
      'status' => 401
    ]);
  }

  public function testMe()
  {
    $user = Helper::createUser();
    $token = auth()->login($user);

    $response = $this->get(route('auth.me'), [
      'Authorization' => Helper::authHeader($token)
    ]);

    $response->assertJsonFragment([
      'data' => $user->toArray()
    ]);
  }

  public function testLogout()
  {
    $user = Helper::createUser();
    $token = auth()->login($user);

    $response = $this->get(route('auth.logout'), [
      'Authorization' => Helper::authHeader($token)
    ]);

    $response->assertJsonFragment([
      'status' => 204
    ]);

    $response = $this->get(route('auth.logout'), [
      'Authorization' => Helper::authHeader($token)
    ]);

    $response->assertJsonFragment([
      'status' => 401
    ]);
  }

  public function testRefresh()
  {
    $password = 'admin123';
    $user = Helper::createUser($password);


    $response = $this->post(route('auth.login'), [
      'email' => $user->email,
      'password' => $password
    ]);

    $token = json_decode($response->getContent(), true)['data']['token'];

    $response = $this->get(route('auth.refresh'), [
      'Authorization' => Helper::authHeader($token)
    ]);

    $response->assertJsonStructure([
      'data' => [
        'token'
      ]
    ]);
  }

  public function testRefreshNotExists()
  {
    $response = $this->get(route('auth.refresh'), [
      'Authorization' => Helper::authHeader('asdf')
    ]);

    $response->assertJsonFragment([
      'status' => 401
    ]);
  }
}
