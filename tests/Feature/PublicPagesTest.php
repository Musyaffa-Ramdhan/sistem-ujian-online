<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Smoke test halaman publik: memastikan route yang tidak memerlukan login
 * dapat dirender tanpa error (status 200).
 */
class PublicPagesTest extends TestCase
{
    public function test_halaman_welcome_dapat_diakses(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_halaman_login_siswa_dapat_diakses(): void
    {
        $this->get('/siswa/login')->assertStatus(200);
    }

    public function test_halaman_login_guru_dapat_diakses(): void
    {
        $this->get('/guru/login')->assertStatus(200);
    }

    public function test_halaman_login_admin_dapat_diakses(): void
    {
        $this->get('/admin/login')->assertStatus(200);
    }
}
