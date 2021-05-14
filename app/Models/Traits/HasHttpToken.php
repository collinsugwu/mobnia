<?php


namespace App\Models\Traits;


trait HasHttpToken
{
    /**
     * @return string
     * @throws \Exception
     */
    private function createToken()
    {
        return sha1($this->id . bin2hex(random_bytes(16)));
    }


    /**
     * Generate a new token for user
     * @param bool $persist True, is new token should be saved, else false
     * @throws \Exception
     */
    public function refreshToken($persist = true)
    {
        $this->api_token = $this->createToken();
        if ($persist) {
            $this->save();
        }
    }

    /**
     * Check if a token is valid
     * @return bool
     */
    public function isTokenValid()
    {
        return $this->last_seen &&
            $this->last_seen->diffInMinutes() < env('TOKEN_EXPIRE', 60);
    }

    /**
     * Find user with the given token
     * @param $token
     * @return self|null
     */
    public static function findWithToken($token)
    {
        return self::where('api_token', $token)->first();
    }
}
