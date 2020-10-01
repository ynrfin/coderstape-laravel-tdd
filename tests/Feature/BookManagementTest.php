<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Book;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {

        # disable exception handling
        # returning actual error instead of HTTP code
        # from exception handling
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' =>'Cool Book Title',
            'author' =>'author',
        ]);
        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required()
    {
        $response = $this->post('/books', [
            'title' =>'',
            'author' =>'author',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_author_is_required()
    {
        $response = $this->post('/books', [
            'title' =>'title',
            'author' =>'',
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test */
    function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' =>'Cool Book Title',
            'author' =>'author',
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'new title',
            'author' => 'new author'
        ]);

        $this->assertEquals('new title', Book::first()->title);
        $this->assertEquals('new author', Book::first()->author);
        $response->assertRedirect($book->fresh()->path());

    }

    /** @test */
    function a_book_can_be_deleted()
    {
        $this->post('/books', [
            'title' =>'Cool Book Title',
            'author' =>'author',
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }


}
