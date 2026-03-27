<?php

it('redirects guests from protected pages to login', function (): void {
    visit('/cart')->assertPathIs('/login');
    visit('/payment')->assertPathIs('/login');
    visit('/admin/stats')->assertPathIs('/login');
    visit('/favorites')->assertPathIs('/login');
});
