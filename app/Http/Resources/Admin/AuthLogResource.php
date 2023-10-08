<?php

namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Agent\Agent;

class AuthLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $agent  = tap(new Agent, fn($agent) => $agent->setUserAgent($this->user_agent));
        $user   = $this->authenticatable_type::find($this->authenticatable_id) ?? false;

        return [
            "id"            => $this->id,
            "ip_address"    => $this->ip_address,
            "login_success" => $this->login_successful === true ? 'Yes' : 'No',
            "login"         => $this->login_at ? $this->login_at->format('d,M Y H:i a') : '-',
            "logout"        => $this->logout_at ? $this->logout_at->format('d,M Y H:i a'): '-',
            "browser"       => $agent->browser(),
            "os_platform"   => $agent->platform(),
            "user"          => $user ? $user->name :null,
            // "location"      => $this->location && $this->location['default'] === false ? $this->location['city'] . ', ' . $this->location['state'] : '-',
        ];
    }
}
