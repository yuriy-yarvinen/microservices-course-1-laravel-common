<?php

namespace Microservices;

use Microservices\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class UserService{

	private $endpoint;

	public function __construct()
	{
		$this->endpoint = env('USER_SERVICE_URL');
	}
	
	public function headers()
	{
		$headers = [];
        if($jwt = request()->cookie('jwt')){
            $headers['Authorization'] = "Bearer {$jwt}";
        }

		if(request()->headers->get('Authorization')){
			$headers['Authorization'] = request()->headers->get('Authorization');
		}

		return $headers;
	}

	public function request()
	{
		return Http::withHeaders($this->headers());
	}

	public function getUser(): User
	{
        $json = $this->request()->get($this->endpoint."/user")->json();

		return new User($json);
	}

	public function isAdmin()
	{
		return $this->request()->get($this->endpoint."/admin")->successful();
	}
	
	public function isInfluencer()
	{
		return $this->request()->get($this->endpoint."/influencer")->successful();
	}

	public function allows($ability, $arguments)
	{
		Gate::forUser($this->getUser())->authorize($ability, $arguments);
	}

	public function all($page)
	{
		return $this->request()->get($this->endpoint."/users?page={$page}")->json();
	}

	public function getCustomUsers($data)
	{
		return $this->request()->post($this->endpoint."/customUsers", $data)->json();
	}

	public function get($id): User
	{
		$json = $this->request()->get($this->endpoint."/users/{$id}")->json();

		return new User($json);

	}

	public function create($data): User
	{
		$json = $this->request()->post($this->endpoint."/users", $data)->json();

		return new User($json);

	}

	public function update($id, $data): User
	{
		$json = $this->request()->put($this->endpoint."/users/{$id}", $data)->json();

		return new User($json);

	}

	public function delete($id)
	{
		return $this->request()->delete($this->endpoint."/users/{$id}")->successful();
	}
}