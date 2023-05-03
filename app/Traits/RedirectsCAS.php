<?php

namespace Illuminate\Foundation\Auth;

trait RedirectsCAS
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectCAS')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectCAS') ? $this->redirectCAS : '/login/cas';
    }
}
