<?php

namespace Tests\Feature;

use Tests\TestCase;

class ForumPagesTest extends TestCase
{
    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Foro Comunidad');
        $response->assertSee('Álbum');
        $response->assertSee('Recetas');
        $response->assertSee('Fertilizantes');
    }

    public function test_album_page_is_accessible(): void
    {
        $response = $this->get('/album');

        $response->assertStatus(200);
        $response->assertSee('Álbum de fotos');
        $response->assertSee('Momentos destacados');
    }
}
