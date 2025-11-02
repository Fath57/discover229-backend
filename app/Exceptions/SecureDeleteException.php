<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SecureDeleteException extends Exception
{
    protected $model;

    protected $code;

    protected $message;

    public function __construct($model)
    {
        parent::__construct();

        $this->model = $model;
        $this->message = 'Cette infomation ne peut pas être supprimée, elle est liée à une autre entité';

        abort(ResponseAlias::HTTP_CONFLICT, $this->message);
    }

    /**
     * Get the affected Eloquent model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }
}
