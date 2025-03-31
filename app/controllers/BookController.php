<?php

namespace App\Controllers;

use App\Services\ResponseService;
use App\Models\Book;

class BookController extends Controller
{
    private $bookModel;

    function __construct()
    {
        $this->bookModel = new Book();
    }

    function getAll()
    {
        $page = (int)($_GET["page"] ?? 1);
        ResponseService::Send($this->bookModel->getAll($page));
    }

    function get($id)
    {
        ResponseService::Send($this->bookModel->get($id));
    }

    function create()
    {
        $data = $this->decodePostData();
        $this->validateInput(["title", "author"], $data);

        $newBook = $this->bookModel->create($data);
        ResponseService::Send($newBook);
    }

    function update($id)
    {
        $data = $this->decodePostData();
        $this->validateInput(["title", "author"], $data);

        $updatedBook = $this->bookModel->update($id, $data);
        ResponseService::Send($updatedBook);
    }

    function delete($id)
    {
        $this->bookModel->delete($id);
        ResponseService::Send([], 204);
    }
}
